<?php
$esLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']);

if ($esLocal) {
    // Entorno local de desarrollo (MariaDB en Docker, cargado desde bdb.sql)
    define('DB_HOST','127.0.0.1');
    define('DB_NAME','bd_powerbi_dev');
    define('DB_USERNAME','root');
    define('DB_PASSWORD','devroot');
    define('DB_ENCODE','utf8');
    define('PRO_NOMBRE','bd_powerbi_dev');
} 
