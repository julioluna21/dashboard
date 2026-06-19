<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$Sistema = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idsistema"])?limpiarCadena($_POST["idsistema"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$estado=isset($_POST["estado"])?limpiarCadena($_POST["estado"]):"";
$table_name = "SISTEMA_AFECTADO";

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		 $data_values = array('NOMBRE_SISTEMA_AFECTADO'=>$nombre,"ESTADO_SISTEMA_AFECTADO "=>1);
         $rspta = $Sistema->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		  
      } else {
        $data_values = array('NOMBRE_SISTEMA_AFECTADO' => $nombre);
        $where_condition = array('ID_SISTEMA_AFECTADO' => $id);
        $rspta = $Sistema->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
          $where_condition = array('ESTADO_SISTEMA_AFECTADO'=>$estado);//$_GET["estado"]);  
          $rspta = $Sistema->listar($table_name,$where_condition);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_SISTEMA_AFECTADO.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->ID_SISTEMA_AFECTADO.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->ESTADO_SISTEMA_AFECTADO ==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_SISTEMA_AFECTADO.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->ID_SISTEMA_AFECTADO.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
              "0"=>$reg->ID_SISTEMA_AFECTADO,
              "1"=>$reg->NOMBRE_SISTEMA_AFECTADO,	
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
          $where_condition = array('ID_SISTEMA_AFECTADO'=>$id);//$_GET["estado"]);   
           $rspta = $Sistema->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('ESTADO_SISTEMA_AFECTADO' => 1);
            $where_condition = array('ID_SISTEMA_AFECTADO' => $id);
            $rspta = $Sistema->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('ESTADO_SISTEMA_AFECTADO' => 0);
            $where_condition = array('ID_SISTEMA_AFECTADO' => $id);
            $rspta = $Sistema->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		 case 'select':
          try {
           $where_condition = array('ESTADO_SISTEMA_AFECTADO'=>1);//$_GET["estado"]);   
           $rspta = $Sistema->validar($table_name, $where_condition); 
           echo "<option value=''>Seleccione Sistema Afectado...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->ID_SISTEMA_AFECTADO'>$reg->NOMBRE_SISTEMA_AFECTADO</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;

  default:
    echo "La opción seleccionada no existe";
    break;
}

?>