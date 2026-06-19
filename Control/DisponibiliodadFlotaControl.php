<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";


$ejecucion = new configuracion();
$Consulta = new consultas();

$id=isset($_POST["idflota"])?limpiarCadena($_POST["idflota"]):"";
$fecha=isset($_POST["fecha"])?limpiarCadena($_POST["fecha"]):"";
$proyecto=isset($_POST["proyecto"])?limpiarCadena($_POST["proyecto"]):"";
$vehiculo=isset($_POST["vehiculo"])?limpiarCadena($_POST["vehiculo"]):"";
$datos = isset($_POST["info"]) ? json_decode(json_encode($_POST['info']),true) : "";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$proyectob=isset($_GET["centrobus"])?limpiarCadena($_GET["centrobus"]):"";
$table_name = "DISPONIBILIDAD_FLOTA";


switch ($_GET["op"]) {
  case 'guardar':
    try {
		
	if (empty($id)) {
		
		 if($datos!=""){
			 
		 for($i=0;$i<count($datos);$i++){
			 $fecha=limpiarCadena($datos[$i]['fecha']);
			 $proyecto=limpiarCadena($datos[$i]['proyecto']);
             $vehiculo=limpiarCadena($datos[$i]['vehiculo']);
			 $data_values = array('ID_VEHICULOS_DIS'=>$vehiculo,"ID_PROYECTO_DIS"=>$proyecto,'FECHA_INICIO_DIS'=>$fecha,'ESTADO_DIS'=>1);
             $rspta = $ejecucion->insertar($table_name, $data_values);
                   
          }
		 echo "Registro exitoso";	 
		  
		 }else{
			echo "Error no se realizo el registro";	 
		 }
		  
		  
      } else {
		
		$rspta = $Consulta->validarFlota($vehiculo,$fecha);	  
		if($rspta->num_rows<=0){
		$where_condition = array('ID_DISPONIBILIDAD'=>$id);//$_GET["estado"]);   
        $rspta = $ejecucion->mostrar($table_name, $where_condition);
		if($rspta['FECHA_INICIO_DIS']<=$fecha){
			$data_values = array('FECHA_FIN_DIS'=>$fecha,'ESTADO_DIS'=>0);
        $where_condition = array('ID_DISPONIBILIDAD' => $id);	
		$rspta = $ejecucion->editar($table_name, $data_values, $where_condition);	
		if($rspta){
		$data_values = array('ID_VEHICULOS_DIS'=>$vehiculo,"ID_PROYECTO_DIS"=>$proyecto,'FECHA_INICIO_DIS'=>$fecha,'ESTADO_DIS'=>1);
        $rspta = $ejecucion->insertar($table_name, $data_values);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
			
		}else{
			echo "Error no se pudo actualizar el registro";
		}
		}else{
			echo 'Error la fecha selecionada es menor a la fecha de inicio de la ultima asignación del proyecto registrado';
		}				
			 
		}else{
			echo 'Error esta selecionando una fecha de inicio que coincide con el registro del vehiculo y otro proyecto en el sistema.';
		}
       
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
			
		
    case 'listar':
      try {
    
          $rspta = $Consulta->listarFlota($estado,$proyectob);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {  
			$bot="";  
			if($reg->ESTADO_DIS==1){   
           $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_DISPONIBILIDAD.')"><i class="fa fa-eye" style=""></i></button></SPAN>';  
			}
			  $fechaf="N/D";
			  if($reg->FECHA_FIN_DIS!=""){
			$fechaf=$reg->FECHA_FIN_DIS;  
			  }
			 
            $data[]=array(
              "0"=>$reg->nombreProyecto,
              "1"=>$reg->PLACA_VEH,
			  "2"=>$reg->NOM_TIPO_VEHICULO,
			  "3"=>$reg->FECHA_INICIO_DIS,
			  "4"=>$fechaf,	
              "5"=>$bot
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
          $where_condition = array('ID_DISPONIBILIDAD'=>$id);//$_GET["estado"]);   
           $rspta = $ejecucion->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;

     	case 'select':
          try {
           $where_condition = array('estadoProyecto'=>1);//$_GET["estado"]);   
           $rspta = $ejecucion->validar('Proyectos', $where_condition); 
           echo "<option value=''>Seleccione Proyecto...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->Idproyctos'>$reg->nombreProyecto</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
				
		
		case 'select2':
          try {
           $where_condition = array('ESTADO_VEHICULO'=>1);//$_GET["estado"]);   
           $rspta = $ejecucion->validar('VEHICULOS', $where_condition); 
           echo "<option value=''>Seleccione vehiculo...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->ID_VEH'>$reg->PLACA_VEH</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
		
		case 'select3':
          try {
           $rspta = $Consulta->selectFlotaVh(); 
           echo "<option value=''>Seleccione vehiculo...</option>";
           while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
              echo "<option value='$reg->ID_VEH'>$reg->PLACA_VEH</option>";
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