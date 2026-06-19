<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$GestionPresupuesto = new configuracion();
$Consulta = new consultas();


$idp=isset($_POST["idGestiongasto"])?limpiarCadena($_POST["idGestiongasto"]):"";
$id=isset($_POST["idpresupuesto"])?limpiarCadena($_POST["idpresupuesto"]):"";
$anio=isset($_POST["anio"])?limpiarCadena($_POST["anio"]):"";
$mes=isset($_POST["mes"])?limpiarCadena($_POST["mes"]):"";
$ValorP=isset($_POST["Valorp"])?limpiarCadena($_POST["Valorp"]):"";
$ValorT=isset($_POST["Valort"])?limpiarCadena($_POST["Valort"]):"";
$ValorC=isset($_POST["ValorC"])?limpiarCadena($_POST["ValorC"]):"";
$EstadoGestion=isset($_POST["EstadoG"])?limpiarCadena($_POST["EstadoG"]):"";
$datos = isset($_POST["info"]) ? json_decode(json_encode($_POST['info']),true) : "";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "gestion_gasto";

$meses= array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($idp)) {
		 if($datos!=""){
			  $data_values = array('ID_PRESUPUESTO_G'=>$id,"ANIO_EJECUCION"=>$datos[0]['anio'],"MES_EJECUCION"=>$datos[0]['mes'],"OBSERVACION_GESTION"=>$datos[0]['observacion'],"VALOR_TOTAL"=>$datos[0]['valor'],"ESTADO_ESTION"=>1);
         $rspta = $GestionPresupuesto->insertar_id($table_name, $data_values);
		 if($rspta){
		 $id=$rspta;	 
		 for($i=0;$i<count($datos);$i++){
			 $elemento=limpiarCadena($datos[$i]['elemento']);
			 $cantidad=limpiarCadena($datos[$i]['cantidad']);
             $valorU=limpiarCadena($datos[$i]['valorU']);
			 $valorT=limpiarCadena($datos[$i]['valotT']);
			 $data_values = array('ID_GESTION_PRS'=>$id,"ELEMENTO"=>$elemento,'CANTIDAD'=>$cantidad,'VALOR_UNITARIO'=>$valorU,"VALOR_TOTAL"=>$valorT);
             $rspta = $GestionPresupuesto->insertar('detalle_gestion_presupuesto', $data_values);
                   
          }	 
			echo "Resgitro exitoso"; 
		 }else{
			 echo "error no se realizo el registro del encabezado";
		 }	 
		 }else{
			 echo "Error no se realizo el registro no llegaron los datos";
		 }
		 	  
      } else {
        $data_values = array('OBSERVACION_GESTION' => $datos[0]['observacion'],"VALOR_TOTAL"=>$datos[0]['valor']);
        $where_condition = array('ID_GESTION' => $idp);
        $rspta = $GestionPresupuesto->editar($table_name, $data_values, $where_condition);
        if($rspta){
		$where_condition = array('ID_GESTION_PRS'=>$idp);
		$rspta = $GestionPresupuesto->borrar("detalle_gestion_presupuesto",$where_condition);
		for($i=0;$i<count($datos);$i++){
			 $elemento=limpiarCadena($datos[$i]['elemento']);
			 $cantidad=limpiarCadena($datos[$i]['cantidad']);
             $valorU=limpiarCadena($datos[$i]['valorU']);
			 $valorT=limpiarCadena($datos[$i]['valotT']);
			 $data_values = array('ID_GESTION_PRS'=>$idp,"ELEMENTO"=>$elemento,'CANTIDAD'=>$cantidad,'VALOR_UNITARIO'=>$valorU,"VALOR_TOTAL"=>$valorT);
             $rspta = $GestionPresupuesto->insertar('detalle_gestion_presupuesto', $data_values);
                   
          }		
		  Echo "Registro editado exitosamente";	
		}else{
			echo "Error no se pudo editar el registro";
		}
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
	
	case 'listarInicial':
      try {
		  
		 
		  $data = Array();
		  $vandera=false;
		  
		  for($i=1;$i<=12;$i++){
			  
		  $Compras=0;  
		  $where_condition = array('MESCOMPRA'=>$i," AND ANIOCOMPRA"=>$anio, " AND ESTADOCOMPRA"=>1); 
          $rspta = $GestionPresupuesto->listar('compras', $where_condition);	  
		  while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
		  {
		  $valor=str_replace(".", "",$reg->VALORCOMPRA);	  
		  $Compras+=$valor;
		  
		  }
			  
			  
		  $where_condition = array('MES_CERRADO'=>$i, " and ANIO_CERRADO"=>$anio); 
		  $rsptaV= $GestionPresupuesto->validar('meses_cerrados', $where_condition);  
		
		  if($rsptaV->num_rows<=0){
		  $valoP=0;
		  $valorT=0;
		  $cerradas=0;
		  $cantidad=0;	  
		  $where_condition = array('MESP'=>$i, " and ANIOP"=>$anio); 
          $rspta = $GestionPresupuesto->listar('meses_presupuesto', $where_condition);
			  
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
		  {	  
			  
		 	  
		  $idpre=$reg->ID_PRESUPUESTOM;
		  $where_condition = array('ID_PRESUPUESTO_G'=>$idpre, "AND ANIO_EJECUCION"=>$anio," AND MES_EJECUCION"=>$i);//$_GET["estado"]); 
		  $rspta2 = $GestionPresupuesto->validar('gestion_gasto', $where_condition);  
			  
		  if($rspta2->num_rows<=0){	  
			
		  $where_condition = array('ID_PRESUPUESTO'=>$idpre); 
		  $rsptaP= $GestionPresupuesto->validar('presupuesto', $where_condition); 	  
		  $regP=$rsptaP->fetch_object(); 
		
		if($regP->ESTADO_PRESUPUESTO==1){
		   $cantidad=$cantidad+1;	
		  $valoPresupuesto=str_replace(".", "",$regP->VALOR_PRESUPUESTO);	  
		  $valoP=$valoP+$valoPresupuesto;	
		}	  
		  }else{
		   $cantidad=$cantidad+1;	  
		  $reg2=$rspta2->fetch_object();
		  $idej=$reg2->ID_GESTION;
		  if($reg2->ESTADO_ESTION==2){
		  $cerradas=$cerradas+1;	  
		  }	  	  
		  $valoPresupuesto=str_replace(".", "",$reg2->VALOR_TOTAL);	  
		  $valoP=$valoP+$valoPresupuesto;
		  $where_condition = array('ID_GESTION_PRS'=>$idej); 
          $rspta3 = $GestionPresupuesto->listar('detalle_gestion_presupuesto', $where_condition);	  
		  while ($reg3=$rspta3->fetch_object())//mientras exista objeto en la respuesta
		  {
		  $valorElemento=str_replace(".", "",$reg3->VALOR_TOTAL);
		  $valorT=$valorT+$valorElemento;	  
		  }
			  
		  }	   
			  
		  }
			 if($valoP>0){
			
				 $bot='<SPAN title="MOSTRAR"><button class="btn btn-light" onclick="listarGestionB('.$anio.','.$i.',1)"><i class="fa fa-eye" style=""></i></button></SPAN>';  
			  
			 if($cantidad==$cerradas and ($vandera or count($data)==0)){
			 $vandera=false;	 
			 $bot=$bot.' <SPAN title="Cerrar mes"><button class="btn btn-light" onclick="cerrar('.$anio.','.$i.','.$valoP.','.$valorT.','.$Compras.')"><i class="fa fa-check" style=""></i></button></SPAN>';  	 	 
			 } 
			  
			  $data[]=array(
              "0"=>$meses[$i-1] ,
              "1"=>$anio,	
			  "2"=>"$".number_format($valoP),	
			  "3"=>"$".number_format($valorT),
			  "4"=>"$".number_format($Compras),	  
			  "5"=>'<SPAN title="ABIERTA"><button class="btn btn-warning" >ABIERTA <i class="fa fa-meh-o" ></i></button></SPAN>',	  
              "6"=>$bot
            );	
				 
				 
			 }	   
			   
		   }else{
		   $vandera=true;	  
		   $reg=$rsptaV->fetch_object();	
		    $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="listarGestionB('.$anio.','.$i.',0)"><i class="fa fa-eye" style=""></i></button></SPAN>';  	   
		   $data[]=array(
              "0"=>$meses[$i-1] ,
              "1"=>$anio,	
			  "2"=>"$".number_format($reg->VALOR_PRESUPESUTO),	
			  "3"=>"$".number_format($reg->VALOR_TOATAL),
			  "4"=>"$".number_format($reg->VALORCOMPRAS),
			  "5"=>'<SPAN title="CERRADA"><button class="btn btn-success" >CERRADA <i class="fa fa-smile-o " ></i></button></SPAN>',
              "6"=>$bot
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
		
		
    case 'listarPresupuesto':
      try {
    
          $where_condition = array('MESP'=>$mes, " and ANIOP"=>$anio); 
          $rspta = $GestionPresupuesto->listar('meses_presupuesto', $where_condition);
          $data = Array();
			  
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
		  {	  
			    
		  $idpre=$reg->ID_PRESUPUESTOM;
		  $where_condition = array('ID_PRESUPUESTO_G'=>$idpre, "AND ANIO_EJECUCION"=>$anio," AND MES_EJECUCION"=>$mes);//$_GET["estado"]); 
		  $rspta2 = $GestionPresupuesto->validar('gestion_gasto', $where_condition);  
			  
		  if($rspta2->num_rows<=0){	  
			
		 $rsptaP= $Consulta->mostrarPresupuesto($idpre); 	
		 if($rsptaP['ESTADO_PRESUPUESTO']==1 and $EstadoGestion==1){
		  $tiempocobro="";	  
		  switch($rsptaP['TIEMPO_COBRO']){
			  case '1':
				  $tiempocobro="MENSUAL";
				  break;
				  case '2':
				  $tiempocobro="BIMESTRAL";
				  break;
				  case '3':
				  $tiempocobro="TRIMESTRAL";
				  break;
				  case '4':
				  $tiempocobro="SEMESTRAL";
				  break;
				  case '5':
				  $tiempocobro="ANUAL";
				  break;
		  }	  
			  
		  $tipopago="";	  
		  switch($rsptaP['TIPO_PAGO']){
			  case '1':
				  $tipopago="ANTICIPADO";
				  break;
				  case '2':
				  $tipopago="VENCIDO";
				  break;
		  }	
			  
		 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrarPresupuesto('.$rsptaP['ID_PRESUPUESTO'].')"><i class="fa fa-eye" style=""></i></button></SPAN>';  	  
			  
		  $data[]=array(
              "0"=>$rsptaP['RAZON_SOCIAL'],
              "1"=>$rsptaP['NombreUen'],	
			  "2"=>$rsptaP['NombreEmpresa'],	
			  "3"=>"$".$rsptaP['VALOR_PRESUPUESTO'],	
			  "4"=>"0",	
			  "5"=>$tiempocobro,	
			  "6"=>$tipopago,	
			  "7"=>"ABIERTA",	 
              "8"=>$bot
            );	 
		 }	  	  
			  
		  }else{
		  $rsptaP= $Consulta->mostrarPresupuesto2($idpre,$anio,$mes); 	
			  
		  $idGestion=$rsptaP['ID_GESTION'];
		  $estadoG=$rsptaP['ESTADO_ESTION'];
		  $where_condition = array('ID_GESTION_PRS'=>$idGestion); 
          $rsptaG = $GestionPresupuesto->listar('detalle_gestion_presupuesto', $where_condition);
		  $valorT=0; 
		  while ($regG=$rsptaG->fetch_object())//mientras exista objeto en la respuesta
		  {
		  $valorElemento=str_replace(".", "",$regG->VALOR_TOTAL);
		  $valorT=$valorT+$valorElemento;	  
		  }	  
			  
		
		   $tiempocobro="";	  
		  switch($rsptaP['TIEMPO_COBRO']){
			  case '1':
				  $tiempocobro="MENSUAL";
				  break;
				  case '2':
				  $tiempocobro="BIMESTRAL";
				  break;
				  case '3':
				  $tiempocobro="TRIMESTRAL";
				  break;
				  case '4':
				  $tiempocobro="SEMESTRAL";
				  break;
				  case '5':
				  $tiempocobro="ANUAL";
				  break;
		  }	  
			  
		  $tipopago="";	  
		  switch($rsptaP['TIPO_PAGO']){
			  case '1':
				  $tipopago="ANTICIPADO";
				  break;
				  case '2':
				  $tipopago="VENCIDO";
				  break;
		  }	
			  
		 $bot=''; 	  
		if($estadoG!=2){
		$estadoG="ABIERTA";	
		$bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrarGEstion('.$rsptaP['ID_PRESUPUESTO'].','.$anio.','.$mes.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="Cerrar Presupuesto"><button class="btn btn-light" onclick="cerrarP('.$idGestion.')"><i class="fa fa-check" style=""></i></button></SPAN>'; 	
		}else{
		$estadoG="CERRADA";	
		$bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrarGEstion('.$rsptaP['ID_PRESUPUESTO'].','.$anio.','.$mes.')"><i class="fa fa-eye" style=""></i></button></SPAN>'; 	
		}	  
			   
			  	  
			  
		  $data[]=array(
              "0"=>$rsptaP['RAZON_SOCIAL'],
              "1"=>$rsptaP['NombreUen'],	
			  "2"=>$rsptaP['NombreEmpresa'],	
			  "3"=>"$".$rsptaP['VALOR_TOTAL'],	
			  "4"=>"$".number_format($valorT),	
			  "5"=>$tiempocobro,	
			  "6"=>$tipopago,
			  "7"=>$estadoG,
              "8"=>$bot
            );
			  
			  
		  }	 
			  
			//echo $data; 
			  
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
			   
		
	case 'listarCompra':
      try {
          $data= array();
          $rspta = $Consulta->ListarCompra2($mes,$anio);	
			  
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
		  {	    
		   $data[]=array(
              "0"=>$reg->RAZON_SOCIAL,
              "1"=>$reg->NombreUen,	
			  "2"=>$reg->NombreEmpresa,	
			  "3"=>$reg->FECHACOMPRA,		
			  "4"=>"$".$reg->VALORCOMPRA,		
              "5"=>'<SPAN title="Detalle"><button class="btn btn-light" onclick="alert('."'".$reg->NOMBRE_ELEMENTO."'".')"><i class="fa fa-eye" style=""></i></button></SPAN>',
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
			   
      case 'mostrarPresupuesto':
        try {
			
		  $rspta= $Consulta->mostrarPresupuesto($id); 		
          echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
			   
	   case 'MostrarGestion'://activado por el ajax 
			   
                   $rspta= $Consulta->mostrarPresupuesto2($id,$anio,$mes); 	
			       $idGestion=$rspta['ID_GESTION'];
			       $where_condition = array('ID_GESTION_PRS'=>$idGestion); 
                   $rsptaG = $GestionPresupuesto->listar('detalle_gestion_presupuesto',$where_condition);
			       $data= Array();
		           while ($reg=$rsptaG->fetch_object())//mientras exista objeto en la respuesta
		           {
		              $data[]=$reg; 
		           }	
                   echo json_encode(array("Encabezado"=>$rspta,"Detalle"=>$data));
                    
            break;
			   
        case 'cerrarMes':
          try {
          $data_values =  array('MES_CERRADO'=>$mes,"ANIO_CERRADO"=>$anio,'VALOR_PRESUPESUTO'=>$ValorP,'VALOR_TOATAL'=>$ValorT,"VALORCOMPRAS"=>$ValorC);
             $rspta = $GestionPresupuesto->insertar('meses_cerrados', $data_values);
            echo $rspta? "Se cerror el mes xitosamente": "Error no se pudo cerrar el mes";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
			   
        case 'CerrarGestion':
          try {
            $data_values = array('ESTADO_ESTION' => 2);
            $where_condition = array('ID_GESTION' => $idp);
            $rspta = $GestionPresupuesto->editar('gestion_gasto', $data_values, $where_condition);
            echo $rspta? "Se cerro el registro exitosamente": "Error no se pudo cerrar el registro.";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
	

  default:
    echo "La opción seleccionada no existe";
    break;
}

?>