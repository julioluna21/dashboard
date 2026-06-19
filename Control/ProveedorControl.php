<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$proveedor = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idproveedor"])?limpiarCadena($_POST["idproveedor"]):"";
$nit=isset($_POST["nit"])?limpiarCadena($_POST["nit"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$telefono=isset($_POST["tel"])?limpiarCadena($_POST["tel"]):"";
$correo=isset($_POST["correo"])?limpiarCadena($_POST["correo"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "proveedores";

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		 $where_condition = array('NIT_PROVEEDOR'=>$nit);//$_GET["estado"]); 
		 $rspta = $proveedor->validar($table_name, $where_condition);  
		 if($rspta->num_rows<=0){
		 $data_values = array('NIT_PROVEEDOR'=>$nit,"RAZON_SOCIAL"=>$nombre,"TEL_PROVEEDOR"=>$telefono,"CORREO_PROVEEDOR"=>$correo,"ESTADO_PROVEEDOR"=>1);
         $rspta = $proveedor->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		 }else{
			 echo "Error ya esta registrado este nit";
		 }  
      } else {
        $data_values = array('RAZON_SOCIAL' => $nombre,"TEL_PROVEEDOR"=>$telefono,"CORREO_PROVEEDOR"=>$correo);
        $where_condition = array('ID_PROVEEDOR' => $id);
        $rspta = $proveedor->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
    
          $where_condition = array('ESTADO_PROVEEDOR'=>$_GET["estado"]); 

          $rspta = $proveedor->listar($table_name, $where_condition);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_PROVEEDOR.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->ID_PROVEEDOR.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->ESTADO_PROVEEDOR==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_PROVEEDOR.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->ID_PROVEEDOR.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
              "0"=>$reg->NIT_PROVEEDOR ,
              "1"=>$reg->RAZON_SOCIAL,	
			  "2"=>$reg->TEL_PROVEEDOR,	
			  "3"=>$reg->CORREO_PROVEEDOR,		
              "4"=>$bot
            );
            
          }
		  
		  $result = array(
              "sEcho"=>1,
              "iTotalRecords"=>count($data),
              "iTotalDisplayRecords"=>count($data),
              "aaData"=>$data
            );
            echo json_encode($result);
        
      } catch (\Throwable $th) {
        echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
      }
      break;
      case 'mostrar':
        try {
          $where_condition = array('ID_PROVEEDOR'=>$id);//$_GET["estado"]);   
           $rspta = $proveedor->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('ESTADO_PROVEEDOR' => 1);
            $where_condition = array('ID_PROVEEDOR' => $id);
            $rspta = $proveedor->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('ESTADO_PROVEEDOR' => 0);
            $where_condition = array('ID_PROVEEDOR' => $id);
            $rspta = $proveedor->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
	

  default:
    echo "La opción seleccionada no existe";
    break;
}

?>