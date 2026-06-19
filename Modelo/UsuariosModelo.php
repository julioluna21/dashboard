<?php
//Incluímos inicialmente la conexión a la base de datos
require "../Conexion/ConexionDB.php";
class Usuarios
{
        //Implementamos el super constructor 
        public function __construct()
        {
        }
        //Implementamos un método para insertar registros
	
	
	    public function insertar($idcolaborador, $clave)
        {
                $sql = "INSERT INTO usuarios (`IDCOLABORADORUSUARIO`, `CLAVEING`, `ESTADOUSUARIO`)
                            VALUES ('$idcolaborador','$nombre','$clave',1)";
                return ejecutarConsulta($sql); //envia la sentencia a la funcion ejecutarConsulta que está en conexion.php
        }
        //Implementamos un método para editar registros
        public function editar($id, $clave)
        {
                $sql = "UPDATE usuarios SET CLAVEING ='$clave' where IDCOLABORADORUSUARIO=$id";
                return ejecutarConsulta($sql);
        }
        //Implementar un método para mostrar los datos de un registro a modificar
        public function mostrar($id)
        {
                $sql = "SELECT * FROM colaborador WHERE ID_USUARIO='$id'";
                return ejecutarConsultaSimpleFila($sql);
        }
        //Implementar un método para listar los registros
        public function listar($estado)
        {
                $sql = "SELECT colaborador.* FROM colaborador where ESTADOCOLABORADOR=$estado";
                return ejecutarConsulta($sql);
        }
        public function anular($id)
        {
                $sql = "UPDATE colaborador SET ESTADOCOLABORADOR=0 WHERE IDCOLABORADOR=$id";
                return ejecutarConsulta($sql);
        }
	
	        public function activar($id)
        {
                $sql = "UPDATE colaborador SET ESTADOCOLABORADOR=1 WHERE IDCOLABORADOR=$id";
                return ejecutarConsulta($sql);
        }
        public function select()
        {
                $sql = "SELECT * FROM colaborador WHERE ESTADOCOLABORADOR=1";
                return ejecutarConsulta($sql);
        }
	
	     public function validar($cedula)
        {
                $sql = "SELECT * FROM colaborador WHERE CEDULA_COLABORADOR='$cedula'";
                return ejecutarConsulta($sql);
        }
}