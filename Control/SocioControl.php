<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$socio = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idsocio"])?limpiarCadena($_POST["idsocio"]):"";
$nit=isset($_POST["nit"])?limpiarCadena($_POST["nit"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "socios";

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		 $where_condition = array('NitSocio'=>$nit);//$_GET["estado"]); 
		 $rspta = $socio->validar($table_name, $where_condition);  
		 if($rspta->num_rows<=0){
	     $data_values = array('NitSocio'=>$nit,"NombreSocio"=>$nombre,"EstadoSocio"=>1);
         $rspta = $socio->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
			  
		  }else{
			  echo "Error el nit ya se encuentra registrado";
		  }
		  
      } else {
        $data_values = array('NitSocio'=>$nit,"NombreSocio"=>$nombre,"EstadoSocio"=>1);
        $where_condition = array('IDSocio' => $id);
        $rspta = $socio->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
		  
		  $where_condition = array('EstadoSocio'=>$_GET["estado"]); 

          $rspta = $socio->listar($table_name, $where_condition);
    
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDSocio.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->IDSocio.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->EstadoSocio==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDSocio.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->IDSocio.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
              "0"=>$reg->NitSocio,
              "1"=>$reg->NombreSocio,	
              "2"=>$bot
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
          $where_condition = array('IDSocio'=>$id);//$_GET["estado"]);   
           $rspta = $socio->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('EstadoSocio' => 1);
            $where_condition = array('IDSocio' => $id);
            $rspta = $socio->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('EstadoSocio' => 0);
            $where_condition = array('IDSocio' => $id);
            $rspta = $socio->editar($table_name, $data_values, $where_condition);
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