-- Tabla para el módulo de carga masiva de gasolina (CSV) + vista editable.
-- id: llave primaria autoincremental, necesaria para poder editar filas individuales
--     desde la vista editable (UPDATE ... WHERE id = ?).
-- fecha_carga: se llena en el servidor al momento de insertar (no viene del CSV),
--     sirve de auditoría para saber cuándo se importó cada fila.
-- idx_fecha: la importación borra-y-recarga filtrando por rango de `fecha`,
--     este índice evita un full table scan en esa operación a medida que crezca.

CREATE TABLE gasolina_raw (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    cliente             VARCHAR(300),
    proveedor           VARCHAR(300),
    nro_identificacion  VARCHAR(300),
    codigo_sap          VARCHAR(300),
    no_venta            VARCHAR(300),
    fecha               DATETIME,
    estacion            VARCHAR(300),
    regional            VARCHAR(300),
    id_eds              VARCHAR(300),
    placa               VARCHAR(300),
    conductor           VARCHAR(300),
    combustible         VARCHAR(300),
    cantidad            DOUBLE(10,3),
    precio              DOUBLE(10,3),
    unidad_venta        VARCHAR(300),
    total_venta         DOUBLE(10,3),
    kilometraje         DOUBLE(10,3),
    fecha_carga         DATETIME,
    INDEX idx_fecha (fecha)
);
