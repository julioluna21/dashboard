<?php
//Incluímos inicialmente la conexión a la base de datos
require "../Conexion/ConexionDB.php";
class colaborador
{
        //Implementamos el super constructor 
        public function __construct()
        {
        }
        //Implementamos un método para insertar registros
        public function insertar($cedula, $nombre, $correo)
        {
                $sql = "INSERT INTO colaborador (`CEDULA_COLABORADOR`, `NOMBRE_COLABORADOR`, `CORREO_COLABORADOR`, `ESTADOCOLABORADOR`)
                            VALUES ('$cedula','$nombre','$correo',1)";
                return ejecutarConsulta_retornarID($sql); //envia la sentencia a la funcion ejecutarConsulta que está en conexion.php
        }
	
	
	    public function insertarUsuario($idcolaborador, $nombre, $clave,$perfil)
        {
                $sql = "INSERT INTO usuarios (`IDCOLABORADORUSUARIO`, `USUARIOING`, `CLAVEING`,PERFIL, `ESTADOUSUARIO`)
                            VALUES ('$idcolaborador','$nombre','$clave','$perfil',1)";
                return ejecutarConsulta($sql); //envia la sentencia a la funcion ejecutarConsulta que está en conexion.php
        }
        //Implementamos un método para editar registros
        public function editar($id, $cedula, $nombre, $correo)
        {
                $sql = "UPDATE colaborador SET CEDULA_COLABORADOR = '$cedula', NOMBRE_COLABORADOR='$nombre', CORREO_COLABORADOR='$correo' where 
                    IDCOLABORADOR=$id";
                return ejecutarConsulta($sql);
        }
        //Implementar un método para mostrar los datos de un registro a modificar
        public function mostrar($id)
        {
                $sql = "SELECT colaborador.*, usuarios.PERFIL FROM colaborador INNER JOIN usuarios on usuarios.IDCOLABORADORUSUARIO=colaborador.IDCOLABORADOR WHERE IDCOLABORADOR='$id'";
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
	
	    public function anularUsuario($id)
        {
                $sql = "UPDATE usuarios SET ESTADOUSUARIO=0 WHERE IDCOLABORADORUSUARIO=$id";
                return ejecutarConsulta($sql);
        }
	
	        public function activar($id)
        {
                $sql = "UPDATE colaborador SET ESTADOCOLABORADOR=1 WHERE IDCOLABORADOR=$id";
                return ejecutarConsulta($sql);
        }
	
	    public function activarUsuario($id)
        {
                $sql = "UPDATE usuarios SET ESTADOUSUARIO=1 WHERE IDCOLABORADORUSUARIO=$id";
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
	
	    public function editarClave($id, $clave)
        {
            $sql = "UPDATE usuarios SET CLAVEING='$clave' where IDCOLABORADORUSUARIO=$id";
            return ejecutarConsulta($sql);
        }
	
	 public function editarperfil($id, $perfil)
        {
            $sql = "UPDATE usuarios SET PERFIL='$perfil' where IDCOLABORADORUSUARIO=$id";
            return ejecutarConsulta($sql);
        }
	
	   
	    public function verificar($usuario, $clave)
        {
            $sql = "SELECT usuarios.*,colaborador.NOMBRE_COLABORADOR FROM usuarios INNER JOIN colaborador on colaborador.IDCOLABORADOR=usuarios.IDCOLABORADORUSUARIO WHERE usuarios.USUARIOING='$usuario' AND usuarios.CLAVEING='$clave' and usuarios.ESTADOUSUARIO=1";
            return ejecutarConsulta($sql);
        }
	
	     public function CorreoColaborador($usuario)
        {
                $sql = "SELECT colaborador.CORREO_COLABORADOR, colaborador.NOMBRE_COLABORADOR, colaborador.IDCOLABORADOR FROM colaborador INNER JOIN usuarios ON usuarios.IDCOLABORADORUSUARIO=colaborador.IDCOLABORADOR WHERE usuarios.USUARIOING='$usuario' and usuarios.ESTADOUSUARIO=1";
                return ejecutarConsultaSimpleFila($sql);
        }
}