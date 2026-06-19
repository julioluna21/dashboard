<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$ejecucion = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idcontrato"])?limpiarCadena($_POST["idcontrato"]):"";
$contrato=isset($_POST["contrato"])?limpiarCadena($_POST["contrato"]):"";
$centro=isset($_POST["centroop"])?limpiarCadena($_POST["centroop"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "EJECUCION_CONTRATO_SER_ASIS";

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		 $data_values = array('ID_CONTRATO'=>$contrato,"ID_CENTROPEATIVO"=>$centro,"ESTADO_EJECICION_ASIS"=>1);
         $rspta = $ejecucion->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		  
      } else {
        $data_values = array('ID_CONTRATO' => $contrato,'ID_CENTROPEATIVO'=>$centro);
        $where_condition = array('ID_EJE_CON_SER_ASIS' => $id);
        $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
    
          $rspta = $Consulta->listarEjecucionContratoAsis($estado);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_EJE_CON_SER_ASIS.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->ID_EJE_CON_SER_ASIS.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->ESTADO_EJECICION_ASIS==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_EJE_CON_SER_ASIS.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->ID_EJE_CON_SER_ASIS.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
              "0"=>$reg->NombreContrato,
              "1"=>$reg->NombreCentroOP,
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
          $where_condition = array('ID_EJE_CON_SER_ASIS'=>$id);//$_GET["estado"]);   
           $rspta = $ejecucion->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('ESTADO_EJECICION_ASIS' => 1);
            $where_condition = array('ID_EJE_CON_SER_ASIS' => $id);
            $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('ESTADO_EJECICION_ASIS' => 0);
            $where_condition = array('ID_EJE_CON_SER_ASIS' => $id);
            $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
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