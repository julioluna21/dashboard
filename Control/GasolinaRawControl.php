<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";

$ejecucion = new configuracion();
$Consulta  = new consultas();

// Inserta $filas en gasolina_raw en lotes de $tamLote filas por sentencia, en vez de un
// INSERT por fila -- con cargas de miles de filas, un insert a la vez es lo más lento
// del proceso. Mismo patrón que insertarMovimientosPorLotes() en MovimiesntosContablesControlTem.php.
function insertarGasolinaPorLotes($conexion, $filas, $tamLote = 500)
{
    $columnas = "cliente, proveedor, nro_identificacion, codigo_sap, no_venta, fecha,
        estacion, regional, id_eds, placa, conductor, combustible, cantidad, precio,
        unidad_venta, total_venta, kilometraje, fecha_carga";

    $insertados = 0;
    $errores    = 0;

    foreach (array_chunk($filas, $tamLote) as $lote) {
        $filasSql = [];

        foreach ($lote as $fila) {
            $valores = [
                limpiarCadena($fila['cliente'] ?? ''),
                limpiarCadena($fila['proveedor'] ?? ''),
                limpiarCadena($fila['nro_identificacion'] ?? ''),
                limpiarCadena($fila['codigo_sap'] ?? ''),
                limpiarCadena($fila['no_venta'] ?? ''),
                limpiarCadena($fila['fecha'] ?? ''),
                limpiarCadena($fila['estacion'] ?? ''),
                limpiarCadena($fila['regional'] ?? ''),
                limpiarCadena($fila['id_eds'] ?? ''),
                limpiarCadena($fila['placa'] ?? ''),
                limpiarCadena($fila['conductor'] ?? ''),
                limpiarCadena($fila['combustible'] ?? ''),
                limpiarCadena($fila['cantidad'] ?? ''),
                limpiarCadena($fila['precio'] ?? ''),
                limpiarCadena($fila['unidad_venta'] ?? ''),
                limpiarCadena($fila['total_venta'] ?? ''),
                limpiarCadena($fila['kilometraje'] ?? ''),
                date('Y-m-d H:i:s'),
            ];
            $filasSql[] = "('" . implode("','", $valores) . "')";
        }

        $sql = "INSERT INTO gasolina_raw ($columnas) VALUES " . implode(',', $filasSql);
        $resultado = mysqli_query($conexion, $sql);

        if ($resultado) {
            $insertados += count($lote);
        } else {
            $errores += count($lote);
            error_log("insertarGasolinaPorLotes: falló lote de " . count($lote) . " filas: " . mysqli_error($conexion));
        }
    }

    return ['insertados' => $insertados, 'errores' => $errores];
}

switch ($_REQUEST['op'] ?? '') {

    case 'listar':
        $condition = array ("1"=>"1");
        $rspta = $ejecucion->listar("gasolina_raw",$condition);

        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $data[] = [
                "0"  => $reg->cliente,
                "1"  => $reg->proveedor,
                "2"  => $reg->nro_identificacion,
                "3"  => $reg->codigo_sap,
                "4"  => $reg->no_venta,
                "5"  => $reg->fecha,
                "6"  => $reg->estacion,
                "7"  => $reg->regional,
                "8"  => $reg->id_eds,
                "9"  => $reg->placa,
                "10" => $reg->conductor,
                "11" => $reg->combustible,
                "12" => $reg->cantidad,
                "13" => $reg->precio,
                "14" => $reg->unidad_venta,
                "15" => $reg->total_venta,
                "16" => $reg->kilometraje,
                "17" => $reg->fecha_carga,
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

        // Borra lo ya cargado en ese rango antes de insertar lo nuevo
        $Consulta->borrarGasolinaRawPorRango($desde, $hasta);

        $resultado  = insertarGasolinaPorLotes($conexion, $registros);
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
