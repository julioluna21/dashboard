<?php
session_start();
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
// Inserta $filas en movimientos_contables en lotes de $tamLote filas por sentencia, en vez
// de un INSERT por fila. Con rangos grandes (30 mil+ filas) hacer un INSERT a la vez era lo
// más lento del proceso completo; agrupar los inserts baja ese tiempo de minutos a segundos.
function insertarMovimientosPorLotes($conexion, $filas, $tamLote = 500) {
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
                limpiarCadena($fila['anio'] ?? ''),
                limpiarCadena($fila['periodo'] ?? ''),
                limpiarCadena($fila['empresa'] ?? ''),
                limpiarCadena($fila['nombre_empresa'] ?? ''),
                limpiarCadena($fila['tipo_documento'] ?? ''),
                limpiarCadena($fila['docto'] ?? ''),
                limpiarCadena($fila['periodo_docto'] ?? ''),
                limpiarCadena($fila['fecha_actualizacion_docto'] ?? ''),
                limpiarCadena($fila['fecha_aprobacion_docto'] ?? ''),
                limpiarCadena($fila['fecha_anulacion_docto'] ?? ''),
                limpiarCadena($fila['usuario_creacion_docto'] ?? ''),
                limpiarCadena($fila['usuario_aprobacion_docto'] ?? ''),
                limpiarCadena($fila['usuario_anulacion_docto'] ?? ''),
                limpiarCadena($fila['notas_docto'] ?? ''),
                limpiarCadena($fila['Fecha_docto'] ?? ''),
                limpiarCadena($fila['cuenta'] ?? ''),
                limpiarCadena($fila['nombre_auxiliar'] ?? ''),
                limpiarCadena($fila['cuenta_n1'] ?? ''),
                limpiarCadena($fila['nombre_cuenta_n1'] ?? ''),
                limpiarCadena($fila['cuenta_n2'] ?? ''),
                limpiarCadena($fila['nombre_cuenta_n2'] ?? ''),
                limpiarCadena($fila['cuenta_n3'] ?? ''),
                limpiarCadena($fila['nombre_cuenta_n3'] ?? ''),
                limpiarCadena($fila['cuenta_n4'] ?? ''),
                limpiarCadena($fila['nombre_cuenta_n4'] ?? ''),
                limpiarCadena($fila['co_movto'] ?? ''),
                limpiarCadena($fila['co'] ?? ''),
                limpiarCadena($fila['regional_co_movto'] ?? ''),
                limpiarCadena($fila['regional'] ?? ''),
                limpiarCadena($fila['unidad_de_negocio'] ?? ''),
                limpiarCadena($fila['nombre_unidad_de_negocio'] ?? ''),
                limpiarCadena($fila['tercero'] ?? ''),
                limpiarCadena($fila['nombre_tercero'] ?? ''),
                limpiarCadena($fila['grupo_ccosto'] ?? ''),
                limpiarCadena($fila['centro_de_costo'] ?? ''),
                limpiarCadena($fila['nombre_centro_de_costo'] ?? ''),
                limpiarCadena($fila['debitos'] ?? ''),
                limpiarCadena($fila['creditos'] ?? ''),
                limpiarCadena($fila['Deb_Libro_2'] ?? ''),
                limpiarCadena($fila['cred_Libro_2'] ?? ''),
                limpiarCadena($fila['Movto_libro2'] ?? ''),
                limpiarCadena($fila['desc_regional'] ?? ''),
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

switch ($_REQUEST['tipo'] ?? '') {


    case 'registrarMovimientoEmpresa':
      // Proceso independiente de generarFilasContables: una sola empresa (la que
      // se elige en el modal) y un rango de fechas puntual (máx. 15 días). Borra
      // lo que ya estuviera registrado para esa empresa en ese rango y registra
      // la información nueva. La confirmación del usuario ya se pidió en el JS.
      $registros = isset($_REQUEST["registros"]) ? json_decode($_REQUEST['registros'], true) : "";

      if (!is_array($registros)) {
        http_response_code(400);
        echo json_encode(["error" => "No se recibieron registros válidos"]);
        exit;
      }

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

      $idCia = (int) ($_REQUEST['idCia'] ?? 0);

      if (!isset($empresas[$idCia])) {
        http_response_code(400);
        echo json_encode(["error" => "Empresa inválida"]);
        exit;
      }

      $fechaDesde = $_REQUEST['desde'] ?? '';
      $fechaHasta = $_REQUEST['hasta'] ?? '';

      if (!$fechaDesde || !$fechaHasta) {
        http_response_code(400);
        echo json_encode(["error" => "Debe indicar fecha desde y fecha hasta"]);
        exit;
      }

      /*$diasRango = (strtotime($fechaHasta) - strtotime($fechaDesde)) / 86400;

      if ($diasRango < 0 || $diasRango > 30) {
        http_response_code(400);
        echo json_encode(["error" => "El rango entre fecha inicio y fecha fin no puede ser mayor a 15 días"]);
        exit;
      }*/

      $filas               = [];

      foreach ($registros as $d) {
        $filas[] = [
          'anio'             => $d['anio'] ?? '',
          'periodo'                 => $d['periodo'] ?? '',
          'empresa'          => $d['empresa'] ?? '',
          'nombre_empresa'           => $d['nombre_empresa'] ?? '',
          'tipo_documento'            => $d['tipo_documento'] ?? '',
          'docto' => $d['docto'] ?? '',
          'periodo_docto'    => $d['periodo_docto'] ?? '',
          'fecha_actualizacion_docto'     => $d['fecha_actualizacion_docto'] ?? '',
          'fecha_aprobacion_docto'       => $d['fecha_aprobacion_docto'] ?? '',
          'fecha_anulacion_docto'  => $d['fecha_anulacion_docto'] ?? '',
          'usuario_creacion_docto'       => $d['usuario_creacion_docto'] ?? '',
          'usuario_aprobacion_docto'     => $d['usuario_aprobacion_docto'] ?? '',
          'usuario_anulacion_docto'      => $d['usuario_anulacion_docto'] ?? '',
          'notas_docto'                  => $d['notas_docto'] ?? '',
          'Fecha_docto'                  => $d['Fecha_docto'] ?? '',
          'cuenta'                     => $d['cuenta'] ?? '',
          'nombre_auxiliar'            => $d['nombre_auxiliar'] ?? '',
          'cuenta_n1'              => $d['cuenta_n1'] ?? '',
          'nombre_cuenta_n1'                  => $d['nombre_cuenta_n1'] ?? '',
          'cuenta_n2'                  => $d['cuenta_n2'] ?? '',
          'nombre_cuenta_n2'                  => $d['nombre_cuenta_n2'] ?? '',
          'cuenta_n3'                    => $d['cuenta_n3'] ?? '',
          'nombre_cuenta_n3'           => $d['nombre_cuenta_n3'] ?? '',
          'cuenta_n4'                    => $d['cuenta_n4'] ?? '',
          'nombre_cuenta_n4'           => $d['nombre_cuenta_n4'] ?? '',
          'co_movto'                  => $d['co_movto'] ?? '',
          'co'               => $d['co'] ?? '',
          'regional_co_movto'               => $d['regional_co_movto'] ?? '',
          'regional'              => $d['regional'] ?? '',
          'unidad_de_negocio'              => $d['unidad_de_negocio'] ?? '',
          'nombre_unidad_de_negocio'                 => $d['nombre_unidad_de_negocio'] ?? '',
          'tercero'              => $d['tercero'] ?? '',
          'nombre_tercero'              => $d['nombre_tercero'] ?? '',
          'grupo_ccosto'              => $d['grupo_ccosto'] ?? '',
          'centro_de_costo'              => $d['centro_de_costo'] ?? '',
          'nombre_centro_de_costo'              => $d['nombre_centro_de_costo'] ?? '',
          'debitos'              => $d['debitos'] ?? '',  
          'creditos'              => $d['creditos'] ?? '',
          'Deb_Libro_2'              => $d['Deb_Libro_2'] ?? '',
          'cred_Libro_2'              => $d['cred_Libro_2'] ?? '',
          'Movto_libro2'              => $d['Movto_libro2'] ?? '',
          'desc_regional'              => $d['desc_regional'] ?? '',
        ];
      }

      // Borra lo ya registrado para esta empresa en este rango antes de insertar lo nuevo
      $ApiSiesa->borrarMovimientosPorRango(
        $idCia,
        date('Y-m-d', strtotime($fechaDesde)),
        date('Y-m-d', strtotime($fechaHasta))
      );

      $resultadoLotes = insertarMovimientosPorLotes($conexion, $filas);
      $insertados     = $resultadoLotes['insertados'];
      $errores        = $resultadoLotes['errores'];

      echo json_encode([
        "total"      => count($filas),
        "insertados" => $insertados,
        "omitidos"   => $errores,
        "mensaje"    => "Proceso finalizado: actualizacion de registros, {$insertados} registros insertados, {$errores} omitidos (duplicados o error).",
      ]);

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

    

        
}

