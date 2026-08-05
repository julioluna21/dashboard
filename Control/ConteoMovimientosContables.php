<?php
// Reporte de diagnóstico: por cada empresa y cada mes del rango indicado, consulta a la
// API de Siesa cuántos registros HAY (campo "total_registros" que trae el encabezado de
// la respuesta), sin recorrer las páginas ni tocar la base de datos. Sirve para comparar
// contra lo que realmente quedó insertado en movimientos_contables.
//
// Uso: php Control/ConteoMovimientosContables.php [anio] [mesDesde] [mesHasta]
// Por defecto: año actual, enero (1) a junio (6).
// El resultado se imprime en pantalla y se guarda en logs/reporte_conteo_movimientos_<anio>.json

if (php_sapi_name() !== 'cli') {
  http_response_code(403);
  echo json_encode(["error" => "Este script solo puede ejecutarse por línea de comandos"]);
  exit;
}

set_time_limit(1200);
date_default_timezone_set("America/Lima");

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

$meses = [
  1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril', 5 => 'mayo', 6 => 'junio',
  7 => 'julio', 8 => 'agosto', 9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
];

$headers = implode("\r\n", [
  "ConniKey: 0d95972b116a18c6507102a7c2c1ffde",
  "ConniToken: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1laWRlbnRpZmllciI6IjI4NjlmZTQ4LTRmZjctNGY3Mi04ZTFhLTdmMTk5YjliYTEwNyIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcHJpbWFyeXNpZCI6IjhhMTY2ODI1LTkyYjgtNDk1Mi1iZWJiLTFmMzA1NTFhOGUxZSJ9.Wf27eJj8AudpQKONSSDnQzvFLXPRbmTkEc4WwadN54c"
]);

$anio     = isset($argv[1]) ? (int) $argv[1] : (int) date('Y');
$mesDesde = isset($argv[2]) ? (int) $argv[2] : 1;
$mesHasta = isset($argv[3]) ? (int) $argv[3] : 6;

// Consulta 1 sola página (tamPag=1) solo para leer "total_registros" del encabezado de
// la respuesta; no recorre el resto de páginas porque no hace falta para contar.
function contarRegistros($fechaDesde, $fechaHasta, $idCia, $headers) {
  $fechaInicialApi = date('Ymd', strtotime($fechaDesde));
  $fechaFinalApi   = date('Ymd', strtotime($fechaHasta));

  $url = "https://servicios.siesacloud.com/api/connekta/v3/ejecutarconsulta"
    . '?' . http_build_query([
        'idCompania'  => '6434',
        'descripcion' => 'regencycolombia_MovimientoContable',
        'paginacion'  => "numPag=1|tamPag=1",
        'parametros'  => "f350_id_cia={$idCia}|f350_fecha_inicial = {$fechaInicialApi}|f350_fecha_final = {$fechaFinalApi}",
    ]);

  $opciones = stream_context_create([
    'http' => [
      'method'  => 'GET',
      'header'  => $headers,
      'timeout' => 30,
    ]
  ]);

  $respuesta = @file_get_contents($url, false, $opciones);

  if ($respuesta === false) {
    return ['total_registros' => null, 'error' => 'No se pudo conectar con la API'];
  }

  // La API devuelve este mensaje cuando no hay registros para el rango pedido
  if (strpos($respuesta, 'No se encontraron registros') !== false) {
    return ['total_registros' => 0];
  }

  $data = json_decode($respuesta, true);

  if (json_last_error() !== JSON_ERROR_NONE || !isset($data['detalle']['total_registros'])) {
    return ['total_registros' => null, 'error' => 'Respuesta inesperada de la API'];
  }

  return ['total_registros' => (int) $data['detalle']['total_registros']];
}

$reporte = [];

foreach ($empresas as $idCia => $nombreEmpresa) {
  $reporte[$idCia] = [
    'nombre_empresa' => $nombreEmpresa,
    'meses'          => [],
  ];

  for ($mes = $mesDesde; $mes <= $mesHasta; $mes++) {
    $fechaDesde = sprintf('%04d/%02d/01', $anio, $mes);
    $fechaHasta = date('Y/m/t', strtotime($fechaDesde));

    $resultado = contarRegistros($fechaDesde, $fechaHasta, $idCia, $headers);

    $reporte[$idCia]['meses'][$meses[$mes]] = array_merge(
      ['fecha_desde' => $fechaDesde, 'fecha_hasta' => $fechaHasta],
      $resultado
    );

    fwrite(STDERR, "Empresa {$idCia} - {$meses[$mes]} {$anio}: " . json_encode($resultado) . PHP_EOL);

    // La API de Siesa limita ~10 solicitudes por minuto (headers Connekta-Rate-Limit-*).
    // Se espera entre llamadas para no recibir respuestas de error por exceso de solicitudes.
    sleep(7);
  }
}

$json = json_encode($reporte, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

$carpetaLogs = __DIR__ . "/../logs";
if (!is_dir($carpetaLogs)) {
  mkdir($carpetaLogs, 0755, true);
}

$rutaArchivo = $carpetaLogs . "/reporte_conteo_movimientos_{$anio}.json";
file_put_contents($rutaArchivo, $json);

echo $json . PHP_EOL;
fwrite(STDERR, "Guardado en: {$rutaArchivo}" . PHP_EOL);
