<?php
require "../Conexion/ConexionDB.php";
class inventarioSoftware
{
        public function __construct()
        {
        }

        public function insertar($nombreapp, $alcance, $fecha, $area, $lider, $tipo, $link)
        {
                $sql = "INSERT INTO inventario_software (`NOMBREAPP`, `ALCANCE`, `FECHA_ULTIMA_VERSION`, `AREA_RESPONSABLE`, `LIDER_FUNCIONAL`, `TIPO_DESARROLLO`, `LINK_CARPETA`, `ESTADO`)
                            VALUES ('$nombreapp','$alcance','$fecha','$area','$lider','$tipo','$link',1)";
                return ejecutarConsulta($sql);
        }

        public function editar($id, $nombreapp, $alcance, $fecha, $area, $lider, $tipo, $link)
        {
                $sql = "UPDATE inventario_software SET NOMBREAPP='$nombreapp', ALCANCE='$alcance', FECHA_ULTIMA_VERSION='$fecha', AREA_RESPONSABLE='$area', LIDER_FUNCIONAL='$lider', TIPO_DESARROLLO='$tipo', LINK_CARPETA='$link' where
                    ID_INVENTARIO_SOFTWARE=$id";
                return ejecutarConsulta($sql);
        }

        public function mostrar($id)
        {
                $sql = "SELECT * FROM inventario_software WHERE ID_INVENTARIO_SOFTWARE='$id'";
                return ejecutarConsultaSimpleFila($sql);
        }

        public function listar($estado)
        {
                $sql = "SELECT * FROM inventario_software where ESTADO=$estado";
                return ejecutarConsulta($sql);
        }

        public function anular($id)
        {
                $sql = "UPDATE inventario_software SET ESTADO=0 WHERE ID_INVENTARIO_SOFTWARE=$id";
                return ejecutarConsulta($sql);
        }

        public function activar($id)
        {
                $sql = "UPDATE inventario_software SET ESTADO=1 WHERE ID_INVENTARIO_SOFTWARE=$id";
                return ejecutarConsulta($sql);
        }
}