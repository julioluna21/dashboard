<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";

$ejecucion = new configuracion();
$Consulta  = new consultas();

// Inserta $filas en peajes en lotes de $tamLote filas por sentencia, en vez de un
// INSERT por fila. Mismo patrón que insertarGasolinaPorLotes() en GasolinaRawControl.php.
function insertarPeajesPorLotes($conexion, $filas, $tamLote = 500)
{
    $columnas = "fecha_recepcion, fecha_emision, tipo_transaccion, codigo_transaccion,
        placa, categoria, peaje, carril, sentido, valor_inicial, valor_cobrado,
        valor_final, receptor_facturacion, cufe_dian, fecha_carga";

    $insertados = 0;
    $errores    = 0;

    

    foreach (array_chunk($filas, $tamLote) as $lote) {
        $filasSql = [];

    
        foreach ($lote as $fila) {

            $fechaRecepcion = $fila['fecha_recepcion'] ?? '';

            // Una fecha vacía no es un valor válido para una columna DATETIME en modo
            // estricto de MySQL. Si no hay fecha de recepción, lo más seguro es que
            // sea una fila de pie de página que no cayó en el filtro del JS (placa +
            // código de transacción vacíos) -- se omite y se cuenta como error, en
            // vez de insertar un NULL silencioso que "invente" que la fila no tenía fecha.
            if ($fechaRecepcion === '') {
                $errores++;
                continue;
            }
            $valores = [
                limpiarCadena($fila['fecha_recepcion'] ?? ''),
                limpiarCadena($fila['fecha_emision'] ?? ''),
                limpiarCadena($fila['tipo_transaccion'] ?? ''),
                limpiarCadena($fila['codigo_transaccion'] ?? ''),
                limpiarCadena($fila['placa'] ?? ''),
                limpiarCadena($fila['categoria'] ?? ''),
                limpiarCadena($fila['peaje'] ?? ''),
                limpiarCadena($fila['carril'] ?? ''),
                limpiarCadena($fila['sentido'] ?? ''),
                limpiarCadena($fila['valor_inicial'] ?? ''),
                limpiarCadena($fila['valor_cobrado'] ?? ''),
                limpiarCadena($fila['valor_final'] ?? ''),
                limpiarCadena($fila['receptor_facturacion'] ?? ''),
                limpiarCadena($fila['cufe_dian'] ?? ''),
                date('Y-m-d H:i:s'),
            ];
            $filasSql[] = "('" . implode("','", $valores) . "')";
        }

        $sql = "INSERT INTO peajes_raw ($columnas) VALUES " . implode(',', $filasSql);

        try {
            $resultado = mysqli_query($conexion, $sql);
            if ($resultado) {
                $insertados += count($lote);
            } else {
                $errores += count($lote);
                error_log("insertarPeajesPorLotes: falló lote de " . count($lote) . " filas: " . mysqli_error($conexion));
            }
        } catch (mysqli_sql_exception $e) {
            $errores += count($lote);
            error_log("insertarPeajesPorLotes: excepción en lote de " . count($lote) . " filas: " . $e->getMessage());
        }
    }

    return ['insertados' => $insertados, 'errores' => $errores];
}

switch ($_REQUEST['op'] ?? '') {

    case 'listar':
        $condition = array ("1"=>"1");
        $rspta = $ejecucion->listar("peajes_raw", $condition);

        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $data[] = [
                "0"  => $reg->fecha_recepcion,
                "1"  => $reg->fecha_emision,
                "2"  => $reg->tipo_transaccion,
                "3"  => $reg->codigo_transaccion,
                "4"  => $reg->placa,
                "5"  => $reg->categoria,
                "6"  => $reg->peaje,
                "7"  => $reg->carril,
                "8"  => $reg->sentido,
                "9"  => $reg->valor_inicial,
                "10" => $reg->valor_cobrado,
                "11" => $reg->valor_final,
                "12" => $reg->receptor_facturacion,
                "13" => $reg->cufe_dian,
                "14" => $reg->fecha_carga,
            ];
        }

        echo json_encode([
            "sEcho"                => 1,
            "iTotalRecords"        => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData"               => $data,
        ]);

        break;

    case 'cargarCSV':
    $desde     = isset($_POST['desde']) ? limpiarCadena($_POST['desde']) : '';
    $hasta     = isset($_POST['hasta']) ? limpiarCadena($_POST['hasta']) : '';
    $registros = isset($_POST['registros']) ? json_decode($_POST['registros'], true) : null;

    if (!$desde || !$hasta) {
        http_response_code(400);
        echo json_encode(["error" => "Debe indicar fecha desde y fecha hasta"]);
        exit;
    }

    if (!is_array($registros)) {
        http_response_code(400);
        echo json_encode(["error" => "No se recibieron registros válidos"]);
        exit;
    }

    // Con la carga por lotes desde el cliente, cada request es un pedazo del
    // archivo -- solo el primero debe borrar el rango existente. Si cada lote
    // borrara, se perdería lo insertado por los lotes anteriores del mismo archivo.
    $borrarRango = filter_var($_POST['borrar'] ?? '1', FILTER_VALIDATE_BOOLEAN);

    if ($borrarRango) {
        $Consulta->borrarPeajesPorRango($desde, $hasta);
    }

    $resultado  = insertarPeajesPorLotes($conexion, $registros);
    $insertados = $resultado['insertados'];
    $errores    = $resultado['errores'];

    echo json_encode([
        "total"      => count($registros),
        "insertados" => $insertados,
        "omitidos"   => $errores,
        "mensaje"    => "Proceso finalizado: {$insertados} registros insertados, {$errores} omitidos (error).",
    ]);

    break;

    case 'mostrar':

        break;

    case 'editar':

        break;

}