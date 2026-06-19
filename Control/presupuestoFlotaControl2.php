<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$PresupuestoF = new configuracion();
$Consulta= new consultas();



$id=isset($_POST["idPresupuesto"])?limpiarCadena($_POST["idPresupuesto"]):"";
$proyecto=isset($_POST["idproyecto"])?limpiarCadena($_POST["idproyecto"]):"";
$anio=isset($_POST["anio"])?limpiarCadena($_POST["anio"]):"";
$mes=isset($_POST["mes"])?limpiarCadena($_POST["mes"]):"";
$tipoMnt=isset($_POST["tipo"])?limpiarCadena($_POST["tipo"]):"";
$tipoMantenimiento=isset($_POST["tipomantenimiento"])?limpiarCadena($_POST["tipomantenimiento"]):"";
$vehiculo=isset($_POST["vehiculo"])?limpiarCadena($_POST["vehiculo"]):"";
$ValorP=isset($_POST["valorp"])?limpiarCadena($_POST["valorp"]):"";
$Detalle=isset($_POST["detalle"])?limpiarCadena($_POST["detalle"]):"";
$estado=isset($_POST["estado"])?limpiarCadena($_POST["estado"]):"";
$Valorestado=isset($_POST["Vestado"])?limpiarCadena($_POST["Vestado"]):"";
$ValorEJ=isset($_POST["valorej"])?limpiarCadena($_POST["valorej"]):"";
$Enlace=isset($_POST["enlace"])?limpiarCadena($_POST["enlace"]):"";
$meses= array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
$table_name = "PRESUPUESTO_FLOTA";

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		 $data_values = array('ID_VEHICULO_PRESUPUESTO'=>$vehiculo,"TIPO_FALLA_PRESUPUESTO"=>$tipoMnt,"ANIO_PRESUPUESTO"=>$anio,"MES_PRESUPUESTO"=>$mes,"VALOR_PRESUPUESTO"=>$ValorP,"VALOR_EJECUTADO"=>0,"DESCRIPCION_PRESUPUESTO"=>$Detalle,"PROYECTO_PRESUPUESTO"=>$proyecto,"ESTADO_PRESUPUESTO"=>5,"ESTADO_AP_PRESUPUESTO"=>0,"MANTENIMIENTO_PRESUPUESTO"=>$tipoMantenimiento);
         $rspta = $PresupuestoF->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro ".print_r($data_values);	 
		  
      } else {
		  
        $data_values = array('ID_VEHICULO_PRESUPUESTO'=>$vehiculo,"TIPO_FALLA_PRESUPUESTO"=>$tipoMnt,"ANIO_PRESUPUESTO"=>$anio,"MES_PRESUPUESTO"=>$mes,"VALOR_PRESUPUESTO"=>$ValorP,"DESCRIPCION_PRESUPUESTO"=>$Detalle,"PROYECTO_PRESUPUESTO"=>$proyecto,"MANTENIMIENTO_PRESUPUESTO"=>$tipoMantenimiento);
        $where_condition = array('ID_PRESUPUESTO_FLOTA'=>$id);
        $rspta = $PresupuestoF->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
		  $data = Array();
		  for($i=1;$i<=12;$i++){
		  $where_condition = array('ANIO_PRESUPUESTO'=>$anio, " and MES_PRESUPUESTO"=>$i, " and not ESTADO_PRESUPUESTO"=>0);
		  $rspta = $PresupuestoF->listar($table_name, $where_condition);
		  $valorP=0;
		  $valorEJ=0;	  
		  while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {
		  $valor=str_replace(".","",$reg->VALOR_PRESUPUESTO);	 
		  $valorP=$valorP+$valor;
		  if($reg->ESTADO_PRESUPUESTO<=3){
		  $valor=str_replace(".", "",$reg->VALOR_EJECUTADO);
		  $valorEJ=$valorEJ+$valor;	  
		  }		 
	      }
			  
		if($valorP>0){
			 $data[]=array(
              "0"=>$meses[$i-1],
              "1"=>$anio,
			  "2"=>"$".number_format($valorP),
			  "3"=>"$".number_format($valorEJ),	 
              "4"=>'<SPAN title="Mostrar"><button class="btn btn-light" onclick="listarGeneral('.$i.','.$anio.')"><i class="fa fa-eye" style=""></i></button></SPAN>'
            );  
			    
		  }	  	  
			  
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
		
	 case 'listarGestion':
      try {
		  $data = Array();
		  $rspta = $Consulta->PresupuestoFlota($estado,$anio,$mes); 
		  while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {
			  
		  if($reg->ESTADO_PRESUPUESTO==5){
		  $bot='<SPAN title="Mostrar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_PRESUPUESTO_FLOTA.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="Agregar RQ"><button class="btn btn-light" onclick="agregar('.$reg->ID_PRESUPUESTO_FLOTA.',4)"><i class="fa fa-file-text" style=""></i></button></SPAN>'; 
		  }else if($reg->ESTADO_PRESUPUESTO==4){
			  
			 if($reg->ESTADO_AP_PRESUPUESTO==1){
			  $bot='<SPAN title="Mostrar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_PRESUPUESTO_FLOTA.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="Agregar OC"><button class="btn btn-light" onclick="Ejecucion('.$reg->ID_PRESUPUESTO_FLOTA.',3)"><i class="fa fa-archive" style=""></i></button></SPAN>';
			 }else{
				  $bot='<SPAN title="Mostrar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_PRESUPUESTO_FLOTA.')"><i class="fa fa-eye" style=""></i></button></SPAN>';
			 } 
			     
		  }else if($reg->ESTADO_PRESUPUESTO==3){
			  
			  if($reg->ESTADO_AP_PRESUPUESTO==2){
		       $bot='<SPAN title="Mostrar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_PRESUPUESTO_FLOTA.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="Agregar a inventario"><button class="btn btn-light" onclick="Inventario('.$reg->ID_PRESUPUESTO_FLOTA.')"><i class="fa fa-plus" style=""></i></button></SPAN>'; 		  
			  }else{
				$bot='<SPAN title="Mostrar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_PRESUPUESTO_FLOTA.')"><i class="fa fa-eye" style=""></i></button></SPAN>';  
			  }
		  
		  }else if($reg->ESTADO_PRESUPUESTO==2){
			 $bot='<SPAN title="Mostrar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_PRESUPUESTO_FLOTA.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="Ejecutada"><button class="btn btn-light" onclick="Ejecutado('.$reg->ID_PRESUPUESTO_FLOTA.')"><i class="fa fa-check" style=""></i></button></SPAN>';   
			   
		  }else if($reg->ESTADO_PRESUPUESTO==1){
			 $bot='<SPAN title="Mostrar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_PRESUPUESTO_FLOTA.')"><i class="fa fa-eye" style=""></i></button></SPAN>';   
		  }
			 
			 $enlace='<SPAN title="Mostrar"><button class="btn btn-light" onclick="alert('."'"."No hay enlace agregado"."'".')"><i class="fa fa-external-link" style=""></i></button></SPAN>';
			if($reg->ESTADO_PRESUPUESTO<=4){
				
			if($reg->LINK_CLOUDFLIT==""){
			$enlace='<SPAN title="Mostrar"><button class="btn btn-light" onclick="enlace('.$reg->ID_PRESUPUESTO_FLOTA.')"><i class="fa fa-external-link" style=""></i></button></SPAN>'; 		
			}else{
			$enlace='<SPAN title="Mostrar"><button class="btn btn-light" onclick="window.open('."'".$reg->LINK_CLOUDFLIT."'".","."'"."_blank"."'".');"><i class="fa fa-external-link" style=""></i></button></SPAN>'; 
			}	
				
			 } 
			  
			  
			 $data[]=array(
              "0"=>$reg->PLACA_VEH,
              "1"=>$reg->NOM_TIPO_VEHICULO,
			  "2"=>$reg->nombreProyecto,
			  "3"=>$reg->NOMBRE_TIPO_MNT,
			  "4"=>"$".$reg->VALOR_PRESUPUESTO,	 
			  "5"=>"$".$reg->VALOR_EJECUTADO,	 	 
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
		
      case 'mostrar':
        try {
			
           $rspta = $Consulta->MPresupuestoFlota($id); 
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
		
        case 'activar':
          try {
            $data_values = array('ESTADO_PRESUPUESTO' => 5);
            $where_condition = array('ID_PRESUPUESTO_FLOTA' => $id);
            $rspta = $PresupuestoF->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('ESTADO_PRESUPUESTO' => 0);
            $where_condition = array('ID_PRESUPUESTO_FLOTA' => $id);
            $rspta = $PresupuestoF->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		 case 'Estados':
          try {
			if($estado==5){
			
		    $data_values = array('ESTADO_PRESUPUESTO'=>4,"RQ_PRESUPUESTO"=>$Valorestado,"LINK_CLOUDFLIT "=>$Enlace);
            $where_condition = array('ID_PRESUPUESTO_FLOTA' => $id);
			$rspta = $PresupuestoF->editarvh($table_name, $data_values, $where_condition);	
			echo $rspta? "RQ agregada con exito": "Error no se pudo agregar la RQ";	
			}else{	
			$data_values = array('ESTADO_PRESUPUESTO'=>3,"OC_PRESUPUESTO"=>$Valorestado,"VALOR_EJECUTADO"=>$ValorEJ);
            $where_condition = array('ID_PRESUPUESTO_FLOTA' => $id);
			$rspta = $PresupuestoF->editar($table_name, $data_values, $where_condition);	
			echo $rspta? "OC agregada con exito": "Error no se pudo agregar la OC";		
		    } 
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		 case 'enlace':
          try {
		    $data_values = array("LINK_CLOUDFLIT "=>$Enlace);
            $where_condition = array('ID_PRESUPUESTO_FLOTA' => $id);
			$rspta = $PresupuestoF->editarvh($table_name, $data_values, $where_condition);	
			echo $rspta? "Enlace agregado con exito": "Error no se pudo agregar el enlace";	
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		 case 'inventario':
          try {
		    $data_values = array('ESTADO_PRESUPUESTO'=>$estado);
            $where_condition = array('ID_PRESUPUESTO_FLOTA' => $id);
			$rspta = $PresupuestoF->editar($table_name, $data_values, $where_condition);	
			echo $rspta? "Se cambio el estado exitosamente": "Error no se pudo agregar el resgitro";		  
		    
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		
	    case 'select':
		try {
		$where_condition= array("ESTADO_TIPO_MNT"=>1);
		$resp=$PresupuestoF->validar("TIPO_MANTENIMIENTO",$where_condition);
		echo '<option value="">Seleccione...</option>';
		while($reg=$resp->fetch_object()){
		echo '<option value="'.$reg->ID_TIPO_MNT.'">'.$reg->NOMBRE_TIPO_MNT.'</option>';	
		}	
		 } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }	
		break;
		
		case 'mostrarVH':
        try {
			
           $rspta = $Consulta->presupuestoVH($vehiculo); 
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
	

  default:
    echo "La opción seleccionada no existe";
    break;
}

?>