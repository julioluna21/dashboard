<?php
session_start();
require "../Modelo/ConsultasAnidadas.php";
require "../Modelo/ConfiguracionModelo.php";
setlocale(LC_ALL,'es-Es');// Activa la localización con el sistema para mostrar en español
date_default_timezone_set("America/Lima");
$dash = new configuracion();
$Consulta = new consultas();
$idproyecto=isset($_POST["proyecto"])?limpiarCadena($_POST["proyecto"]):"";
$fechainicial=isset($_POST["finicio"])?limpiarCadena($_POST["finicio"]):"";
$fechafinal=isset($_POST["ffinal"])?limpiarCadena($_POST["ffinal"]):"";
$mes=isset($_POST["MES"])?limpiarCadena($_POST["MES"]):"";
$ano=isset($_POST["ano"])?limpiarCadena($_POST["ano"]):"";
$fechaactual=date("Y-m-d");
$fechaactualhora=date("Y-m-d H:i:s");

function truncar($numero, $digitos)
{
    $truncar = 10**$digitos;
    return intval($numero * $truncar) / $truncar;
}

function minutosTranscurridos($fecha_i,$fecha_f)
           {
             $minutos = ((strtotime($fecha_i)-strtotime($fecha_f))/60);
             $minutos = abs($minutos); $minutos = floor($minutos);
             return $minutos;
           }


switch ($_GET["op"]) {
		
    case 'consulta':
      try {
          $contenido="";
          $rspta = $Consulta->contratosdash($idproyecto);
		  $fechainicail=$rspta['FechaInicio'];
		  $fechafinal=$rspta['fechaFinal'];
		  
                      $firstDate  = new DateTime($fechainicail);
                      $secondDate = new DateTime($fechafinal);
                      $intvl2 = $firstDate->diff($secondDate);    
                      $diastotales=$intvl2->days; 
		  
		              $firstDate  = new DateTime($fechainicail);
                      $secondDate = new DateTime($fechaactual);
                      $intvl2 = $firstDate->diff($secondDate);    
                      $diastrancurridos=$intvl2->days; 
		  
		  
		            $op=($diastrancurridos/$diastotales);
		            if($op>1){
					 $op=1;	
					}
		  
		  $contenido='<tr><td >CONTRATO</td><td style="font-size:12PX">'.$rspta['NombreContrato'].'</td></tr>
					<tr><td>CLIENTE</td><td style="font-size:12PX">'.$rspta['NombreCliente'].'</td></tr>
					<tr><td>TIPO CLIENTE</td><td style="font-size:12PX">'.$rspta['NombreTipoCliente'].'</td></tr>
					<tr><td>EMPRESA</td><td style="font-size:12PX">'.$rspta['NombreEmpresa'].'</td></tr>
					<tr><td>OBJETO CONTRATO</td><td style="font-size:12PX">'.$rspta['ObjetoContrato'].'</td></tr>
					<tr><td>ADMINISTRADOR CLIENTE</td ><td style="font-size:12PX">'.$rspta['AdministradorCliente'].'</td></tr>
				    <tr><td>ADMINISTRADOR EMPRESA</td><td style="font-size:12PX">'.$rspta['AdministradorEmpresa'].'</td></tr>
					<tr><td>VALOR MENSUAL</td><td style="font-size:12PX">$ '.number_format($rspta['ValorMensualContrato'],0, '', '.').'</td></tr>	
					<tr><td>VALOR TOTAL</td><td style="font-size:12PX">$ '.number_format($rspta['ValorTotalContrato'],0, '', '.').'</td></tr>	
					<tr><td>TIPO TARIFA</td><td style="font-size:12PX">'.$rspta['TipoTarifa'].'</td></tr>	
					<tr><td>ESTADO</td><td style="font-size:12PX">'.$rspta['nombreEstado'].'</td></tr>
					<tr><td>No OTRO SÍ</td><td style="font-size:12PX">';
			  
			        if($rspta['NoOS']==0){
					$contenido=$contenido.'0';	
					}else{
						
					$contenido=$contenido.'<table class="stabla">
                    <tr>
                        <th style="width:10%; padding:2px;">No</th>
                        <th style="width:90%; padding:2px;">OBJETO</th>
                    </tr>';	
						
					  $rsptaOT2 = $Consulta->mostrarotrosi($rspta['IDcontrato']);
		              while ($reg=$rsptaOT2->fetch_object()){
					  	 $contenido=$contenido.'<tr>
                        <td style="padding:2px;font-size:12PX">'.$reg->NOOS.'</td>
                        <td style="padding:2px;font-size:12PX">'.$reg->OBJETO .'</td>
                        </tr>';
					  }	
					 $contenido=$contenido.'</table>';	
						
					}
					
					
					$contenido=$contenido.'</td></tr>
					<tr><td>SOCIOS </td><td style="font-size:12PX"> <table class="stabla">
                    <tr>
                        <th style="width:50%; padding:2px;">NIT</th>
                        <th style="width:50%; padding:2px;">NOMBRE SOCIO</th>
                    </tr>';
		  
		  
		              $rspta = $Consulta->socios($rspta['IDClinente']);
		              while ($reg=$rspta->fetch_object()){
					  	 $contenido=$contenido.'<tr>
                        <td style="padding:2px;font-size:12PX">'.$reg->NitSocio.'</td>
                        <td style="padding:2px;font-size:12PX">'.$reg->NombreSocio.'</td>
                        </tr>';
					  }
		  
		              $contenido=$contenido.'</table></td></tr>';
		              echo json_encode(array("contenido"=>$contenido, "fechas"=>$fechainicail." / ".$fechafinal,"promedio"=>$op,"diast"=>$diastotales,"diastras"=>$diastrancurridos));
          
          
        
      } catch (\Throwable $th) {
        echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
      }
      break;
		
		
	  case 'consulta2':
      try {
		  
		  if($idproyecto!=0){
			 
			  
		  $listaid="";	  
		  $minutosoperativosG=0;
		  $minutosoperativosA=0;	
		  $minutosoperativosO=0;
		  $cantVehiculos=0;
		  $cantVnovedades=0;
		  $siniestros=0;	
		  $correctivos=0;	  
		  $preventivos=0;
		  $nombrepronovedades="";
			  
			 
		  $where_condition = array('Idproyctos'=>$idproyecto);//$_GET["estado"]);   
          $rspta2 = $dash->mostrar('Proyectos', $where_condition);  
		  $nompro=$rspta2['nombreProyecto'];   	  
		   
           $rspta = $Consulta->Flotaactivos($idproyecto,$fechafinal);
		   while ($reg=$rspta->fetch_object()){
			$cantVehiculos=$cantVehiculos+1;   
			if($reg->FECHA_INICIO_DIS<$fechainicial){
			if($fechainicial==$fechafinal){
			$firstDate  = new DateTime($fechainicial);
            $secondDate = new DateTime($fechafinal);
			$diastotales=1;	
			}else{
			$firstDate  = new DateTime($fechainicial);
            $secondDate = new DateTime($fechafinal);
			$intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 	
			}
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}	
				
			}else{
			$firstDate  = new DateTime($reg->FECHA_INICIO_DIS);
            $secondDate = new DateTime($fechafinal);
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}	
			}     
		  }
			  
		  $rspta = $Consulta->Flota2($idproyecto,$fechainicial,$fechafinal);
		   while ($reg=$rspta->fetch_object()){
			$cantVehiculos=$cantVehiculos+1;   
			$firstDate  = new DateTime($reg->FECHA_INICIO_DIS);
			if($reg->FECHA_FIN_DIS>$fechafinal){
			$secondDate = new DateTime($fechafinal);	
			}else{
			 $secondDate = new DateTime($reg->FECHA_FIN_DIS);	
			}   
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}   
			  
		  }	 
			  
		  $rspta = $Consulta->Flota3($idproyecto,$fechainicial,$fechafinal);
		   while ($reg=$rspta->fetch_object()){
			$cantVehiculos=$cantVehiculos+1;     
			$firstDate  = new DateTime($fechainicial);
			if($reg->FECHA_FIN_DIS>$fechafinal){
			$secondDate = new DateTime($fechafinal);	
			}else{
			 $secondDate = new DateTime($reg->FECHA_FIN_DIS);	
			}    
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);	
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}   
			  
		  }	 	  	
		  
		  $minutosInoperativos=0;
		  $minutosInoperativosO=0;
		  $minutosInoperativosA=0;
		  	  
		  $rspta = $Consulta->minutosNovedades($idproyecto,$fechainicial,$fechafinal);
		  while ($reg=$rspta->fetch_object()){	  
		  $listaid=$listaid.$reg->ID_NOVEDAD.";";
		  $nombrepronovedades=$nombrepronovedades.$nompro.";";	  
		  $cantVnovedades=$cantVnovedades+1;	  
		  if($reg->TIPO_NOVEDAD=="SINIESTRO"){
		  $siniestros=$siniestros+1;  
		  }else if($reg->TIPO_NOVEDAD=="PREVENTIVO"){
			  $preventivos=$preventivos+1;
		  } else if($reg->TIPO_NOVEDAD=="CORRECTIVO"){
			  $correctivos=$correctivos+1;
		  }  
			  
		  if($reg->FECHA_HORA_INICIO<$reg->FECHA_INICIO_DIS){
			  
			 if($reg->FECHA_INICIO_DIS<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_INICIO_DIS.' 00:00:00')); 
			 } 
	
			  if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			} 
             
		    $horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}   
		  }else{
			 if($reg->FECHA_HORA_INICIO<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_INICIO)); 
			 }          
            if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			}
            
			$horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}    
		  }	   
			  
		  }
		
		  $rspta = $Consulta->minutosNovedades2($idproyecto,$fechainicial,$fechafinal);
		  while ($reg=$rspta->fetch_object()){	  
		  $listaid=$listaid.$reg->ID_NOVEDAD.";";
		  $nombrepronovedades=$nombrepronovedades.$nompro.";";	 	  
		  $cantVnovedades=$cantVnovedades+1;	  
		  if($reg->TIPO_NOVEDAD=="SINIESTRO"){
		  $siniestros=$siniestros+1;  
		  }else if($reg->TIPO_NOVEDAD=="PREVENTIVO"){
			  $preventivos=$preventivos+1;
		  } else if($reg->TIPO_NOVEDAD=="CORRECTIVO"){
			  $correctivos=$correctivos+1;
		  } 	  
		  if($reg->FECHA_HORA_INICIO<$reg->FECHA_INICIO_DIS){
			  
			 if($reg->FECHA_INICIO_DIS<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_INICIO_DIS.' 00:00:00')); 
			 } 
	
			  if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			} 
             
		    $horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}   
		  }else{
			 if($reg->FECHA_HORA_INICIO<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_INICIO)); 
			 }          
            if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			}
            
			$horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}    
		  }	   
			  
		  }
		  $optotal=0;
		  $optotalA=0;
		  $optotalO=0;	  
		  $opreal=$minutosoperativosG-$minutosInoperativos;
		  if($opreal>0){
		  $optotal=($opreal/$minutosoperativosG);
		    
		  }	  
		  $opreal=$minutosoperativosA-$minutosInoperativosA;
		  if($opreal>0){	  
		  $optotalA=($opreal/$minutosoperativosA);
		  }
		  $opreal=$minutosoperativosO-$minutosInoperativosO;
		  if($opreal>0){	  
		  $optotalO=($opreal/$minutosoperativosO);	  
		  }
		  if($optotal<0){
			 $optotal=0; 
		  }
		  if($optotalA<0){
			 $optotalA=0; 
		  }
		  if($optotalO<0){
			 $optotalO=0; 
		  }	  
		  $listaid=$listaid."0";
		  $nombrepronovedades=$nombrepronovedades."0";	 	  
		   echo json_encode(array("promediototal"=>$optotal,"promedioO"=>$optotalO,"PromedioA"=>$optotalA,"listas"=>$listaid,"listprnovedades"=>$nombrepronovedades,"cantidadvh"=>$cantVehiculos,"prtoyecton"=>$nompro,"cantidadno"=>$cantVnovedades,"siniestro"=>$siniestros,"preventivo"=>$preventivos,"correctivo"=>$correctivos));
			  
			  
		  }else{//validacion todos --------------------------------------------------------------------------------------------------------------------------------
			  
			  
			 
			$listaid="";
			$nombrepn="";  
			$optotal=0;
		    $optotalA=0;
		    $optotalO=0;	
			$totalp="";
			$oprativop="";
			$adminp="";
			$proyectosn="";
			$proyectosntO=""; 
			$proyectosntA="";   
			$canvh="";
			$canno="";
			$siniestrol="";
			$correctivol="";
			$preventivol="";
			
			$nombresoplis="";
			$nombresadlis="";
			$listotalinoad="";
			$listotalinoop="";  
			  			  
			$minutosoperativosGT=0;
		    $minutosoperativosAT=0;	
		    $minutosoperativosOT=0;  
			$minutosInoperativosT=0;
		    $minutosInoperativosOT=0;
		    $minutosInoperativosAT=0;  
			
			$minutosInoperativosbackup="";  
			$minutosInoperativosalquilado="";    
			$minutosInoperativosnorequiere="";
			$minutosInoperativosresdistribucion=""; 
			$minutosInoperativossinreemplazo=""; 
			$minutosInoperativosGeneral="";   
			  
			$where_condition = array('estadoProyecto'=>1);//$_GET["estado"]);   
            $rspta2 = $dash->validar('Proyectos', $where_condition);  
            while ($reg2=$rspta2->fetch_object())//mientras exista objeto en la respuesta
			{  
		   $minutosoperativosG=0;
		   $minutosoperativosA=0;	
		   $minutosoperativosO=0;
		   $cantVehiculos=0;
		   $cantVnovedades=0;
		   $siniestros=0;	
		   $correctivos=0;	  
		   $preventivos=0;			
				
		   $idproyecto=$reg2->Idproyctos;
						
          $rspta = $Consulta->Flotaactivos($idproyecto,$fechafinal);
		   while ($reg=$rspta->fetch_object()){
			$cantVehiculos=$cantVehiculos+1;    
			if($reg->FECHA_INICIO_DIS<$fechainicial){
			if($fechainicial==$fechafinal){
			$firstDate  = new DateTime($fechainicial);
            $secondDate = new DateTime($fechafinal);
			$diastotales=1;	
			}else{
			$firstDate  = new DateTime($fechainicial);
            $secondDate = new DateTime($fechafinal);
			$intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 	
			}	
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}	
				
			}else{
			$firstDate  = new DateTime($reg->FECHA_INICIO_DIS);
            $secondDate = new DateTime($fechafinal);
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}	
			}     
		  }
			  
		  $rspta = $Consulta->Flota2($idproyecto,$fechainicial,$fechafinal);
		   while ($reg=$rspta->fetch_object()){
			$cantVehiculos=$cantVehiculos+1;    
			$firstDate  = new DateTime($reg->FECHA_INICIO_DIS);
			if($reg->FECHA_FIN_DIS>$fechafinal){
			$secondDate = new DateTime($fechafinal);	
			}else{
			 $secondDate = new DateTime($reg->FECHA_FIN_DIS);	
			}   
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}   
			  
		  }	 
			  
		  $rspta = $Consulta->Flota3($idproyecto,$fechainicial,$fechafinal);
		   while ($reg=$rspta->fetch_object()){
			$cantVehiculos=$cantVehiculos+1;    
			$firstDate  = new DateTime($fechainicial);
			if($reg->FECHA_FIN_DIS>$fechafinal){
			$secondDate = new DateTime($fechafinal);	
			}else{
			 $secondDate = new DateTime($reg->FECHA_FIN_DIS);	
			}    
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);	
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}   
			  
		  }	
		
		  if($cantVehiculos>0){
		
		  $nombreproyectoV=$reg2->nombreProyecto;		
		  if($proyectosn==""){
			$proyectosn=$reg2->nombreProyecto;  
		  }else{
			$proyectosn=$proyectosn.";".$reg2->nombreProyecto;   
		  }	  
			  
			  
		  $minutosInoperativos=0;
		  $minutosInoperativosO=0;
		  $minutosInoperativosA=0;
			  
		  $alquilados=0;
		  $backup=0;
		  $norequiere=0;
		  $redistribucion=0;	
		  $sinreemplazo=0;		  
		  	  
		  $rspta = $Consulta->minutosNovedades($idproyecto,$fechainicial,$fechafinal);
		  while ($reg=$rspta->fetch_object()){
		  $listaid=$listaid.$reg->ID_NOVEDAD.";";
		  $nombrepn=$nombrepn.$nombreproyectoV.";";	  
		  $cantVnovedades=$cantVnovedades+1;	  
		  if($reg->TIPO_NOVEDAD=="SINIESTRO"){
		  $siniestros=$siniestros+1;  
		  }else if($reg->TIPO_NOVEDAD=="PREVENTIVO"){
			  $preventivos=$preventivos+1;
		  } else if($reg->TIPO_NOVEDAD=="CORRECTIVO"){
			  $correctivos=$correctivos+1;
		  } 	  
		   if($reg->FECHA_HORA_INICIO<$reg->FECHA_INICIO_DIS){
			  
			 if($reg->FECHA_INICIO_DIS<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_INICIO_DIS.' 00:00:00')); 
			 } 
	
			  if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			} 
             
		    $horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}  
			  
			switch($reg->CONTIGENCIA){
				case "BACKUP":
				$backup=$backup+$horas;	
			 break;	
				case "VEHICULO ALQUILADO":
				$alquilados=$alquilados+$horas;		
			 break;	
				case "NO REQUIERE":
				$norequiere=$norequiere+$horas;		
			 break;	
				case "REDISTRIBUCIÓN":
				$redistribucion=$redistribucion+$horas;		
			 break;	
				case "SIN REEMPLAZO":
				$sinreemplazo=$sinreemplazo+$horas;		
			 break;			
			}   
			   
		  }else{
			 if($reg->FECHA_HORA_INICIO<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_INICIO)); 
			 }          
            if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			}
            
			$horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}    
			 
			switch($reg->CONTIGENCIA){
				case "BACKUP":
				$backup=$backup+$horas;	
			 break;	
				case "VEHICULO ALQUILADO":
				$alquilados=$alquilados+$horas;		
			 break;	
				case "NO REQUIERE":
				$norequiere=$norequiere+$horas;		
			 break;	
				case "REDISTRIBUCIÓN":
				$redistribucion=$redistribucion+$horas;		
			 break;	
				case "SIN REEMPLAZO":
				$sinreemplazo=$sinreemplazo+$horas;		
			 break;			
			}    
			   
		  } 
			  
		  }
		
		  $rspta = $Consulta->minutosNovedades2($idproyecto,$fechainicial,$fechafinal);
		  while ($reg=$rspta->fetch_object()){
		  $listaid=$listaid.$reg->ID_NOVEDAD.";";
		  $nombrepn=$nombrepn.$nombreproyectoV.";";		  
		  $cantVnovedades=$cantVnovedades+1;	  
		  if($reg->TIPO_NOVEDAD=="SINIESTRO"){
		  $siniestros=$siniestros+1;  
		  }else if($reg->TIPO_NOVEDAD=="PREVENTIVO"){
			  $preventivos=$preventivos+1;
		  } else if($reg->TIPO_NOVEDAD=="CORRECTIVO"){
			  $correctivos=$correctivos+1;
		  } 	  
		  if($reg->FECHA_HORA_INICIO<$reg->FECHA_INICIO_DIS){
			  
			 if($reg->FECHA_INICIO_DIS<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_INICIO_DIS.' 00:00:00')); 
			 } 
	
			  if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			} 
             
		    $horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}   
			
			switch($reg->CONTIGENCIA){
				case "BACKUP":
				$backup=$backup+$horas;	
			 break;	
				case "VEHICULO ALQUILADO":
				$alquilados=$alquilados+$horas;		
			 break;	
				case "NO REQUIERE":
				$norequiere=$norequiere+$horas;		
			 break;	
				case "REDISTRIBUCIÓN":
				$redistribucion=$redistribucion+$horas;		
			 break;	
				case "SIN REEMPLAZO":
				$sinreemplazo=$sinreemplazo+$horas;		
			 break;			
			}   
			  
		  }else{
			 if($reg->FECHA_HORA_INICIO<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_INICIO)); 
			 }          
            if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			}
            
			$horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}
			  
			switch($reg->CONTIGENCIA){
				case "BACKUP":
				$backup=$backup+$horas;	
			 break;	
				case "VEHICULO ALQUILADO":
				$alquilados=$alquilados+$horas;		
			 break;	
				case "NO REQUIERE":
				$norequiere=$norequiere+$horas;		
			 break;	
				case "REDISTRIBUCIÓN":
				$redistribucion=$redistribucion+$horas;		
			 break;	
				case "SIN REEMPLAZO":
				$sinreemplazo=$sinreemplazo+$horas;		
			 break;			
			}   
			  
		  }	  
			  
		  }  
			
		  $minutosoperativosGT=$minutosoperativosGT+$minutosoperativosG;
		  $minutosoperativosAT=$minutosoperativosAT+$minutosoperativosA;
		  $minutosoperativosOT=$minutosoperativosOT+$minutosoperativosO;
		
		  $minutosInoperativosT=$minutosInoperativosT+$minutosInoperativos;
		  $minutosInoperativosAT=$minutosInoperativosAT+$minutosInoperativosA;
		  $minutosInoperativosOT=$minutosInoperativosOT+$minutosInoperativosO;
		
		  $optotal=0;
		  $optotalA=0;
		  $optotalO=0;
			  
		  $optotalalquiler=0;
		  $optotalbskup=0;
		  $optotalnorquiere=0;
		  $optotalredistribucion=0;	 
		  $optotalsinreemplazo=0;	  
				
		  $opreal=$minutosoperativosG-$minutosInoperativos;
		  if($opreal>0){
		  $optotal=($opreal/$minutosoperativosG);
		    
		  }	  
		  $opreal=$minutosoperativosA-$minutosInoperativosA;
		  if($opreal>0){	  
		  $optotalA=($opreal/$minutosoperativosA);
		  }
		  $opreal=$minutosoperativosO-$minutosInoperativosO;
		  if($opreal>0){	  
		  $optotalO=($opreal/$minutosoperativosO);	  
		  } 
			  
		  if($optotal<0){
			 $optotal=0; 
		  }
		  if($optotalA<0){
			 $optotalA=0; 
		  }
		  if($optotalO<0){
			 $optotalO=0; 
		  }
				
		  if($totalp===""){ 
			if($optotal<0){
			$totalp=0;	
			}else{
			$totalp=truncar($optotal*100,2);	
			}    
		  }else{
			if($optotal<0){
			$totalp=$totalp.";0"; 
			}else{
			$totalp=$totalp.";".truncar($optotal*100,2); 	
			}   
		  }
			   
			  
		  if($minutosoperativosO>0){
			  
		   if($proyectosntO===""){
			$proyectosntO=$nombreproyectoV;  
		  }else{
			$proyectosntO=$proyectosntO.";".$nombreproyectoV;   
		  }	 	  
			  
		  if($oprativop===""){
			if($optotalO<=0){
			$oprativop=0;	
			}else{
			$oprativop=truncar($optotalO*100,0);	
			}   
			  
		  }else{
			if($optotalO<=0){
			$oprativop=$oprativop.";0";   	
			}else{
			$oprativop=$oprativop.";".truncar($optotalO*100,0);   
			}   
		  }
			  
		  if($optotalO!=1){
		  if($nombresoplis===""){
			$nombresoplis=$nombreproyectoV;  
		  }else{
			$nombresoplis=$nombresoplis.";".$nombreproyectoV;   
		  }
		
		  if($optotalO<=0){
			  
		  if($listotalinoop===""){
			$listotalinoop="100";  
		  }else{
			$listotalinoop=$listotalinoop.";100";   
		  }	  
			  
		  }else{
			  
		  if($listotalinoop===""){
		  $listotalinoop=truncar((1-$optotalO)*100,0); 
		  }else{
		  $listotalinoop=$listotalinoop.";".truncar((1-$optotalO)*100,0);   
		  }		  
			  
		  }  
			  
		  }	  
			  
		  } 
		
		  if($minutosoperativosA>0){
		   
		     if($proyectosntA===""){
			$proyectosntA=$nombreproyectoV;  
		  }else{
			$proyectosntA=$proyectosntA.";".$nombreproyectoV;   
		  }		  
			  
		   if($adminp===""){
			if($optotalA<=0){
			$adminp=0;	
			}else{
			$adminp=truncar($optotalO*100,0);	
			}   
		  }else{
			if($optotalA<=0){
			$adminp=$adminp.";0";   	
			}else{
			$adminp=$adminp.";".truncar($optotalA*100,0);   
			}       
		  }	 
			  
		
		  if($optotalA!=1){
		  if($nombresadlis===""){
			$nombresadlis=$nombreproyectoV;  
		  }else{
			$nombresadlis=$nombresadlis.";".$nombreproyectoV;   
		  }
		
		  if($optotalA<=0){
			  
		  if($listotalinoad===""){
			$listotalinoad="100";  
		  }else{
			$listotalinoad=$listotalinoad.";100";   
		  }	  
			  
		  }else{
			  
		  if($listotalinoad===""){
		  $listotalinoad=truncar((1-$optotalA)*100,0);     
		  }else{
		  $listotalinoad=$listotalinoad.";".truncar((1-$optotalA)*100,0);   
		  }		  
			  
		  }  
			  
		  }	 	  
		  	  
		  }
			  	  
		  $optotalalquiler=($alquilados/$minutosoperativosG);	
		  $optotalbskup=($backup/$minutosoperativosG);	
		  $optotalnorquiere=($norequiere/$minutosoperativosG);	
		  $optotalredistribucion=($redistribucion/$minutosoperativosG);	
		  $optotalsinreemplazo=($sinreemplazo/$minutosoperativosG);	
		  $optotalinoperatividadgeneral=($minutosInoperativos/$minutosoperativosG);	
			  
			  
			  
			  
		 if($minutosInoperativosbackup===""){
		 if($optotalbskup>1){
		 $minutosInoperativosbackup=100;	 
		 }else{
		  $minutosInoperativosbackup=truncar(($optotalbskup*100),2); 	 
		 }	 	 
		 }else{
		 if($optotalbskup>1){
		 $minutosInoperativosbackup=$minutosInoperativosbackup.";100";	 
		 }else{
		  $minutosInoperativosbackup=$minutosInoperativosbackup.";".truncar(($optotalbskup*100),2);  	 
		 }	 
		 }	 
			  
		 if($minutosInoperativosalquilado===""){
		 if($optotalalquiler>1){
		  $minutosInoperativosalquilado=100;	 
		 }else{
		   $minutosInoperativosalquilado=truncar(($optotalalquiler*100),2); 		 
		 }	 
			  
		 }else{
		 if($optotalalquiler>1){
		   $minutosInoperativosalquilado=$minutosInoperativosalquilado.";100";  	 
		 }else{
		    $minutosInoperativosalquilado=$minutosInoperativosalquilado.";".truncar(($optotalalquiler*100),2);  	 
		 }		 
		 }	 
		
		 if($minutosInoperativosnorequiere===""){
		 if($optotalnorquiere>1){
		  $minutosInoperativosnorequiere=100; 	 
		 }else{
		   $minutosInoperativosnorequiere=truncar(($optotalnorquiere*100),2); 	 	 
		 }	  
		 }else{
		 if($optotalnorquiere>1){
		  $minutosInoperativosnorequiere=$minutosInoperativosnorequiere.";100";   	 
		 }else{
		  $minutosInoperativosnorequiere=$minutosInoperativosnorequiere.";".truncar(($optotalnorquiere*100),2);   	 	 
		 }	
			
		 }	 
			  
		 if($minutosInoperativosresdistribucion===""){
		 if($optotalredistribucion>1){
		   $minutosInoperativosresdistribucion=100;  
		 }else{
		    $minutosInoperativosresdistribucion=truncar(($optotalredistribucion*100),2);  	 	 
		 }		 	 
		 }else{
		 if($optotalredistribucion>1){
		    $minutosInoperativosresdistribucion=$minutosInoperativosresdistribucion.";100";  
		 }else{
		    $minutosInoperativosresdistribucion=$minutosInoperativosresdistribucion.";".truncar(($optotalredistribucion*100),2); 	 	 
		 }	 
		 }	 
			  
	     if($minutosInoperativossinreemplazo===""){
		 if($optotalsinreemplazo>1){
		   $minutosInoperativossinreemplazo=100;  
		 }else{
		    $minutosInoperativossinreemplazo=truncar(($optotalsinreemplazo*100),2);	 	 
		 }	  	 
		 }else{
		 if($optotalsinreemplazo>1){
		   $minutosInoperativossinreemplazo=$minutosInoperativossinreemplazo.";100"; 
		 }else{
		   $minutosInoperativossinreemplazo=$minutosInoperativossinreemplazo.";".truncar(($optotalsinreemplazo*100),2);  	 
		 }	  
		 }	 
			  
		 if($minutosInoperativosGeneral===""){
		 if($optotalinoperatividadgeneral>1){
		  $minutosInoperativosGeneral=100; 
		 }else{
		    $minutosInoperativosGeneral=truncar(($optotalinoperatividadgeneral*100),2);	 	 
		 }		 	 
		 }else{
		 if($optotalinoperatividadgeneral>1){
		  $minutosInoperativosGeneral=$minutosInoperativosGeneral.";100";
		 }else{
		  $minutosInoperativosGeneral=$minutosInoperativosGeneral.";".truncar(($optotalinoperatividadgeneral*100),2);	 
		 }		   
		 }		  
			  
		
		  $canvh=$canvh.";".$cantVehiculos;	     
		  $canno=$canno.";".$cantVnovedades;	     
		  $siniestrol=$siniestrol.";".$siniestros;	     
		  $correctivol=$correctivol.";".$correctivos;	    
		  $preventivol=$preventivol.";".$preventivos;		  
			  
			  
		  }		
				
		}
			  
		  $opreal=$minutosoperativosGT-$minutosInoperativosT;
		  if($opreal>0){
		  $optotal=($opreal/$minutosoperativosGT);
		  }	  
		  $opreal=$minutosoperativosAT-$minutosInoperativosAT;
		  if($opreal>0){	  
		  $optotalA=($opreal/$minutosoperativosAT);
		  }
		  $opreal=$minutosoperativosOT-$minutosInoperativosOT;
		  if($opreal>0){	  
		  $optotalO=($opreal/$minutosoperativosOT);	  
		  }
		  if($optotal<0){
			 $optotal=0; 
		  }
		  if($optotalA<0){
			 $optotalA=0; 
		  }
		  if($optotalO<0){
			 $optotalO=0; 
		  }
			  
			  
		   $listaid=$listaid."0";
		   $nombrepn=$nombrepn."0";		
		   $listotalinoop=$listotalinoop.";0";	
		   $listotalinoad=$listotalinoad.";0";	   
		   echo json_encode(array("promediototal"=>$optotal,"promedioO"=>$optotalO,"PromedioA"=>$optotalA,"listas"=>$listaid,"listprnovedades"=>$nombrepn,"nombrep"=>$proyectosn,"listatotal"=>$totalp,"listaOPnom"=>$proyectosntO,"listaop"=>$oprativop,"listaAnom"=>$proyectosntA,"listaA"=>$adminp,"cantidadvh"=>substr($canvh,1),"cantidadno"=>substr($canno,1),"siniestro"=>substr($siniestrol,1),"preventivo"=>substr($preventivol,1),"correctivo"=>substr($correctivol,1),"proyecyosinoOP"=>$nombresoplis,"proyecyosinoAD"=>$nombresadlis,"totalinoOP"=>$listotalinoop,"totalinoAD"=>$listotalinoad,"inoperatividadG"=>$minutosInoperativosGeneral,"alquiler"=>$minutosInoperativosalquilado,"backup"=>$minutosInoperativosbackup,"redistribucion"=>$minutosInoperativosresdistribucion,"norequiere"=>$minutosInoperativosnorequiere,"sinreemplazo"=>$minutosInoperativossinreemplazo));		  
			  
		  }
          
		  
          
          
        
      } catch (\Throwable $th) {
        echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
      }
      break;
		
	//------------------------------------------------------------------por mes-------------------------------------------------	
	case 'consultaMes':
		
      try {
		  
		 if($idproyecto!=0){
			  
		 $mesinicial=1;	  
		 $mesesnombre=array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		 $listanomes="";
		 $listaop="";
		 $listaad="";
		 $listfechas="";	  
		 if($ano=="2024"){
		 $mesinicial=6;	 
		 }	  	  
		 for($mesi=$mesinicial;$mesi<=12;$mesi++){
		 $fechainicial=date("Y-m-d",strtotime($ano."-".$mesi."-01"));  	 
		 $fechaformat = new DateTime("$ano-$mesi-01");	 
		 $fechaformat->modify('last day of this month');		 
		 $fechafinal=$fechaformat->format('Y-m-d');	
			 
		if($fechainicial<=$fechaactual){
		 if($listanomes==""){
		 $listanomes=$mesesnombre[($mesi-1)];
		 }else{
		  $listanomes=$listanomes.";".$mesesnombre[($mesi-1)];	 
		 }	 
		 
		  $listaid="";	  
		  $minutosoperativosG=0;
		  $minutosoperativosA=0;	
		  $minutosoperativosO=0;
		  $cantVehiculos=0;
		  $cantVnovedades=0;
		  $siniestros=0;	
		  $correctivos=0;	  
		  $preventivos=0;
		  $nombrepronovedades="";
			  
			 
		  $where_condition = array('Idproyctos'=>$idproyecto);//$_GET["estado"]);   
          $rspta2 = $dash->mostrar('Proyectos', $where_condition);  
		  $nompro=$rspta2['nombreProyecto'];   	  
		   
           $rspta = $Consulta->Flotaactivos($idproyecto,$fechafinal);
		   while ($reg=$rspta->fetch_object()){
			$cantVehiculos=$cantVehiculos+1;   
			if($reg->FECHA_INICIO_DIS<$fechainicial){
			$firstDate  = new DateTime($fechainicial);
            $secondDate = new DateTime($fechafinal);
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}	
				
			}else{
			$firstDate  = new DateTime($reg->FECHA_INICIO_DIS);
            $secondDate = new DateTime($fechafinal);
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}	
			}     
		  }
			  
		  $rspta = $Consulta->Flota2($idproyecto,$fechainicial,$fechafinal);
		   while ($reg=$rspta->fetch_object()){
			$cantVehiculos=$cantVehiculos+1;   
			$firstDate  = new DateTime($reg->FECHA_INICIO_DIS);
			if($reg->FECHA_FIN_DIS>$fechafinal){
			$secondDate = new DateTime($fechafinal);	
			}else{
			 $secondDate = new DateTime($reg->FECHA_FIN_DIS);	
			}   
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}   
			  
		  }	 
			  
		  $rspta = $Consulta->Flota3($idproyecto,$fechainicial,$fechafinal);
		   while ($reg=$rspta->fetch_object()){
			$cantVehiculos=$cantVehiculos+1;     
			$firstDate  = new DateTime($fechainicial);
			if($reg->FECHA_FIN_DIS>$fechafinal){
			$secondDate = new DateTime($fechafinal);	
			}else{
			 $secondDate = new DateTime($reg->FECHA_FIN_DIS);	
			}    
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);	
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}   
			  
		  }	 	  	
		  
		  $minutosInoperativos=0;
		  $minutosInoperativosO=0;
		  $minutosInoperativosA=0;
		  	  
		  $rspta = $Consulta->minutosNovedades($idproyecto,$fechainicial,$fechafinal);
		  while ($reg=$rspta->fetch_object()){	  
		  $listaid=$listaid.$reg->ID_NOVEDAD.";";
		  $nombrepronovedades=$nombrepronovedades.$nompro.";";	  
		  $cantVnovedades=$cantVnovedades+1;	  
		  if($reg->TIPO_NOVEDAD=="SINIESTRO"){
		  $siniestros=$siniestros+1;  
		  }else if($reg->TIPO_NOVEDAD=="PREVENTIVO"){
			  $preventivos=$preventivos+1;
		  } else if($reg->TIPO_NOVEDAD=="CORRECTIVO"){
			  $correctivos=$correctivos+1;
		  }  
			  
		  if($reg->FECHA_HORA_INICIO<$reg->FECHA_INICIO_DIS){
			  
			 if($reg->FECHA_INICIO_DIS<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_INICIO_DIS.' 00:00:00')); 
			 } 
	
			  if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			} 
             
		    $horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}   
		  }else{
			 if($reg->FECHA_HORA_INICIO<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_INICIO)); 
			 }          
            if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			}
            
			$horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}    
		  }	   
			  
		  }
		
		  $rspta = $Consulta->minutosNovedades2($idproyecto,$fechainicial,$fechafinal);
		  while ($reg=$rspta->fetch_object()){	  
		  $listaid=$listaid.$reg->ID_NOVEDAD.";";
		  $nombrepronovedades=$nombrepronovedades.$nompro.";";	 	  
		  $cantVnovedades=$cantVnovedades+1;	  
		  if($reg->TIPO_NOVEDAD=="SINIESTRO"){
		  $siniestros=$siniestros+1;  
		  }else if($reg->TIPO_NOVEDAD=="PREVENTIVO"){
			  $preventivos=$preventivos+1;
		  } else if($reg->TIPO_NOVEDAD=="CORRECTIVO"){
			  $correctivos=$correctivos+1;
		  } 	  
		  if($reg->FECHA_HORA_INICIO<$reg->FECHA_INICIO_DIS){
			  
			 if($reg->FECHA_INICIO_DIS<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_INICIO_DIS.' 00:00:00')); 
			 } 
	
			  if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			} 
             
		    $horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}   
		  }else{
			 if($reg->FECHA_HORA_INICIO<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_INICIO)); 
			 }          
            if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			}
            
			$horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}    
		  }	   
			  
		  }
		  $optotal=0;
		  $optotalA=0;
		  $optotalO=0;	  
		  $opreal=$minutosoperativosG-$minutosInoperativos;
		  if($opreal>0){
		  $optotal=($opreal/$minutosoperativosG);
		    
		  }	  
		  $opreal=$minutosoperativosA-$minutosInoperativosA;
		  if($opreal>0){	  
		  $optotalA=($opreal/$minutosoperativosA);
		  }
		  $opreal=$minutosoperativosO-$minutosInoperativosO;
		  if($opreal>0){	  
		  $optotalO=($opreal/$minutosoperativosO);	  
		  }
		  if($optotal<0){
			 $optotal=0; 
		  }
		  if($optotalA<0){
			 $optotalA=0; 
		  }
		  if($optotalO<0){
			 $optotalO=0; 
		  }	  
		  $listaid=$listaid."0";
		  $nombrepronovedades=$nombrepronovedades."0";	 	 
			 
		 if($listaop===""){
		 $listaop=truncar($optotalO*100,0);
		 }else{
		  $listaop=$listaop.";".truncar($optotalO*100,0);	 
		 }	
			 
		 if($listaad===""){
		 $listaad=truncar($optotalA*100,0);
		 }else{
		  $listaad=$listaad.";".truncar($optotalA*100,0);	 
		 }		 
			 	
			
		}	 
			 
			 
		 }
			  
		 echo json_encode(array("meses"=>$listanomes,"promedioO"=>$listaop,"PromedioA"=>$listaad));
			  
			  
		  }else{//validacion todos --------------------------------------------------------------------------------------------------------------------------------
			  
			  
			 
		 $mesinicial=1;	  
         $mesesnombre=array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		 $listanomes="";
		 $listaop="";
		 $listaad="";	  	
		 if($ano=="2024"){
		 $mesinicial=6;	 
		 }	  	  
		 for($mesi=$mesinicial;$mesi<=12;$mesi++){
			 
		
		    $listaid="";
			$nombrepn="";  
			$optotal=0;
		    $optotalA=0;
		    $optotalO=0;	
			$totalp="";
			$oprativop="";
			$adminp="";
			$proyectosn="";
			$proyectosntO=""; 
			$proyectosntA="";   
			$canvh="";
			$canno="";
			$siniestrol="";
			$correctivol="";
			$preventivol="";  
			  			  
			$minutosoperativosGT=0;
		    $minutosoperativosAT=0;	
		    $minutosoperativosOT=0;  
			$minutosInoperativosT=0;
		    $minutosInoperativosOT=0;
		    $minutosInoperativosAT=0;  	 
			 
		 $fechainicial=date("Y-m-d",strtotime($ano."-".$mesi."-01"));  
		 $fechaformat = new DateTime("$ano-$mesi-01");	 
		 $fechaformat->modify('last day of this month');		 
		 $fechafinal=$fechaformat->format('Y-m-d');	
			 
		if($fechainicial<=$fechaactual){
			
		$where_condition = array('estadoProyecto'=>1);//$_GET["estado"]);   
            $rspta2 = $dash->validar('Proyectos', $where_condition);  
            while ($reg2=$rspta2->fetch_object())//mientras exista objeto en la respuesta
			{  
		   $minutosoperativosG=0;
		   $minutosoperativosA=0;	
		   $minutosoperativosO=0;
		   $cantVehiculos=0;
		   $cantVnovedades=0;
		   $siniestros=0;	
		   $correctivos=0;	  
		   $preventivos=0;		
		   $idproyecto=$reg2->Idproyctos;
						
          $rspta = $Consulta->Flotaactivos($idproyecto,$fechafinal);
		   while ($reg=$rspta->fetch_object()){
			$cantVehiculos=$cantVehiculos+1;    
			if($reg->FECHA_INICIO_DIS<$fechainicial){
			$firstDate  = new DateTime($fechainicial);
            $secondDate = new DateTime($fechafinal);
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}	
				
			}else{
			$firstDate  = new DateTime($reg->FECHA_INICIO_DIS);
            $secondDate = new DateTime($fechafinal);
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}	
			}     
		  }
			  
		  $rspta = $Consulta->Flota2($idproyecto,$fechainicial,$fechafinal);
		   while ($reg=$rspta->fetch_object()){
			$cantVehiculos=$cantVehiculos+1;    
			$firstDate  = new DateTime($reg->FECHA_INICIO_DIS);
			if($reg->FECHA_FIN_DIS>$fechafinal){
			$secondDate = new DateTime($fechafinal);	
			}else{
			 $secondDate = new DateTime($reg->FECHA_FIN_DIS);	
			}   
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}   
			  
		  }	 
			  
		  $rspta = $Consulta->Flota3($idproyecto,$fechainicial,$fechafinal);
		   while ($reg=$rspta->fetch_object()){
			$cantVehiculos=$cantVehiculos+1;    
			$firstDate  = new DateTime($fechainicial);
			if($reg->FECHA_FIN_DIS>$fechafinal){
			$secondDate = new DateTime($fechafinal);	
			}else{
			 $secondDate = new DateTime($reg->FECHA_FIN_DIS);	
			}    
            $intvl2 = $firstDate->diff($secondDate);    
            $diastotales=$intvl2->days; 
			$minutosoperativosG=$minutosoperativosG+(($diastotales*24)*60);	
			if($reg->TIPO_VEH==1){
			$minutosoperativosA=$minutosoperativosA+(($diastotales*24)*60);	
			}else{
			$minutosoperativosO=$minutosoperativosO+(($diastotales*24)*60);		
			}   
			  
		  }	
		
		  if($cantVehiculos>0){
		
		  $nombreproyectoV=$reg2->nombreProyecto;		
		  if($proyectosn==""){
			$proyectosn=$reg2->nombreProyecto;  
		  }else{
			$proyectosn=$proyectosn.";".$reg2->nombreProyecto;   
		  }	  
			  
			  
		  $minutosInoperativos=0;
		  $minutosInoperativosO=0;
		  $minutosInoperativosA=0;
		  	  
		  $rspta = $Consulta->minutosNovedades($idproyecto,$fechainicial,$fechafinal);
		  while ($reg=$rspta->fetch_object()){
		  $listaid=$listaid.$reg->ID_NOVEDAD.";";
		  $nombrepn=$nombrepn.$nombreproyectoV.";";	  
		  $cantVnovedades=$cantVnovedades+1;	  
		  if($reg->TIPO_NOVEDAD=="SINIESTRO"){
		  $siniestros=$siniestros+1;  
		  }else if($reg->TIPO_NOVEDAD=="PREVENTIVO"){
			  $preventivos=$preventivos+1;
		  } else if($reg->TIPO_NOVEDAD=="CORRECTIVO"){
			  $correctivos=$correctivos+1;
		  } 	  
		   if($reg->FECHA_HORA_INICIO<$reg->FECHA_INICIO_DIS){
			  
			 if($reg->FECHA_INICIO_DIS<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_INICIO_DIS.' 00:00:00')); 
			 } 
	
			  if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			} 
             
		    $horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}   
		  }else{
			 if($reg->FECHA_HORA_INICIO<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_INICIO)); 
			 }          
            if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			}
            
			$horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}    
		  } 
			  
		  }
		
		  $rspta = $Consulta->minutosNovedades2($idproyecto,$fechainicial,$fechafinal);
		  while ($reg=$rspta->fetch_object()){
		  $listaid=$listaid.$reg->ID_NOVEDAD.";";
		  $nombrepn=$nombrepn.$nombreproyectoV.";";		  
		  $cantVnovedades=$cantVnovedades+1;	  
		  if($reg->TIPO_NOVEDAD=="SINIESTRO"){
		  $siniestros=$siniestros+1;  
		  }else if($reg->TIPO_NOVEDAD=="PREVENTIVO"){
			  $preventivos=$preventivos+1;
		  } else if($reg->TIPO_NOVEDAD=="CORRECTIVO"){
			  $correctivos=$correctivos+1;
		  } 	  
		  if($reg->FECHA_HORA_INICIO<$reg->FECHA_INICIO_DIS){
			  
			 if($reg->FECHA_INICIO_DIS<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_INICIO_DIS.' 00:00:00')); 
			 } 
	
			  if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			} 
             
		    $horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}   
		  }else{
			 if($reg->FECHA_HORA_INICIO<$fechainicial){
				  $fechai=date("Y/m/d H:i:s",strtotime($fechainicial.' 00:00:00'));
			 }else{
				 $fechai=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_INICIO)); 
			 }          
            if(!empty($reg->FECHA_FIN_DIS) and $reg->FECHA_HORA_FIN<$reg->FECHA_FIN_DIS."23:59:59"){
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}   
			 }else if(!empty($reg->FECHA_FIN_DIS)){
				if($reg->FECHA_FIN_DIS<$fechafinal){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_FIN_DIS));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}
			 }else{
				if($reg->FECHA_HORA_FIN<$fechafinal." 23:59:59"){
				$fechaf=date("Y/m/d H:i:s",strtotime($reg->FECHA_HORA_FIN));  	
				}else{
				 $fechaf=date("Y/m/d H:i:s",strtotime($fechafinal." 23:00:00"));  	
				}  
			}
            
			$horas=minutosTranscurridos($fechai,$fechaf);  
			  
			$minutosInoperativos=$minutosInoperativos+$horas;
			if($reg->TIPO_VEH==1){
			$minutosInoperativosA=$minutosInoperativosA+$horas;
			}else{
			$minutosInoperativosO=$minutosInoperativosO+$horas;		
			}    
		  }	  
			  
		  }  
			
		  $minutosoperativosGT=$minutosoperativosGT+$minutosoperativosG;
		  $minutosoperativosAT=$minutosoperativosAT+$minutosoperativosA;
		  $minutosoperativosOT=$minutosoperativosOT+$minutosoperativosO;
		
		  $minutosInoperativosT=$minutosInoperativosT+$minutosInoperativos;
		  $minutosInoperativosAT=$minutosInoperativosAT+$minutosInoperativosA;
		  $minutosInoperativosOT=$minutosInoperativosOT+$minutosInoperativosO;
		
		  $optotal=0;
		  $optotalA=0;
		  $optotalO=0;	  		
				
		  $opreal=$minutosoperativosG-$minutosInoperativos;
		  if($opreal>0){
		  $optotal=($opreal/$minutosoperativosG);
		    
		  }	  
		  $opreal=$minutosoperativosA-$minutosInoperativosA;
		  if($opreal>0){	  
		  $optotalA=($opreal/$minutosoperativosA);
		  }
		  $opreal=$minutosoperativosO-$minutosInoperativosO;
		  if($opreal>0){	  
		  $optotalO=($opreal/$minutosoperativosO);	  
		  }
		  if($optotal<0){
			 $optotal=0; 
		  }
		  if($optotalA<0){
			 $optotalA=0; 
		  }
		  if($optotalO<0){
			 $optotalO=0; 
		  }
				
		  if($totalp==""){ 
			if($optotal<0){
			$totalp=0;	
			}else{
			$totalp=truncar($optotal*100,0);	
			}    
		  }else{
			if($optotal<0){
			$totalp=$totalp.";0"; 
			}else{
			$totalp=$totalp.";".truncar($optotal*100,0); 	
			}   
		  }	
			  
		  if($minutosoperativosO>0){
			  
		   if($proyectosntO==""){
			$proyectosntO=$nombreproyectoV;  
		  }else{
			$proyectosntO=$proyectosntO.";".$nombreproyectoV;   
		  }	 	  
			  
		  if($oprativop==""){
			if($optotalO<0){
			$oprativop=0;	
			}else{
			$oprativop=truncar($optotalO*100,0);	
			}   
			  
		  }else{
			if($optotalO<0){
			$oprativop=$oprativop.";0";   	
			}else{
			$oprativop=$oprativop.";".truncar($optotalO*100,0);   
			}   
		  }		  
			  
		  } 
		
		  if($minutosoperativosA>0){
		   
		     if($proyectosntA==""){
			$proyectosntA=$nombreproyectoV;  
		  }else{
			$proyectosntA=$proyectosntA.";".$nombreproyectoV;   
		  }		  
			  
		   if($adminp==""){
			if($optotalA<0){
			$adminp=0;	
			}else{
			$adminp=truncar($optotalO*100,0);	
			}   
		  }else{
			if($optotalA<0){
			$adminp=$adminp.";0";   	
			}else{
			$adminp=$adminp.";".truncar($optotalA*100,0);   
			}       
		  }	  
		  	  
		  }
				
				
		 
		  $canvh=$canvh.";".$cantVehiculos;	     
		  $canno=$canno.";".$cantVnovedades;	     
		  $siniestrol=$siniestrol.";".$siniestros;	     
		  $correctivol=$correctivol.";".$correctivos;	    
		  $preventivol=$preventivol.";".$preventivos;		  
			  
			  
		  }		
				
		}
			  
		  $opreal=$minutosoperativosGT-$minutosInoperativosT;
		  if($opreal>0){
		  $optotal=($opreal/$minutosoperativosGT);
		  }	  
		  $opreal=$minutosoperativosAT-$minutosInoperativosAT;
		  if($opreal>0){	  
		  $optotalA=($opreal/$minutosoperativosAT);
		  }
		  $opreal=$minutosoperativosOT-$minutosInoperativosOT;
		  if($opreal>0){	  
		  $optotalO=($opreal/$minutosoperativosOT);	  
		  }
		  if($optotal<0){
			 $optotal=0; 
		  }
		  if($optotalA<0){
			 $optotalA=0; 
		  }
		  if($optotalO<0){
			 $optotalO=0; 
		  }
			 
			 
		 if($listaop==""){
		 $listaop=truncar($optotalO*100,0);
		 }else{
		  $listaop=$listaop.";".truncar($optotalO*100,0);	 
		 }	
			 
		 if($listaad==""){
		 $listaad=truncar($optotalA*100,0);
		 }else{
		  $listaad=$listaad.";".truncar($optotalA*100,0);	 
		 }		 
			 
		 if($listanomes==""){
		 $listanomes=$mesesnombre[($mesi-1)];
		 }else{
		  $listanomes=$listanomes.";".$mesesnombre[($mesi-1)];	 
		 }		 
 	
			
			
		}	 
			 
		 
			 
		 }
			  
		 $listaid=$listaid."0";
		 $nombrepn=$nombrepn."0";		  
		 echo json_encode(array("meses"=>$listanomes,"promedioO"=>$listaop,"PromedioA"=>$listaad));	  	  
		 }
      } catch (\Throwable $th) {
        echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
      }
      break;
		

   case 'listar':
      try {
		  $condicional="";
		  $idproyectob=isset($_GET["proyecto"])?$_GET["proyecto"]:"";
		  $nombrepro=isset($_GET["nombrespr"])?explode(";", $_GET["nombrespr"]):"";
		  $data = Array();
		  if($idproyectob!="0"){
		  $idproyectob=explode(";", $idproyectob);	  
		  for($i=0;$i<(count($idproyectob)-1);$i++){
		
		  $condicional=" NOVEDAD_FLOTA.ID_NOVEDAD=".$idproyectob[$i];  
			  
		  $rspta = $Consulta->listraNovedadesreporte($condicional);
	
          $reg=$rspta->fetch_object();//mientras exista objeto en la respuesta
          
			  
		  $bot='<SPAN title=""><button class="btn btn-light" data-toggle="modal" data-target="#modal-nota" onclick="mostrarcontenido('.$reg->ID_NOVEDAD.')"><i class="fa fa-eye" style=""></i></button></SPAN>';
			  
		  $estado='<SPAN title=""><button class="btn btn-light"><i class="fa fa-car" style="color:green;"></i> </button></SPAN>';	  
		 if($reg->FECHA_HORA_FIN>$fechaactualhora){
		 $estado='<SPAN title="Editar"><button class="btn btn-light"><i class="fa fa-car" style="color:red;"></i></button></SPAN>';	 
		 }
		 $tiempotrans="";	  
		 if(($reg->HORAS_TRANSCURRIDAS/60)<24){
		 $tiempotrans=truncar(($reg->HORAS_TRANSCURRIDAS/60),0)." Horas";	 
		 }else{
		 $tiempotrans=truncar((($reg->HORAS_TRANSCURRIDAS/60)/24),0)." Días"; 
		 }	  
			  
            $data[]=array(
              "0"=>$reg->PLACA_VEH,
              "1"=>$reg->NOM_TIPO_VEHICULO,
			  "2"=>$nombrepro[$i],	
			  "3"=>$reg->TIPO_NOVEDAD,	
			  "4"=>$reg->SISTEMA,		
			  "5"=>$reg->OPERATIVIDAD ,
			  "6"=>$reg->CONTIGENCIA,
			  "7"=>$reg->PLACACONTIGENCIA,		
			  "8"=>$reg->FECHA_HORA_INICIO,
			  "9"=>$reg->FECHA_HORA_FIN,
			  "10"=>$tiempotrans,	
			  "11"=>$estado,	
			  "12"=>$bot,
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
		
     case 'experiencia':
          try {
			  
		   $cliente=isset($_GET["clienteb"])?$_GET["clienteb"]:"";	
		   $condicional="";  
		   $data = Array();  
		   if($cliente=="0"){
		    $condicional="1=1";	   
		   }else{
			  $condicional="contrato.IDClienteContrato=".$cliente; 
		   }	  
            $rspta = $Consulta->listarexperiencia($condicional); 
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
				$bot='<SPAN title="Editar"><button class="btn btn-light" onclick="objetocontrato('."'".$reg->ObjetoContrato."'".')"><i class="fa fa-eye" style=""></i></button></SPAN>';  
			
			          $firstDate  = new DateTime($reg->FechaInicio);
                      $secondDate = new DateTime($reg->fechaFinal);
                      $intvl2 = $firstDate->diff($secondDate);    
                      $diastrancurridos=$intvl2->days; 	
				      $mesesDeDiferencia = ($intvl2->y * 12) + $intvl2->m;
				
             $data[]=array(
             "0"=>$reg->NombreCliente,
             "1"=>$reg->NombreContrato,
			 "2"=>$reg->NombreUen,	 
			 "3"=>$bot,	
			 "4"=>$reg->DEPARTAMENTO,	
			 "5"=>$reg->FORMAEJECUCION ,
			 "6"=>$reg->FechaInicio,
			 "7"=>$reg->fechaFinal,		
			 "8"=>"$".(number_format($reg->ValorTotalContrato,0, '', '.')),
			 "9"=>$diastrancurridos,	
			 "10"=>$mesesDeDiferencia,
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
		
		
		 case 'listarTipoVehiculo':
          try {   
            
			$rspta = $Consulta->TipoVehiculos();
			$tipo="";  
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
              if($tipo===""){
				  $tipo=$reg->NOM_TIPO_VEHICULO;
			  }else{
				  $tipo=$tipo.";".$reg->NOM_TIPO_VEHICULO;
			  }   
            }
			echo json_encode(array("Tipovehiculos"=>$tipo));  
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
    break;
		
		
		case 'listarProyectosCombustible':
          try {   
            
			$rspta = $Consulta->ProyectosCombustible();
			$Proyecto="";  
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
              if($Proyecto===""){
				  $Proyecto=$reg->nombreProyecto;
			  }else{
				  $Proyecto=$Proyecto.";".$reg->nombreProyecto;
			  }   
            }
			echo json_encode(array("NombreProyecto"=>$Proyecto));  
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
    break;
		
		 case 'Combustible':
          try {
			 
		   $nombresMeses = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO","JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");	
			$rendimiento="";
			$servicios="";
			$kilometros="";
			$meseM="";  
			$tipovehiculos= array(); 
			$proyectosR= array();   
			for($i=0;$i<12;$i++){
			$rendimientoM=0;
			$serviciosM=0;
			$kilometrosM=0;	
			$galones=0;	
			$mes=$nombresMeses[$i];	
			$rspta = $Consulta->listarCombustiblegrafica($mes,$ano);
			while ($reg=$rspta->fetch_object()){
			$serviciosM=$serviciosM+$reg->SERVICIOS;	
			$kilometrosM=$kilometrosM+$reg->KILOMETROS;	
			$galones=$galones+$reg->GALONES;		
			}
			if($serviciosM>0 or $kilometrosM>0){
			$rendimientoM=$kilometrosM/$galones;
				
			if($meseM===""){
			$meseM=$mes;	
			}else{
			$meseM=$meseM.";".$mes;	
			}
				
			if($rendimiento===""){
			$rendimiento=truncar($rendimientoM,0);	
			}else{
			$rendimiento=$rendimiento.";".truncar($rendimientoM,0);	
			}
				
			if($servicios===""){
			$servicios=$serviciosM;	
			}else{
			$servicios=$servicios.";".$serviciosM;	
			}
				
			if($kilometros===""){
			$kilometros=$kilometrosM;	
			}else{
			$kilometros=$kilometros.";".$kilometrosM;	
			}
				
			$rspta2 = $Consulta->TipoVehiculos();
            while ($reg2=$rspta2->fetch_object())//mientras exista objeto en la respuesta
            {	
			$rendimientoM=0;
			$kilometrosM=0;	
			$galones=0;		
			$idtipo=$reg2->ID_TIP_VEH;	
			if (!array_key_exists($reg2->NOM_TIPO_VEHICULO, $tipovehiculos)) {
            $tipovehiculos[$reg2->NOM_TIPO_VEHICULO]="";
			}	
			$rspta3 = $Consulta->listarCombustibleTipo($mes,$ano,$idtipo);	
			 while ($reg3=$rspta3->fetch_object())//mientras exista objeto en la respuesta
             {
			 $kilometrosM=$kilometrosM+$reg3->KILOMETROS;	
			 $galones=$galones+$reg3->GALONES;	 
			 }
			
			if($kilometrosM>0){
			$rendimientoM=$kilometrosM/$galones;	
			}
			if($tipovehiculos[$reg2->NOM_TIPO_VEHICULO]===""){
			$tipovehiculos[$reg2->NOM_TIPO_VEHICULO]=truncar($rendimientoM,0);	
			}else{
			$tipovehiculos[$reg2->NOM_TIPO_VEHICULO]=$tipovehiculos[$reg2->NOM_TIPO_VEHICULO].";".truncar($rendimientoM,0);		
			}	
				
			}
				
			$rspta3 = $Consulta->ProyectosCombustible();
            while ($reg3=$rspta3->fetch_object())//mientras exista objeto en la respuesta
            {	
			$rendimientoM=0;
			$kilometrosM=0;	
			$galones=0;		
			$idproyecto=$reg3->Idproyctos;	
			if (!array_key_exists($reg3->nombreProyecto, $proyectosR)) {
            $proyectosR[$reg3->nombreProyecto]="";
			}	
			 $rspta4 = $Consulta->listarCombustibleProyecto($mes,$ano,$idproyecto);	
			 while ($reg4=$rspta4->fetch_object())//mientras exista objeto en la respuesta
             {
			 $kilometrosM=$kilometrosM+$reg4->KILOMETROS;	
			 $galones=$galones+$reg4->GALONES;	 
			 }
			
			if($kilometrosM>0){
			$rendimientoM=$kilometrosM/$galones;	
			}
			if($proyectosR[$reg3->nombreProyecto]===""){
			$proyectosR[$reg3->nombreProyecto]=truncar($rendimientoM,0);	
			}else{
			$proyectosR[$reg3->nombreProyecto]=$proyectosR[$reg3->nombreProyecto].";".truncar($rendimientoM,0);		
			}	
				
			}	
				
		  }
			
	    }  
			  
       echo json_encode(array("meses"=>$meseM,"kilometros"=>$kilometros,"servicios"=>$servicios,"rendimiento"=>$rendimiento,"tipovehiculo"=>$tipovehiculos,"Proyectos"=>$proyectosR));		  
			  
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
    break;	
		
		
		
case 'TI':
          try {
			 
		   $nombresMeses = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO","JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");	
			$Presupuesto="";
			$Gestion="";
			$meseM=""; 
			  	  
		  $where_condition = array("ANIO_CERRADO"=>$ano); 
          $rspta = $dash->listar('meses_cerrados', $where_condition);
			  
		  while($reg=$rspta->fetch_object()){
		 $Numes=$reg->MES_CERRADO-1;	  
		 if($meseM===""){
			$meseM=$nombresMeses[$Numes]; 
		 }else{
			$meseM=$meseM.";".$nombresMeses[$Numes];  
		 }	
			  
		 if($Presupuesto===""){
			$Presupuesto=$reg->VALOR_PRESUPESUTO; 
		 }else{
			$Presupuesto=$Presupuesto.";".$reg->VALOR_PRESUPESUTO;  
		 }
			  
		 if($Gestion===""){
			$Gestion=$reg->VALOR_TOATAL; 
		 }else{
			$Gestion=$Gestion.";".$reg->VALOR_TOATAL;  
		 }	  
			    
		 }
		$Presupuesto=$Presupuesto.";0";
		$Gestion=$Gestion.";0";	  
       echo json_encode(array("meses"=>$meseM,"Presupuesto"=>$Presupuesto,"Gestion"=>$Gestion));		  	  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
    break;	
		
		  case 'listadoflotaCombustible':
          try { 
		  $ano=isset($_GET["anio"])?limpiarCadena($_GET["anio"]):"";	  
		  $data = Array();  
		  $rspta = $Consulta->listarflotaCombustible($ano); 	  
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
			  $data[]=array(	
              "0"=>$reg->PLACA_VEH,
			  "1"=>$reg->NOM_TIPO_VEHICULO,
              "2"=>$reg->nombreProyecto,
			  "3"=>$reg->MES_COMBUSTIBLE,
			  "4"=>number_format($reg->GALONES,0, '', '.'),
			  "5"=>number_format($reg->KILOMETROS,0, '', '.'),	
			  "6"=>number_format($reg->SERVICIOS,0, '', '.'),	
			  "7"=>number_format($reg->CANTIDADPEAJES,0, '', '.'),
			  "8"=>"$".number_format($reg->PEJAE,0, '', '.'),
			  "9"=>"$".number_format($reg->MANTENIMIENTO,0, '', '.'),
			  "10"=>"$".number_format($reg->DINEROCOMBUSTIBLE,0, '', '.'),	
			  "11"=>truncar(($reg->KILOMETROS/$reg->GALONES),0),		  
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

		  case 'listadoflota':
          try { 
		  $data = Array();  
		  $rspta = $Consulta->cantidadflota(); 
		  $cantidad=0;	
		  $cantidadad=0;
		  $cantidadop=0;		  
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
			   $cantidad=$cantidad+$reg->CANTIDAD;
			   $rspta2 = $Consulta->cantidadflotaAD($reg->Idproyctos); 
			   $can=$rspta2["CANTIDADAD"];
			   $canop=$reg->CANTIDAD-$can;
			   $cantidadad=$cantidadad+$can;
			   $cantidadop=$cantidadop+$canop;
			   $data[]=array("nombreProyecto"=>$reg->nombreProyecto,"CANTIDAD"=>$reg->CANTIDAD,"administrativo"=>$can,"operativo"=>$canop);
			  
			  
            }	
		   $data[]=array("nombreProyecto"=>"TOTAL VEHÍCULOS","CANTIDAD"=>$cantidad,"administrativo"=>$cantidadad,"operativo"=>$cantidadop);	  
           echo json_encode($data);  
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
    break;	
		
   case 'select':
          try {   
            
			 $where_condition = array('estadoProyecto'=>1);//$_GET["estado"]);   
           $rspta = $dash->validar('Proyectos', $where_condition); 
			echo "<option value=''>Seleccione..</option>
			<option value='0'>TODOS</option>";    
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
            
			 $where_condition = array('1'=>1);//$_GET["estado"]);   
             $rspta = $dash->validar('clientes', $where_condition); 
			 echo "<option value='0'>TODOS</option>";    
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDClinente'>$reg->NombreCliente</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
    break;	
		
		
   case 'listarvhflota':
      try {
		  
		  $rspta = $Consulta->vehiculosAfectados($fechaactualhora);
          $vehiculos = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          { 
		  $vehiculos[]=$reg->ID_VEHICULO_NOVEDAD;	    
          }
    
          $rspta = $Consulta->listarFlota(1,"null");
          $data = Array();
		  $cons=0;
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          { 
		
		  $estado='<SPAN title=""><button class="btn btn-light"><i class="fa fa-car" style="color:green;"></i> </button></SPAN>';
			  
		  if(in_array($reg->ID_VEHICULOS_DIS,$vehiculos)){
		  $estado='<SPAN title=""><button class="btn btn-light"><i class="fa fa-car" style="color:red;"></i></button></SPAN>';	  
		  }	  
			  
		  $cons=$cons+1;	  
          $data[]=array(
			  "0"=>$cons,	
              "1"=>$reg->PLACA_VEH,
			  "2"=>$estado,
              "3"=>$reg->nombreProyecto,
			  "4"=>$reg->NOM_TIPO_VEHICULO,
			  "5"=>$reg->FECHA_INICIO_DIS,
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
		
		
	case 'NovedadesN':
      try {
		  
		  
		    $where_condition = array('estadoProyecto'=>1);//$_GET["estado"]);   
            $rspta2 = $dash->validar('Proyectos', $where_condition);  
		   $listadoP="";
		   $cerradas="";
		   $talatales="";
		   $listaabier="";
            while ($reg2=$rspta2->fetch_object())//mientras exista objeto en la respuesta
		   {		
		   $idproyecto=$reg2->Idproyctos;
		   $rspta = $Consulta->NovevdadesRegistro($fechainicial,$fechafinal,$idproyecto);
           $cantidadT=0;
		   $canA=0;
		   $canC=0;		
           while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
           {
			   
		   $cantidadT=$cantidadT+1;
		   if($reg->ESTADO_NOVEDAD==1){
			$canA=$canA+1;   
		   }else{
			 $canC=$canC+1;  
		   }
			   
           }
			if($cantidadT>0){
				
			 $listadoP=$listadoP.";".$reg2->nombreProyecto;  
		     	
			 $cerradas=$cerradas.";".$canC;  
				 
			 $listaabier=$listaabier.";".$canA;  
		     		
				
			}			
				
		  }
		 
		  if($listadoP==""){
		  $listadoP="P1";	  
		  $listaabier="0;0";	
		  $cerradas="0;0";	  
		  }else{
		  $listaabier=substr($listaabier, 1);
		  $listadoP=substr($listadoP, 1);
		  $cerradas=substr($cerradas, 1);  
			$listaabier=$listaabier.";0"; 
			$cerradas=$cerradas.";0";  
		  }
		
          echo json_encode(array("proyectos"=>$listadoP,"Cerradas"=>$cerradas,"Abiertas"=>$listaabier));	
        
      } catch (\Throwable $th) {
        echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
      }
      break;
		
	case 'ListarNovedadesN':
      try {
		  
		   $fechainicial=isset($_GET["finicio"])?limpiarCadena($_GET["finicio"]):"";
           $fechafinal=isset($_GET["ffinal"])?limpiarCadena($_GET["ffinal"]):"";

		   $rspta = $Consulta->ListarNovedadesN($fechainicial,$fechafinal);	
		   $data = Array();
           while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
           {    
			$bot='<SPAN title=""><button class="btn btn-light" data-toggle="modal" data-target="#modal-novedades" onclick="mostrarcontenidoNovedades('.$reg->IDNOVEDAD.')"><i class="fa fa-eye" style=""></i></button></SPAN>';  
			 
			$fechaCierre="NOVEDAD ACTIVA";   
			$estado="ABIERTA";   
			if($reg->ESTADO_NOVEDAD==0){
			$fechaCierre=$reg->FECHACIERRE;
			$estado="CERRADA";
			}
			   
		    $data[]=array(
			  "0"=>$reg->TITULONOVEDAD,	
              "1"=>$reg->nombreProyecto,
              "2"=>$reg->NombreUen,
			  "3"=>$reg->FECHAREGISTRO,
			  "4"=>$fechaCierre,
			  "5"=>$estado,
			  "6"=>$bot,	
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