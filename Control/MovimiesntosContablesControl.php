<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
// Traer un mes completo de movimientos contables puede implicar más de 100 llamadas
// secuenciales a la API de SIESA (~10-12 minutos); el límite por defecto de PHP (120s)
// se queda corto para eso.
set_time_limit(1200);
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$ApiSiesa = new consultas();
$Movimiento = new configuracion();

// Traer varios días puede tardar varios minutos en llamadas a la API de Siesa,
// tiempo en el que la conexión a MySQL queda inactiva. En hosting compartido el
// servidor puede cerrarla por inactividad mucho antes de que termine el fetch,
// haciendo fallar todos los INSERT del final. Se extiende la sesión para evitarlo.
mysqli_query($conexion, "SET SESSION wait_timeout = 1200");
mysqli_query($conexion, "SET SESSION interactive_timeout = 1200");

// Procesa el cuerpo de una respuesta de la API de Siesa. Devuelve
// ['registros'=>[], 'totalPaginas'=>int|null, 'sinRegistros'=>bool] o null si la
// respuesta es inválida / la API respondió un error.
function procesarRespuestaSiesa($respuesta) {
    if (strpos($respuesta, 'No se encontraron registros') !== false) {
        return ['registros' => [], 'totalPaginas' => null, 'totalRegistros' => 0, 'sinRegistros' => true];
    }

    // Algunas notas de la API traen caracteres de control (saltos de línea, tabs) sin
    // escapar dentro de las cadenas del JSON, lo que lo vuelve inválido para json_decode.
    // Se limpian antes de decodificar.
    $respuestaLimpia = preg_replace('/[\x00-\x1F\x7F]+/', ' ', $respuesta);

    $data = json_decode($respuestaLimpia, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("procesarRespuestaSiesa: JSON inválido (" . json_last_error_msg() . "), largo=" . strlen($respuesta) . ", final=" . substr($respuesta, -300));
        return null;
    }

    // La API responde "codigo" distinto de 0 cuando rechaza la solicitud.
    if (isset($data['codigo']) && $data['codigo'] != 0) {
        error_log("procesarRespuestaSiesa: la API respondió error: " . ($data['mensaje'] ?? '') . ' - ' . ($data['detalle'] ?? ''));
        return null;
    }

    $registrosPagina = $data['detalle']['Datos'] ?? $data['detalle']['Table'] ?? null;

    if ($registrosPagina === null) {
        // Cuando no hay registros para el rango pedido, la API a veces no incluye
        // "Datos"/"Table" en vez de devolver el mensaje de texto "No se encontraron
        // registros" (pasa con total_páginas/total_registros en 0). No es un error.
        $totalPaginasResp = $data['detalle']['total_páginas'] ?? null;
        if ($totalPaginasResp !== null && (int) $totalPaginasResp === 0) {
            return ['registros' => [], 'totalPaginas' => 0, 'totalRegistros' => 0, 'sinRegistros' => true];
        }

        error_log("procesarRespuestaSiesa: respuesta sin 'Datos'/'Table': " . substr($respuesta, 0, 300));
        return null;
    }

    return [
        'registros'      => $registrosPagina,
        'totalPaginas'   => $data['detalle']['total_páginas'] ?? null,
        'totalRegistros' => $data['detalle']['total_registros'] ?? null,
        'sinRegistros'   => false,
    ];
}

// Pide una sola página con cURL (para la página 1, que hace falta antes de saber
// cuántas páginas hay en total).
function obtenerPaginaSiesa($url, array $headersArray) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArray);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $respuesta = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($respuesta === false || $respuesta === '') {
        error_log("obtenerPaginaSiesa: fallo de conexión: {$error}");
        return null;
    }

    return procesarRespuestaSiesa($respuesta);
}

// Pide varias páginas al mismo tiempo con curl_multi (la API admite hasta 25 solicitudes
// concurrentes, según el header Connekta-Rate-Limit-Concurrent-Limit). $urls es
// [numPag => url]. Devuelve [numPag => resultado_de_procesarRespuestaSiesa_o_null].
function obtenerPaginasEnParalelo(array $urls, array $headersArray) {
    $multi = curl_multi_init();
    $handles = [];

    foreach ($urls as $numPag => $url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArray);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_multi_add_handle($multi, $ch);
        $handles[$numPag] = $ch;
    }

    $activos = null;
    do {
        $status = curl_multi_exec($multi, $activos);
        if ($activos) {
            curl_multi_select($multi);
        }
    } while ($activos > 0 && $status === CURLM_OK);

    $resultados = [];
    foreach ($handles as $numPag => $ch) {
        $respuesta = curl_multi_getcontent($ch);
        $error = curl_error($ch);
        curl_multi_remove_handle($multi, $ch);
        curl_close($ch);

        if ($respuesta === false || $respuesta === '' || $error) {
            error_log("obtenerPaginasEnParalelo: fallo de conexión en página {$numPag}: {$error}");
            $resultados[$numPag] = null;
            continue;
        }

        $resultados[$numPag] = procesarRespuestaSiesa($respuesta);
    }

    curl_multi_close($multi);

    return $resultados;
}

// Trae todos los movimientos contables entre $fechaDesde y $fechaHasta (formato "Y/m/d").
// La API de Siesa rechaza tamPag > 1000, así que 1000 es el tamaño de página más grande
// posible. Se pide la página 1 sola para saber cuántas páginas hay en total, y el resto se
// piden en paralelo (5 a la vez) en vez de una por una, para entrar dentro del timeout fijo
// de ~5 min del hosting.
//
// La paginación de Siesa no es estable: entre llamadas puede repetir registros de una página
// en otra, u omitir alguno por completo, sin avisar. Por eso, al final se deduplica por
// f351_rowid y se compara la cantidad resultante contra "total_registros" que reporta la
// propia API; si no coincide, se reintenta la traída completa (hasta 3 veces) en vez de
// devolver una lista incompleta como si estuviera bien.
//
// Con 1000 x 100 páginas caben 100.000 registros por rango de fechas; si "total_páginas" aun
// así lo supera, se parte el rango por la mitad y se une la información recursivamente
// (cada mitad se reconcilia por su cuenta).
function obtenerMovimientosContables($fechaDesde, $fechaHasta, $idCia, $headers) {
    $tamPag = 1000;
    $maxPaginas = 100;
    $concurrencia = 5;
    $intentosReconciliacion = 3;

    $headersArray = explode("\r\n", $headers);

    $fechaInicialApi = date('Ymd', strtotime($fechaDesde));
    $fechaFinalApi   = date('Ymd', strtotime($fechaHasta));

    $construirUrl = function ($numPag) use ($idCia, $fechaInicialApi, $fechaFinalApi, $tamPag) {
        return "https://servicios.siesacloud.com/api/connekta/v3/ejecutarconsulta"
            . '?' . http_build_query([
                'idCompania'  => '6434',
                'descripcion' => 'regencycolombia_MovimientoContable',
                'paginacion'  => "numPag={$numPag}|tamPag={$tamPag}",
                'parametros'  => "f350_id_cia={$idCia}|f350_fecha_inicial = {$fechaInicialApi}|f350_fecha_final = {$fechaFinalApi}",
            ]);
    };

    for ($intento = 1; $intento <= $intentosReconciliacion; $intento++) {
        $resultadoPag1 = obtenerPaginaSiesa($construirUrl(1), $headersArray);

        if ($resultadoPag1 === null) {
            error_log("obtenerMovimientosContables: fallo al traer la página 1, compañía {$idCia}, fecha {$fechaDesde}, intento {$intento}");
            continue;
        }

        if ($resultadoPag1['sinRegistros']) {
            return [];
        }

        $registros      = $resultadoPag1['registros'];
        $totalPaginas   = $resultadoPag1['totalPaginas'];
        $totalRegistros = $resultadoPag1['totalRegistros'];

        if ($totalPaginas === null || $totalPaginas <= 1) {
            return $registros;
        }

        if ($totalPaginas > $maxPaginas) {
            $desdeTs = strtotime($fechaDesde);
            $hastaTs = strtotime($fechaHasta);

            if ($hastaTs > $desdeTs) {
                $diasTotales = ($hastaTs - $desdeTs) / 86400;
                $medioTs = $desdeTs + (int) floor($diasTotales / 2) * 86400;

                $fechaMedio1 = date('Y/m/d', $medioTs);
                $fechaMedio2 = date('Y/m/d', $medioTs + 86400);

                $primeraMitad = obtenerMovimientosContables($fechaDesde, $fechaMedio1, $idCia, $headers);
                $segundaMitad = obtenerMovimientosContables($fechaMedio2, $fechaHasta, $idCia, $headers);

                if ($primeraMitad === null || $segundaMitad === null) {
                    return null;
                }

                return array_merge($primeraMitad, $segundaMitad);
            }

            // Un solo día ya no se puede partir más y aun así supera el tope de páginas:
            // se señala como error en vez de devolver información incompleta en silencio.
            error_log("obtenerMovimientosContables: {$fechaDesde} compañía {$idCia} supera {$maxPaginas} páginas de {$tamPag} registros y no se puede partir más");
            return null;
        }

        $fetchOk = true;

        for ($inicio = 2; $inicio <= $totalPaginas; $inicio += $concurrencia) {
            $fin = min($inicio + $concurrencia - 1, $totalPaginas);

            $urls = [];
            for ($numPag = $inicio; $numPag <= $fin; $numPag++) {
                $urls[$numPag] = $construirUrl($numPag);
            }

            $resultadosTanda = obtenerPaginasEnParalelo($urls, $headersArray);

            foreach ($resultadosTanda as $numPag => $resultado) {
                // Con varias solicitudes grandes al mismo tiempo, de vez en cuando una llega
                // cortada o corrupta; se reintenta esa página sola (sin concurrencia) un par
                // de veces antes de dar la traída completa por fallida.
                for ($intentoPag = 1; $resultado === null && $intentoPag <= 2; $intentoPag++) {
                    error_log("obtenerMovimientosContables: reintentando página {$numPag} (intento {$intentoPag}), compañía {$idCia}, fecha {$fechaDesde}");
                    $resultado = obtenerPaginaSiesa($urls[$numPag], $headersArray);
                }

                if ($resultado === null) {
                    error_log("obtenerMovimientosContables: fallo en página {$numPag} tras reintentos, compañía {$idCia}, fecha {$fechaDesde}, intento {$intento}");
                    $fetchOk = false;
                    break 2;
                }
                if (!$resultado['sinRegistros']) {
                    $registros = array_merge($registros, $resultado['registros']);
                }
            }
        }

        if (!$fetchOk) {
            continue;
        }

        // La API puede repetir el mismo movimiento en más de una página; se deduplica por
        // f351_rowid, que identifica cada línea de movimiento de forma única.
        $vistos = [];
        $registrosUnicos = [];
        foreach ($registros as $registro) {
            $rowId = $registro['f351_rowid'] ?? null;
            if ($rowId !== null) {
                if (isset($vistos[$rowId])) {
                    continue;
                }
                $vistos[$rowId] = true;
            }
            $registrosUnicos[] = $registro;
        }

        if ($totalRegistros !== null && count($registrosUnicos) !== (int) $totalRegistros) {
            error_log("obtenerMovimientosContables: el conteo no coincide tras deduplicar (obtenidos=" . count($registrosUnicos) . ", esperados={$totalRegistros}), compañía {$idCia}, fecha {$fechaDesde}, intento {$intento} de {$intentosReconciliacion}");
            continue;
        }

        return $registrosUnicos;
    }

    error_log("obtenerMovimientosContables: no se logró traer el conteo completo tras {$intentosReconciliacion} intentos, compañía {$idCia}, fecha {$fechaDesde}");
    return null;
}

// Inserta $filas en movimientos_contables en lotes de $tamLote filas por sentencia, en vez
// de un INSERT por fila. Con rangos grandes (30 mil+ filas) hacer un INSERT a la vez era lo
// más lento del proceso completo; agrupar los inserts baja ese tiempo de minutos a segundos.
function insertarMovimientosPorLotes($conexion, $filas, $anio, $tamLote = 500) {
    $columnas = "anio, periodo, empresa, nombre_empresa, tipo_documento, docto, periodo_docto,
        fecha_actualizacion_docto, fecha_aprobacion_docto, fecha_anulacion_docto,
        usuario_creacion_docto, usuario_aprobacion_docto, usuario_anulacion_docto, notas_docto,
        Fecha_docto, cuenta, nombre_auxiliar, cuenta_n1, nombre_cuenta_n1, cuenta_n2,
        nombre_cuenta_n2, cuenta_n3, nombre_cuenta_n3, cuenta_n4, nombre_cuenta_n4, co_movto, co,
        regional_co_movto, regional, unidad_de_negocio, nombre_unidad_de_negocio, tercero,
        nombre_tercero, grupo_ccosto, centro_de_costo, nombre_centro_de_costo, debitos, creditos,
        Deb_Libro_2, cred_Libro_2, Movto_libro2, desc_regional";

    $insertados = 0;
    $errores    = 0;

    foreach (array_chunk($filas, $tamLote) as $lote) {
        $filasSql = [];

        foreach ($lote as $fila) {
            $valores = [
                limpiarCadena($anio),
                limpiarCadena($fila['f350_id_periodo'] ?? ''),
                limpiarCadena($fila['f350_id_cia'] ?? ''),
                limpiarCadena($fila['nombre_empresa'] ?? ''),
                limpiarCadena($fila['f350_id_tipo_docto'] ?? ''),
                limpiarCadena($fila['f350_consec_docto'] ?? ''),
                limpiarCadena($fila['f350_id_periodo'] ?? ''),
                limpiarCadena($fila['f350_fecha_ts_actualizacion'] ?? ''),
                limpiarCadena($fila['f350_fecha_ts_aprobacion'] ?? ''),
                limpiarCadena($fila['f350_fecha_ts_anulacion'] ?? ''),
                limpiarCadena($fila['f350_usuario_creacion'] ?? ''),
                limpiarCadena($fila['f350_usuario_aprobacion'] ?? ''),
                limpiarCadena($fila['f350_usuario_anulacion'] ?? ''),
                limpiarCadena($fila['f350_notas'] ?? ''),
                limpiarCadena($fila['f350_fecha'] ?? ''),
                limpiarCadena($fila['f253_id'] ?? ''),
                limpiarCadena($fila['f253_descripcion'] ?? ''),
                limpiarCadena($fila['n1_id'] ?? ''),
                limpiarCadena($fila['n1_desc'] ?? ''),
                limpiarCadena($fila['n2_id'] ?? ''),
                limpiarCadena($fila['n2_desc'] ?? ''),
                limpiarCadena($fila['n3_id'] ?? ''),
                limpiarCadena($fila['n3_desc'] ?? ''),
                limpiarCadena($fila['n4_id'] ?? ''),
                limpiarCadena($fila['n4_desc'] ?? ''),
                limpiarCadena($fila['f351_id_co_mov'] ?? ''),
                limpiarCadena($fila['f285_desc'] ?? ''),
                limpiarCadena($fila['f285_id_regional'] ?? ''),
                limpiarCadena($fila['f285_id_regional'] ?? ''),
                limpiarCadena($fila['f351_id_un'] ?? ''),
                limpiarCadena($fila['f281_desc'] ?? ''),
                limpiarCadena($fila['f200_nit'] ?? ''),
                limpiarCadena($fila['f200_razon_social'] ?? ''),
                limpiarCadena($fila['f284_id_grupo_ccosto'] ?? ''),
                limpiarCadena($fila['f284_id'] ?? ''),
                limpiarCadena($fila['f284_desc'] ?? ''),
                limpiarCadena($fila['f351_valor_db'] ?? ''),
                limpiarCadena($fila['f351_valor_cr'] ?? ''),
                limpiarCadena($fila['f351_valor_db2'] ?? ''),
                limpiarCadena($fila['f351_valor_cr2'] ?? ''),
                limpiarCadena($fila['movi_cuenta'] ?? ''),
                limpiarCadena($fila['regional_desc'] ?? ''),
            ];
            $filasSql[] = "('" . implode("','", $valores) . "')";
        }

        $sql = "INSERT INTO movimientos_contables ($columnas) VALUES " . implode(',', $filasSql);
        $resultado = mysqli_query($conexion, $sql);

        if ($resultado) {
            $insertados += count($lote);
        } else {
            $errores += count($lote);
            error_log("insertarMovimientosPorLotes: falló lote de " . count($lote) . " filas: " . mysqli_error($conexion));
        }
    }

    return ['insertados' => $insertados, 'errores' => $errores];
}

switch ($_GET['tipo']) {


    case 'registrarMovimientoEmpresa':
      // Proceso independiente de generarFilasContables: una sola empresa (la que
      // se elige en el modal) y un rango de fechas puntual (máx. 15 días). Borra
      // lo que ya estuviera registrado para esa empresa en ese rango y registra
      // la información nueva. La confirmación del usuario ya se pidió en el JS.

      $empresas = [
        1 => 'REGENCY SERVICES DE COLOMBIA S.A.S',
        2 => 'REGENCY HEALTH SERVICES S.A.S',
        3 => 'PROTECCION DE INFRAESTRUCTURA COLOMBIA - PROTINCO LTDA',
        4 => 'CONSORCIO RQS',
        5 => 'REGENCY TECH S.A.S',
        6 => 'TRANSPORTADORA DE VALORES ANDINA LTDA',
        7 => 'CONSORCIO PEAJES CUNDINAMARCA 24',
        8 => 'CONSORCIO PEAJES CUNDINAMARCA 24',
        9 => 'CONSORCIO PEAJES 2526',
      ];

      $idCia = (int) ($_GET['idCia'] ?? 0);

      if (!isset($empresas[$idCia])) {
        http_response_code(400);
        echo json_encode(["error" => "Empresa inválida"]);
        exit;
      }

      $nombreEmpresa = $empresas[$idCia];

      $fechaDesde = $_GET['desde'] ?? '';
      $fechaHasta = $_GET['hasta'] ?? '';

      if (!$fechaDesde || !$fechaHasta) {
        http_response_code(400);
        echo json_encode(["error" => "Debe indicar fecha desde y fecha hasta"]);
        exit;
      }

      $diasRango = (strtotime($fechaHasta) - strtotime($fechaDesde)) / 86400;

      if ($diasRango < 0 || $diasRango > 15) {
        http_response_code(400);
        echo json_encode(["error" => "El rango entre fecha inicio y fecha fin no puede ser mayor a 15 días"]);
        exit;
      }

      $anio = date('Y', strtotime($fechaDesde));
      $mes  = date('n', strtotime($fechaDesde));
      $where_condition = array('anio_cierre'=>$anio,' and mes_cierre'=>$mes);
		  $rspta = $Movimiento->validar('cierre_moviminetos_contables', $where_condition);  
		  if($rspta->num_rows>0){
        echo json_encode(["mensaje" => "Ya se encuentra registrado el cierre de movimientos contables para las fechas seleccionadaas"]);
        exit;

      }

      $headers = implode("\r\n", [
        "ConniKey: 0d95972b116a18c6507102a7c2c1ffde",
        "ConniToken: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1laWRlbnRpZmllciI6IjI4NjlmZTQ4LTRmZjctNGY3Mi04ZTFhLTdmMTk5YjliYTEwNyIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcHJpbWFyeXNpZCI6IjhhMTY2ODI1LTkyYjgtNDk1Mi1iZWJiLTFmMzA1NTFhOGUxZSJ9.Wf27eJj8AudpQKONSSDnQzvFLXPRbmTkEc4WwadN54c"
      ]);

      $todosLosRegistros = obtenerMovimientosContables($fechaDesde, $fechaHasta, $idCia, $headers);

      if ($todosLosRegistros === null) {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo conectar con la API"]);
        exit;
      }

      $partirFecha = function ($valor) {
        return $valor ? explode('T', $valor)[0] : '';
      };

      $cuentas             = [];
      $idsCentrosCosto     = [];
      $idsCentrosOperacion = [];
      $idsUnidadesNegocio  = [];
      $idsregionales  = [];
      $filas               = [];

      foreach ($todosLosRegistros as $d) {
        $key             = $d['f253_id'] ?? '';
        $centroCosto     = $d['f284_id'] ?? '';
        $centroOperacion = $d['f351_id_co_mov'] ?? '';
        $unidadNegocio   = $d['f351_id_un'] ?? '';

        if (!isset($idsCentrosCosto[$centroCosto])) {
          $idsCentrosCosto[$centroCosto] = $centroCosto;
        }
        if (!isset($idsCentrosOperacion[$centroOperacion])) {
          $idsCentrosOperacion[$centroOperacion] = $centroOperacion;
        }
        if (!isset($idsUnidadesNegocio[$unidadNegocio])) {
          $idsUnidadesNegocio[$unidadNegocio] = $unidadNegocio;
        }

        if (!isset($cuentas[$key])) {
          $str = (string) $key;
          $cuentas[$key] = [
            'f253_id' => $d['f253_id'] ?? '',
            'n1' => substr($str, 0, 1),
            'n2' => substr($str, 0, 2),
            'n3' => substr($str, 0, 4),
            'n4' => substr($str, 0, 6),
          ];
        }

        $movi = ($d['f351_valor_db2'] ?? '0') != '0'
          ? $d['f351_valor_db2']
          : '-' . ($d['f351_valor_cr2'] ?? '');

        $filas[] = [
          'f350_id_periodo'             => $d['f350_id_periodo'] ?? '',
          'f350_id_cia'                 => $d['f350_id_cia'] ?? '',
          'f350_id_tipo_docto'          => $d['f350_id_tipo_docto'] ?? '',
          'f350_consec_docto'           => $d['f350_consec_docto'] ?? '',
          'f350_id_periodo2'            => $d['f350_id_periodo'] ?? '',
          'f350_fecha_ts_actualizacion' => $partirFecha($d['f350_fecha_ts_actualizacion'] ?? ''),
          'f350_fecha_ts_aprobacion'    => $partirFecha($d['f350_fecha_ts_aprobacion'] ?? ''),
          'f350_fecha_ts_anulacion'     => $partirFecha($d['f350_fecha_ts_anulacion'] ?? ''),
          'f350_usuario_creacion'       => $d['f350_usuario_creacion'] ?? '',
          'f350_usuario_actualizacion'  => $d['f350_usuario_actualizacion'] ?? '',
          'f350_usuario_aprobacion'     => $d['f350_usuario_aprobacion'] ?? '',
          'f350_usuario_anulacion'      => $d['f350_usuario_anulacion'] ?? '',
          'f350_notas'                  => $d['f350_notas'] ?? '',
          'f350_fecha'                  => $partirFecha($d['f350_fecha'] ?? ''),
          'f253_id'                     => $d['f253_id'] ?? '',
          'f253_descripcion'            => $d['f253_descripcion'] ?? '',
          'f351_id_co_mov'              => $d['f351_id_co_mov'] ?? '',
          'f350_id_co'                  => $d['f350_id_co'] ?? '',
          'f351_id_un'                  => $d['f351_id_un'] ?? '',
          'f200_nit'                    => $d['f200_nit'] ?? '',
          'f200_razon_social'           => $d['f200_razon_social'] ?? '',
          'f284_descripcion'            => $d['f284_descripcion'] ?? '',
          'f284_id'                     => $d['f284_id'] ?? '',
          'f351_notas'                  => $d['f351_notas'] ?? '',
          'f351_valor_db'               => $d['f351_valor_db'] ?? '',
          'f351_valor_cr'               => $d['f351_valor_cr'] ?? '',
          'f351_valor_db2'              => $d['f351_valor_db2'] ?? '',
          'f351_valor_cr2'              => $d['f351_valor_cr2'] ?? '',
          'movi_cuenta'                 => $movi,
          'nombre_empresa'              => $nombreEmpresa,
        ];
      }
      unset($todosLosRegistros); // ya se copió lo necesario a $filas; libera la respuesta cruda de la API

      // Ids únicos de cuentas (n1..n4) a buscar en cuentas_siesa
      $idsCuentasNecesarias = [];
      foreach ($cuentas as $c) {
        foreach (['n1', 'n2', 'n3', 'n4'] as $nivel) {
          $id = trim($c[$nivel]);
          if ($id !== '') {
            $idsCuentasNecesarias[$id] = $id;
          }
        }
      }

      $mapaCuentas          = $ApiSiesa->buscarCuentasPorIds(array_values($idsCuentasNecesarias));
      $mapaCentrosCosto     = $ApiSiesa->buscarcentrosCostoPorIds(array_values($idsCentrosCosto));
      $mapaCentrosOperacion = $ApiSiesa->buscarcentrosoperativosPorIds(array_values($idsCentrosOperacion));
      $mapaUnidadesNegocio  = $ApiSiesa->buscarunidadesPorIds(array_values($idsUnidadesNegocio));

      // Se enriquece cada fila en el mismo arreglo (por referencia) en vez de con array_map,
      // que crearía una copia completa de $filas en memoria por cada pasada; con rangos
      // grandes (30-35 mil filas) eso duplicaba innecesariamente el uso de memoria.
      foreach ($filas as &$f) {
        $cuenta          = $cuentas[$f['f253_id']] ?? null;
        $centroCosto     = $mapaCentrosCosto[$f['f284_id']] ?? null;
        $centroOperacion = $mapaCentrosOperacion[$f['f351_id_co_mov']] ?? null;
        $unidadNegocio   = $mapaUnidadesNegocio[$f['f351_id_un']] ?? null;

        if ($cuenta) {
          $f['n1_id']   = $cuenta['n1'];
          $f['n1_desc'] = $mapaCuentas[trim($cuenta['n1'])] ?? '';
          $f['n2_id']   = $cuenta['n2'];
          $f['n2_desc'] = $mapaCuentas[trim($cuenta['n2'])] ?? '';
          $f['n3_id']   = $cuenta['n3'];
          $f['n3_desc'] = $mapaCuentas[trim($cuenta['n3'])] ?? '';
          $f['n4_id']   = $cuenta['n4'];
          $f['n4_desc'] = $mapaCuentas[trim($cuenta['n4'])] ?? '';
        }
        if ($centroCosto) {
          $f['f284_desc']            = $centroCosto['f284_descripcion'] ?? '';
          $f['f284_id_grupo_ccosto'] = $centroCosto['f284_id_grupo_ccosto'] ?? '';
        }
        if ($centroOperacion) {
          $f['f285_desc']        = $centroOperacion['f285_descripcion'] ?? '';
          $f['f285_id_regional'] = $centroOperacion['f285_id_regional'] ?? '';

          $regional = $centroOperacion['f285_id_regional'] ?? '';
          if (!isset($idsregionales[$regional])) {
            $idsregionales[$regional] = $regional;
          }
        }
        if ($unidadNegocio) {
          $f['f281_desc'] = $unidadNegocio;
        }
      }
      unset($f, $cuentas, $mapaCentrosCosto, $mapaCentrosOperacion, $mapaUnidadesNegocio, $mapaCuentas);

      $mapaRegionales = $ApiSiesa->buscarregionalPorIds(array_values($idsregionales));

      foreach ($filas as &$f) {
        $regional = $mapaRegionales[$f['f285_id_regional']] ?? null;

        if ($regional) {
          $f['regional_desc'] = $regional;
        }
      }
      unset($f, $mapaRegionales);


      // Borra lo ya registrado para esta empresa en este rango antes de insertar lo nuevo
      $ApiSiesa->borrarMovimientosPorRango(
        $idCia,
        date('Y-m-d', strtotime($fechaDesde)),
        date('Y-m-d', strtotime($fechaHasta))
      );

      $resultadoLotes = insertarMovimientosPorLotes($conexion, $filas, $anio);
      $insertados     = $resultadoLotes['insertados'];
      $errores        = $resultadoLotes['errores'];

      echo json_encode([
        "total"      => count($filas),
        "insertados" => $insertados,
        "omitidos"   => $errores,
        "mensaje"    => "Proceso finalizado: actualizacion de registros, {$insertados} registros insertados, {$errores} omitidos (duplicados o error).",
      ]);

      break;

    case 'Cuentas':

      $headers = implode("\r\n", [
        "ConniKey: 0d95972b116a18c6507102a7c2c1ffde",
        "ConniToken: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1laWRlbnRpZmllciI6IjI4NjlmZTQ4LTRmZjctNGY3Mi04ZTFhLTdmMTk5YjliYTEwNyIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcHJpbWFyeXNpZCI6IjhhMTY2ODI1LTkyYjgtNDk1Mi1iZWJiLTFmMzA1NTFhOGUxZSJ9.Wf27eJj8AudpQKONSSDnQzvFLXPRbmTkEc4WwadN54c"
      ]);

      $todosLosRegistros = [];
      $numPag = 1;
      $tamPag = 100;
      $maxPaginas = 100; // límite de seguridad para no entrar en bucle infinito

      while ($numPag <= $maxPaginas) {

        $url = "https://servicios.siesacloud.com/api/siesa/v3/ejecutarconsultaestandar"
          . '?' . http_build_query([
              'idCompania'  => '6434',
              'descripcion' => 'API_v2_Mayores',
              'paginacion'  => "numPag={$numPag}|tamPag={$tamPag}",
          ]);

        $opciones = stream_context_create([
          'http' => [
            'method' => 'GET',
            'header' => $headers
          ]
        ]);

        $respuesta = file_get_contents($url, false, $opciones);

        if ($respuesta === false) {
          http_response_code(500);
          echo json_encode(["error" => "No se pudo conectar con la API en la página {$numPag}"]);
          exit;
        }

        // La API devuelve este mensaje cuando ya no hay más registros
        if (strpos($respuesta, 'No se encontraron registros') !== false) {
          break;
        }

        $data = json_decode($respuesta, true);

        // La API puede devolver los registros bajo "Datos" (formato nuevo) o "Table" (formato anterior)
        $registrosPagina = $data['detalle']['Datos'] ?? $data['detalle']['Table'] ?? null;

        if (json_last_error() !== JSON_ERROR_NONE || $registrosPagina === null) {
          break;
        }

        if (empty($registrosPagina)) {
          break;
        }

        $todosLosRegistros = array_merge($todosLosRegistros, $registrosPagina);

        // Si devolvió menos de tamPag, ya es la última página
        if (count($registrosPagina) < $tamPag) {
          break;
        }

        $numPag++;
      }

      $insertados = 0;
      $errores    = 0;

      foreach ($todosLosRegistros as $cuenta) {
        $resultado = $ApiSiesa->insertarCuentas(
          $cuenta['f252_id']          ?? '',
          $cuenta['f252_descripcion'] ?? '',
          $cuenta['f252_id_plan']     ?? '',
          $cuenta['f252_nivel']       ?? ''
        );

        if ($resultado) {
          $insertados++;
        } else {
          $errores++;
        }
      }

      echo json_encode([
        "total"      => count($todosLosRegistros),
        "insertados" => $insertados,
        "omitidos"   => $errores,
        "mensaje"    => "Proceso finalizado: {$insertados} registros insertados, {$errores} omitidos (duplicados o error)."
      ]);

      break;


       case 'CentrosCosto':

      $headers = implode("\r\n", [
        "ConniKey: 0d95972b116a18c6507102a7c2c1ffde",
        "ConniToken: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1laWRlbnRpZmllciI6IjI4NjlmZTQ4LTRmZjctNGY3Mi04ZTFhLTdmMTk5YjliYTEwNyIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcHJpbWFyeXNpZCI6IjhhMTY2ODI1LTkyYjgtNDk1Mi1iZWJiLTFmMzA1NTFhOGUxZSJ9.Wf27eJj8AudpQKONSSDnQzvFLXPRbmTkEc4WwadN54c"
      ]);

      $todosLosRegistros = [];
      $numPag = 1;
      $tamPag = 100;
      $maxPaginas = 100; // límite de seguridad para no entrar en bucle infinito

      while ($numPag <= $maxPaginas) {

        $url = "https://servicios.siesacloud.com/api/siesa/v3/ejecutarconsultaestandar"
          . '?' . http_build_query([
              'idCompania'  => '6434',
              'descripcion' => 'API_v2_CentrosCosto',
              'paginacion'  => "numPag={$numPag}|tamPag={$tamPag}",
          ]);

        $opciones = stream_context_create([
          'http' => [
            'method' => 'GET',
            'header' => $headers
          ]
        ]);

        $respuesta = file_get_contents($url, false, $opciones);

        if ($respuesta === false) {
          http_response_code(500);
          echo json_encode(["error" => "No se pudo conectar con la API en la página {$numPag}"]);
          exit;
        }

        // La API devuelve este mensaje cuando ya no hay más registros
        if (strpos($respuesta, 'No se encontraron registros') !== false) {
          break;
        }

        $data = json_decode($respuesta, true);

        // La API puede devolver los registros bajo "Datos" (formato nuevo) o "Table" (formato anterior)
        $registrosPagina = $data['detalle']['Datos'] ?? $data['detalle']['Table'] ?? null;

        if (json_last_error() !== JSON_ERROR_NONE || $registrosPagina === null) {
          break;
        }

        if (empty($registrosPagina)) {
          break;
        }

        $todosLosRegistros = array_merge($todosLosRegistros, $registrosPagina);

        // Si devolvió menos de tamPag, ya es la última página
        if (count($registrosPagina) < $tamPag) {
          break;
        }

        $numPag++;
      }

      $insertados = 0;
      $errores    = 0;

      $centrosCostoInsertados = [];
      foreach ($todosLosRegistros as $cuenta) {
        if (in_array($cuenta['f284_id'], $centrosCostoInsertados)) {
          continue; // Omitir si ya se insertó este centro de costo
        }else{
          $centrosCostoInsertados[] = $cuenta['f284_id'];
        }
        $resultado = $ApiSiesa->centro_costo_siesa(
          $cuenta['f284_id']          ?? '',
          $cuenta['f284_descripcion'] ?? '',
          $cuenta['f284_id_grupo_ccosto']     ?? ''
        );

        if ($resultado) {
          $insertados++;
        } else {
          $errores++;
        }
      }

      echo json_encode([
        "total"      => count($todosLosRegistros),
        "insertados" => $insertados,
        "omitidos"   => $errores,
        "mensaje"    => "Proceso finalizado: {$insertados} registros insertados, {$errores} omitidos (duplicados o error)."
      ]);

      break;


      case 'CentrosOperacion':

      $headers = implode("\r\n", [
        "ConniKey: 0d95972b116a18c6507102a7c2c1ffde",
        "ConniToken: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1laWRlbnRpZmllciI6IjI4NjlmZTQ4LTRmZjctNGY3Mi04ZTFhLTdmMTk5YjliYTEwNyIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcHJpbWFyeXNpZCI6IjhhMTY2ODI1LTkyYjgtNDk1Mi1iZWJiLTFmMzA1NTFhOGUxZSJ9.Wf27eJj8AudpQKONSSDnQzvFLXPRbmTkEc4WwadN54c"
      ]);

      $todosLosRegistros = [];
      $numPag = 1;
      $tamPag = 100;
      $maxPaginas = 100; // límite de seguridad para no entrar en bucle infinito

      while ($numPag <= $maxPaginas) {

        $url = "https://servicios.siesacloud.com/api/siesa/v3/ejecutarconsultaestandar"
          . '?' . http_build_query([
              'idCompania'  => '6434',
              'descripcion' => 'API_v2_CentrosOperacion',
              'paginacion'  => "numPag={$numPag}|tamPag={$tamPag}",
          ]);

        $opciones = stream_context_create([
          'http' => [
            'method' => 'GET',
            'header' => $headers
          ]
        ]);

        $respuesta = file_get_contents($url, false, $opciones);

        if ($respuesta === false) {
          http_response_code(500);
          echo json_encode(["error" => "No se pudo conectar con la API en la página {$numPag}"]);
          exit;
        }

        // La API devuelve este mensaje cuando ya no hay más registros
        if (strpos($respuesta, 'No se encontraron registros') !== false) {
          break;
        }

        $data = json_decode($respuesta, true);

        // La API puede devolver los registros bajo "Datos" (formato nuevo) o "Table" (formato anterior)
        $registrosPagina = $data['detalle']['Datos'] ?? $data['detalle']['Table'] ?? null;

        if (json_last_error() !== JSON_ERROR_NONE || $registrosPagina === null) {
          break;
        }

        if (empty($registrosPagina)) {
          break;
        }

        $todosLosRegistros = array_merge($todosLosRegistros, $registrosPagina);

        // Si devolvió menos de tamPag, ya es la última página
        if (count($registrosPagina) < $tamPag) {
          break;
        }

        $numPag++;
      }

      $insertados = 0;
      $errores    = 0;

      $centrosCostoInsertados = [];
      foreach ($todosLosRegistros as $cuenta) {
        if (in_array($cuenta['f285_id'], $centrosCostoInsertados)) {
          continue; // Omitir si ya se insertó este centro de costo
        }else{
          $centrosCostoInsertados[] = $cuenta['f285_id'];
        }
        $resultado = $ApiSiesa->centro_operativo_siesa(
          $cuenta['f285_id']          ?? '',
          $cuenta['f285_descripcion'] ?? '',
          $cuenta['f285_id_regional']     ?? ''
        );

        if ($resultado) {
          $insertados++;
        } else {
          $errores++;
        }
      }

      echo json_encode([
        "total"      => count($todosLosRegistros),
        "insertados" => $insertados,
        "omitidos"   => $errores,
        "mensaje"    => "Proceso finalizado: {$insertados} registros insertados, {$errores} omitidos (duplicados o error)."
      ]);

      break;


      case 'unidadNegocio':

      $headers = implode("\r\n", [
        "ConniKey: 0d95972b116a18c6507102a7c2c1ffde",
        "ConniToken: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1laWRlbnRpZmllciI6IjI4NjlmZTQ4LTRmZjctNGY3Mi04ZTFhLTdmMTk5YjliYTEwNyIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcHJpbWFyeXNpZCI6IjhhMTY2ODI1LTkyYjgtNDk1Mi1iZWJiLTFmMzA1NTFhOGUxZSJ9.Wf27eJj8AudpQKONSSDnQzvFLXPRbmTkEc4WwadN54c"
      ]);

      $todosLosRegistros = [];
      $numPag = 1;
      $tamPag = 100;
      $maxPaginas = 100; // límite de seguridad para no entrar en bucle infinito

      while ($numPag <= $maxPaginas) {

        $url = "https://servicios.siesacloud.com/api/siesa/v3/ejecutarconsultaestandar"
          . '?' . http_build_query([
              'idCompania'  => '6434',
              'descripcion' => 'API_v2_UnidadesNegocio',
              'paginacion'  => "numPag={$numPag}|tamPag={$tamPag}",
          ]);

        $opciones = stream_context_create([
          'http' => [
            'method' => 'GET',
            'header' => $headers
          ]
        ]);

        $respuesta = file_get_contents($url, false, $opciones);

        if ($respuesta === false) {
          http_response_code(500);
          echo json_encode(["error" => "No se pudo conectar con la API en la página {$numPag}"]);
          exit;
        }

        // La API devuelve este mensaje cuando ya no hay más registros
        if (strpos($respuesta, 'No se encontraron registros') !== false) {
          break;
        }

        $data = json_decode($respuesta, true);

        // La API puede devolver los registros bajo "Datos" (formato nuevo) o "Table" (formato anterior)
        $registrosPagina = $data['detalle']['Datos'] ?? $data['detalle']['Table'] ?? null;

        if (json_last_error() !== JSON_ERROR_NONE || $registrosPagina === null) {
          break;
        }

        if (empty($registrosPagina)) {
          break;
        }

        $todosLosRegistros = array_merge($todosLosRegistros, $registrosPagina);

        // Si devolvió menos de tamPag, ya es la última página
        if (count($registrosPagina) < $tamPag) {
          break;
        }

        $numPag++;
      }

      $insertados = 0;
      $errores    = 0;

      $centrosCostoInsertados = [];
      foreach ($todosLosRegistros as $cuenta) {
        if (in_array($cuenta['f281_id'], $centrosCostoInsertados)) {
          continue; // Omitir si ya se insertó este centro de costo
        }else{
          $centrosCostoInsertados[] = $cuenta['f281_id'];
        }
        $resultado = $ApiSiesa->unidad_negocio_siesa(
          $cuenta['f281_id']          ?? '',
          $cuenta['f281_descripcion'] ?? ''
        );

        if ($resultado) {
          $insertados++;
        } else {
          $errores++; 
        }
      }

      echo json_encode([
        "total"      => count($todosLosRegistros),
        "insertados" => $insertados,
        "omitidos"   => $errores,
        "mensaje"    => "Proceso finalizado: {$insertados} registros insertados, {$errores} omitidos (duplicados o error)."
      ]);

      break;


    case 'buscarCuentas':
        $body = json_decode(file_get_contents('php://input'), true);
        $ids  = $body['ids'] ?? [];

        if (empty($ids)) {
            echo json_encode(["mapa" => []]);
            break;
        }

        $mapa = $ApiSiesa->buscarCuentasPorIds($ids);
        echo json_encode(["mapa" => $mapa]);
        break;

        case 'buscarCentrosCosto':
        $body = json_decode(file_get_contents('php://input'), true);
        $ids  = $body['ids'] ?? [];

        if (empty($ids)) {
            echo json_encode(["mapa" => []]);
            break;
        }

        $mapa = $ApiSiesa->buscarcentrosCostoPorIds($ids);
        echo json_encode(["mapa" => $mapa]);
        break;

         case 'buscarCentrosOperativos':
        $body = json_decode(file_get_contents('php://input'), true);
        $ids  = $body['ids'] ?? [];

        if (empty($ids)) {
            echo json_encode(["mapa" => []]);
            break;
        }

        $mapa = $ApiSiesa->buscarcentrosOperativosPorIds($ids);
        echo json_encode(["mapa" => $mapa]);
        break;

        case 'buscarUnidadesNegocio':
        $body = json_decode(file_get_contents('php://input'), true);
        $ids  = $body['ids'] ?? [];

        if (empty($ids)) {
            echo json_encode(["mapa" => []]);
            break;
        }

        $mapa = $ApiSiesa->buscarunidadesPorIds($ids);
        echo json_encode(["mapa" => $mapa]);
        break;


        case 'listar'://activado por el ajax en el scrip articulos
                    $rspta=$ApiSiesa->ListarMovimientos();//Carga la rspta con lista de articulos
                    //Vamos a declarar un array
                    $data= Array();

                    while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
                    {
                  
                                   
                        
                            $data[]=array(//arreglo con los datos de las columnas
                                    "0"=>$reg->anio,
                                    "1"=>$reg->periodo,
                                    "2"=>$reg->empresa,
                                    "3"=>$reg->nombre_empresa,
                                    "4"=>$reg->tipo_documento,
                                    "5"=>$reg->docto,
                                    "6"=>$reg->periodo_docto,
                                    "7"=>$reg->fecha_actualizacion_docto,
                                    "8"=>$reg->fecha_aprobacion_docto,
                                    "9"=>$reg->fecha_anulacion_docto,
                                    "10"=>$reg->usuario_creacion_docto,
                                    "11"=>$reg->usuario_aprobacion_docto,
                                    "12"=>$reg->usuario_anulacion_docto,
                                    "13"=>$reg->notas_docto,
                                    "14"=>$reg->Fecha_docto,
                                    "15"=>$reg->cuenta,
                                    "16"=>$reg->nombre_auxiliar,
                                    "17"=>$reg->cuenta_n1,
                                    "18"=>$reg->nombre_cuenta_n1,
                                    "19"=>$reg->cuenta_n2,
                                    "20"=>$reg->nombre_cuenta_n2,
                                    "21"=>$reg->cuenta_n3,
                                    "22"=>$reg->nombre_cuenta_n3,
                                    "23"=>$reg->cuenta_n4,
                                    "24"=>$reg->nombre_cuenta_n4,
                                    "25"=>$reg->co_movto,
                                    "26"=>$reg->co,
                                    "27"=>$reg->regional_co_movto,
                                    "28"=>$reg->regional,
                                    "29"=>$reg->desc_regional,
                                    "30"=>$reg->unidad_de_negocio,
                                    "31"=>$reg->nombre_unidad_de_negocio,
                                    "32"=>$reg->tercero,
                                    "33"=>$reg->nombre_tercero,
                                    "34"=>$reg->grupo_ccosto,
                                    "35"=>$reg->centro_de_costo,
                                    "36"=>$reg->nombre_centro_de_costo,
                                    "37"=>$reg->debitos,
                                    "38"=>$reg->creditos,
                                    "39"=>$reg->Deb_Libro_2,
                                    "40"=>$reg->cred_Libro_2,
                                    "41"=>$reg->Movto_libro2

                            );
                    }
                    $results = array(//variable con el resultado del arreglo
                            "sEcho"=>1, //Información para el datatables
                            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
                            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
                            "aaData"=>$data);
                    echo json_encode($results);//muestra el resultado

            break;
        
        case 'ultimafecha':
            $rspta=$ApiSiesa->movimiento_ultimafecha();
            echo json_encode($rspta);
            break;

        case 'cerrarperiodo':
          $anio=isset($_GET["anio"])?limpiarCadena($_GET["anio"]):"";
          $mes=isset($_GET["mes"])?limpiarCadena($_GET["mes"]):"";

          $where_condition = array('anio_cierre'=>$anio,' and mes_cierre'=>$mes);
		      $rspta = $Movimiento->validar('cierre_moviminetos_contables', $where_condition);  
		      if($rspta->num_rows<=0){
          $data_values = array('mes_cierre'=>$mes,"anio_cierre"=>$anio,"estado_cierre"=>1);
          $rspta = $Movimiento->insertar('cierre_moviminetos_contables', $data_values);
          echo $rspta ? json_encode([
           "mensaje"    => "registro realizado con exito",
      ]) : json_encode([
           "mensaje"    => "error al realizar el registro",
      ]);	 
          }else{
            echo json_encode([
           "mensaje"    => "Ya existe un registro para el periodo seleccionado",
      ]);
          }    
            break;

      case 'listarcierreperiodo':
      try {
		  
		  $where_condition = array('1'=>'1'); 
      $rspta = $Movimiento->listar('cierre_moviminetos_contables', $where_condition);
      $data = Array();
      $meses = array(
        1 => "Enero",
        2 => "Febrero",
        3 => "Marzo",
        4 => "Abril",
        5 => "Mayo",
        6 => "Junio",
        7 => "Julio",
        8 => "Agosto",
        9 => "Septiembre",
        10 => "Octubre",
        11 => "Noviembre",
        12 => "Diciembre"
      );
      while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
      {              
            
      $estado="Cerrado";
			if($reg->estado_cierre !=1){ 
      $estado="Reabierto";
      }  
      $data[]=array(
              "0"=>$reg->anio_cierre,
              "1"=>$meses[$reg->mes_cierre],
              "2"=>$estado
            );
            
          }

		  $result = array(
              "sEcho"=>1,
              "iTotalRecords"=>count($data),
              "iTotalDisplayRecords"=>count($data),
              "aaData"=>$data
            );
            echo json_encode($result);
        
      } catch (\Throwable $th) {
        echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
      }
      break;


    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;

        
}

