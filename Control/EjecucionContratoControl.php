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
$table_name = "ejecucioncontrato";

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		 $data_values = array('IdContratoEjecuCicion'=>$contrato,"IDCentroOPEjecucion"=>$centro,"EstadoEjecucion"=>1);
         $rspta = $ejecucion->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		  
      } else {
        $data_values = array('IdContratoEjecuCicion' => $contrato,'IDCentroOPEjecucion'=>$centro);
        $where_condition = array('IDEjecucion' => $id);
        $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
    
          $rspta = $Consulta->listarEjecucionContrato($estado);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDEjecucion.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->IDEjecucion.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->EstadoEjecucion==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDEjecucion.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->IDEjecucion.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
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
          $where_condition = array('IDEjecucion'=>$id);//$_GET["estado"]);   
           $rspta = $ejecucion->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('EstadoEjecucion' => 1);
            $where_condition = array('IDEjecucion' => $id);
            $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('EstadoEjecucion' => 0);
            $where_condition = array('IDEjecucion' => $id);
            $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		 case 'select':
          try {
           $where_condition = array('idestadocontrato'=>1);//$_GET["estado"]);   
           $rspta = $ejecucion->validar('contrato', $where_condition); 
           echo "<option value=''>Seleccione Contrato...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDcontrato'>$reg->NombreContrato</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
		
		 case 'select2':
          try {
           $where_condition = array('EstadoCentroOP'=>1);//$_GET["estado"]);   
           $rspta = $ejecucion->validar('centrooperativo', $where_condition); 
           echo "<option value=''>Seleccione Centro Operativo...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDCentroOP'>$reg->NombreCentroOP</option>";
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