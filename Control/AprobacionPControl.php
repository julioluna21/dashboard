<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$aprobacion = new configuracion();
$consulta = new consultas();



$id=isset($_POST["idPresupuesto"])?limpiarCadena($_POST["idPresupuesto"]):"";
$estado1=isset($_POST["estado1"])?limpiarCadena($_POST["estado1"]):"";
$estado2=isset($_POST["estado2"])?limpiarCadena($_POST["estado2"]):"";
$tipo=isset($_POST["tipo"])?limpiarCadena($_POST["tipo"]):"";
$table_name = "APROBACION_FLOTA";

switch ($_GET["op"]) {
  case 'Aprobar':
    try {
      if ($tipo==1){
		  
		 $data_values = array('ESTADO_AP_PRESUPUESTO' => 1);
         $where_condition = array('ID_PRESUPUESTO_FLOTA' => $id);
         $rspta = $aprobacion->editar('PRESUPUESTO_FLOTA', $data_values, $where_condition); 
		 if($rspta){
			 
		 $data_values = array('ID_PRESUPUESTO_FLOTA_AP'=>$id,"ID_USUARIO_AP"=>$_SESSION['IdUsuarios'],"TIPO_APROBACION_AP"=>"RQ","ESTADO_AP"=>1);
         $rspta = $aprobacion->insertar('APROBACION_FLOTA', $data_values);	 
         echo $rspta ? "Aprobacion Exitosa" : "No se pudo realizar el registro";	 
		 }else{
			 echo "error no se pudo aprobar el registro";
		 }  	 
		  
      } else {
		  
         $data_values = array('ESTADO_AP_PRESUPUESTO' => 2);
         $where_condition = array('ID_PRESUPUESTO_FLOTA' => $id);
         $rspta = $aprobacion->editar('PRESUPUESTO_FLOTA', $data_values, $where_condition); 
		 if($rspta){
		 $data_values = array('ID_PRESUPUESTO_FLOTA_AP'=>$id,"ID_USUARIO_AP"=>$_SESSION['IdUsuarios'],"TIPO_APROBACION_AP"=>"OC","ESTADO_AP"=>1);
         $rspta = $aprobacion->insertar('APROBACION_FLOTA', $data_values);	 
         echo $rspta ? "Aprobacion Exitosa" : "No se pudo realizar el registro";
			 
		 }else{
			 echo "error no se pudo aprobar el registro";
		 } 
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
    
          $rspta = $consulta->ListarAprobacionFlota($estado1, $estado2);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {   
			  
			 $ap=""; 
			if($estado1==4){
			   $bot='<SPAN title="Aprobar"><button class="btn btn-light" onclick="aprobacion('.$reg->ID_PRESUPUESTO_FLOTA .',1)"><i class="fa fa-check-circle " style=""></i></button></SPAN> ';  	
				$ap=$reg->RQ_PRESUPUESTO;
			}else{
				$bot='<SPAN title="Aprobar"><button class="btn btn-light" onclick="aprobacion('.$reg->ID_PRESUPUESTO_FLOTA .',2)"><i class="fa fa-check-circle" style=""></i></button></SPAN> '; 
				$ap=$reg->OC_PRESUPUESTO;
			} 
			  
			 $enlace='<SPAN title="Mostrar"><button class="btn btn-light" onclick="alert('."'"."No hay enlace agregado"."'".')"><i class="fa fa-external-link" style=""></i></button></SPAN>';

			if($reg->LINK_CLOUDFLIT!=""){
			$enlace='<SPAN title="Mostrar"><button class="btn btn-light" onclick="window.open('."'".$reg->LINK_CLOUDFLIT."'".","."'"."_blank"."'".');"><i class="fa fa-external-link" style=""></i></button></SPAN>';
			}  
			  
			  
         
			
            $data[]=array(
              "0"=>$reg->PLACA_VEH,
              "1"=>$reg->NOMBRE_TIPO_MNT,
			  "2"=>$reg->NOM_TIPO_VEHICULO,	
			  "3"=>$reg->nombreProyecto,
			  "4"=>"$".$reg->VALOR_PRESUPUESTO,
			  "5"=>$ap,	
			  "6"=>$enlace,	
              "7"=>$bot
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
      
	

  default:
    echo "La opción seleccionada no existe";
    break;
}

?>