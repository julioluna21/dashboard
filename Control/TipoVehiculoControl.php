<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$Tipovh = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idtipo"])?limpiarCadena($_POST["idtipo"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "TIPO_VEHICULOS";

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		 $data_values = array('NOM_TIPO_VEHICULO'=>$nombre,"estadotipo"=>1);
         $rspta = $Tipovh->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		  
      } else {
         $data_values = array('NOM_TIPO_VEHICULO'=>$nombre);
        $where_condition = array('ID_TIP_VEH' => $id);
        $rspta = $Tipovh->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
    
          $where_condition = array('estadotipo'=>$_GET["estado"]); 

          $rspta = $Tipovh->listar($table_name, $where_condition);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_TIP_VEH.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->ID_TIP_VEH.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->estadotipo==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_TIP_VEH.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->ID_TIP_VEH.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
              "0"=>$reg->ID_TIP_VEH ,
              "1"=>$reg->NOM_TIPO_VEHICULO,	
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
          $where_condition = array('ID_TIP_VEH'=>$id);//$_GET["estado"]);   
           $rspta = $Tipovh->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('estadotipo' => 1);
            $where_condition = array('ID_TIP_VEH' => $id);
            $rspta = $Tipovh->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('estadotipo' => 0);
            $where_condition = array('ID_TIP_VEH' => $id);
            $rspta = $Tipovh->editar($table_name, $data_values, $where_condition);
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