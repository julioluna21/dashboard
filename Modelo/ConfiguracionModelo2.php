<?php
require_once "../Conexion/ConexionDB.php";
class configuracion{
    public function __construct()
    {
        
    }

    public function insertar_id($table_name, $data_values){
        try {
            $columns = implode(', ', array_keys($data_values));
            $values = implode(', ',array_map('add_quotes',$data_values));
            $query = "INSERT INTO $table_name ($columns) VALUES (".mb_strtoupper($values,'utf-8').")";
            return ejecutarConsulta_retornarID($query);
        } catch (\Throwable $th) {
            echo "Error al guardar: ".$th->getMessage();
        }
    }   
    
    public function insertar($table_name, $data_values){
        try {
            
            $columns = implode(', ', array_keys($data_values));
            $values = implode(', ',array_map('add_quotes',$data_values));//La función add_quotes se encuentra en "/ConexionDb"
            $query = "INSERT INTO $table_name ($columns) VALUES (".mb_strtoupper($values,'utf-8').")";
            return ejecutarConsulta($query);
        } catch (\Throwable $th) {
            echo "Error al guardar: ".$th->getMessage();
        }
    } 
	
	 public function insertarvh($table_name, $data_values){
        try {
            
            $columns = implode(', ', array_keys($data_values));
            $values = implode(', ',array_map('add_quotes',$data_values));//La función add_quotes se encuentra en "/ConexionDb"
            $query = "INSERT INTO $table_name ($columns) VALUES (".$values.")";
            return ejecutarConsulta($query);
        } catch (\Throwable $th) {
            echo "Error al guardar: ".$th->getMessage();
        }
    } 
    
    public function mostrar($table_name, $where_condition){
        try {
            $where_id = '';
            foreach ($where_condition as $columns => $value) {
                $where_id .= "$columns = $value";    
            }
            $query = "SELECT * FROM $table_name WHERE $where_id";
            return ejecutarConsultaSimpleFila($query);
        } catch (\Throwable $th) {
            echo "Error al guardar: ".$th->getMessage();
        }
    }   
    
    public function listar($table_name, $where_condition){
        try {
            $where_id = '';
            foreach ($where_condition as $columns => $value) {
                $where_id .= "$columns = $value";    
            }
            $query = "SELECT * FROM $table_name WHERE $where_id";
            return ejecutarConsulta($query);
        } catch (\Throwable $th) {
            echo "Error al guardar: ".$th->getMessage();
        }
    }   
    
    
    public function validar($table_name, $where_condition){
        try {
            $where_id = '';
            foreach ($where_condition as $columns => $value) {
                $where_id .= "$columns= '$value'";    
            }
            $query = "SELECT * FROM $table_name WHERE $where_id";
            return ejecutarConsulta($query);
        } catch (\Throwable $th) {
            echo "Error al guardar: ".$th->getMessage();
        }
    }   
    
    public function borrar($table_name, $where_condition){
        try {
            $where_id = '';
            foreach ($where_condition as $columns => $value) {
                $where_id .= "$columns = ?";    
            }
            $query = "DELETE FROM $table_name WHERE $where_id";
            return ejecutarConsulta($query);
        } catch (\Throwable $th) {
            echo "Error al guardar: ".$th->getMessage();
        }
    }   
    
    public function editar($table_name, $data_values, $where_condition){
        try {
            $set_val = '';
            foreach ($data_values as $columns => $value) {
                $con_comillas= "'$value'";
                $set_val .= "$columns =".mb_strtoupper($con_comillas,'utf-8').",";
            }
            $set_val= rtrim($set_val,', ');
            $where_id = '';
            foreach ($where_condition as $columns => $value) {
                $where_id .= "$columns = $value AND ";    
            }
            $where_id = rtrim($where_id, 'AND ');
            $query = "UPDATE $table_name SET $set_val WHERE $where_id";
            return ejecutarConsulta($query);
        } catch (\Throwable $th) {
            echo "Error al guardar: ".$th->getMessage();
        }
    }  
	
	public function editarvh($table_name, $data_values, $where_condition){
        try {
            $set_val = '';
            foreach ($data_values as $columns => $value) {
                $con_comillas= "'$value'";
                $set_val .= "$columns =".$con_comillas.",";
            }
            $set_val= rtrim($set_val,', ');
            $where_id = '';
            foreach ($where_condition as $columns => $value) {
                $where_id .= "$columns = $value AND";    
            }
            $where_id = rtrim($where_id, 'AND ');
            $query = "UPDATE $table_name SET $set_val WHERE $where_id";
            return ejecutarConsulta($query);
        } catch (\Throwable $th) {
            echo "Error al guardar: ".$th->getMessage();
        }
    }  
    
    
}
?>