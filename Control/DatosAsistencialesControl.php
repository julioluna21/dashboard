<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";

require '../PHPMailer-master/src/Exception.php';
                require '../PHPMailer-master/src/PHPMailer.php';
                require '../PHPMailer-master/src/SMTP.php';
                use PHPMailer\PHPMailer\PHPMailer;
                use PHPMailer\PHPMailer\Exception;  

$ejecucion = new configuracion();
$Consulta = new consultas();

$id=isset($_POST["idCargue"])?limpiarCadena($_POST["idCargue"]):"";
$fecha=isset($_POST["fechaservicio"])?limpiarCadena($_POST["fechaservicio"]):"";
$base=isset($_POST["centro"])?limpiarCadena($_POST["centro"]):"";
$tipoEvento=isset($_POST["TipoEvento"])?limpiarCadena($_POST["TipoEvento"]):"";
$Prinicial=isset($_POST["Pr"])?limpiarCadena($_POST["Pr"]):"";
$Metros=isset($_POST["Metros"])?limpiarCadena($_POST["Metros"]):"";
$Uf=isset($_POST["Uf"])?limpiarCadena($_POST["Uf"]):"";
$VehuculoServicio=isset($_POST["TipoVehiculo"])?limpiarCadena($_POST["TipoVehiculo"]):"";
$placa=isset($_POST["Placa"])?limpiarCadena($_POST["Placa"]):"";
$prsalida=isset($_POST["Prsalida"])?limpiarCadena($_POST["Prsalida"]):"";
$rnSalida=isset($_POST["RnSalida"])?limpiarCadena($_POST["RnSalida"]):"";
$DatosServicio=isset($_POST["Servicio"])?limpiarCadena($_POST["Servicio"]):"";

$prtraslado=isset($_POST["prtraslado"])?limpiarCadena($_POST["prtraslado"]):"";
$rntraslado=isset($_POST["rutaTraslado"])?limpiarCadena($_POST["rutaTraslado"]):"";
$VehiculoAtendido=isset($_POST["TipoVehiculoAtencion"])?limpiarCadena($_POST["TipoVehiculoAtencion"]):"";
$Categoria=isset($_POST["CategoriaVehiculo"])?limpiarCadena($_POST["CategoriaVehiculo"]):"";
$PersonasHeridas=isset($_POST["Pheridas"])?limpiarCadena($_POST["Pheridas"]):"";
$graves=isset($_POST["Hgraves"])?limpiarCadena($_POST["Hgraves"]):"";
$leves=isset($_POST["Hleves"])?limpiarCadena($_POST["Hleves"]):"";
$ilesos=isset($_POST["Hilesos"])?limpiarCadena($_POST["Hilesos"]):"";
$Fallecidos=isset($_POST["Fallecidos"])?limpiarCadena($_POST["Fallecidos"]):"";
$HoraReporte=isset($_POST["HoraReporte"])?limpiarCadena($_POST["HoraReporte"]):"";
$HoraLLegada=isset($_POST["HoraLLegada"])?limpiarCadena($_POST["HoraLLegada"]):"";
$HoraInicio=isset($_POST["HorainicioT"])?limpiarCadena($_POST["HorainicioT"]):"";
$Horafin=isset($_POST["HoraFinT"])?limpiarCadena($_POST["HoraFinT"]):"";
$Horafinservicio=isset($_POST["Horafinservico"])?limpiarCadena($_POST["Horafinservico"]):"";
$HoraBase=isset($_POST["Horabase"])?limpiarCadena($_POST["Horabase"]):"";

$datos = isset($_POST["info"]) ? json_decode(json_encode($_POST['info']),true) : "";
$estadom=isset($_POST["estadom"])?limpiarCadena($_POST["estadom"]):"";

$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$centro=isset($_GET["centrobus"])?limpiarCadena($_GET["centrobus"]):"";
$fechai=isset($_GET["fechainicial"])?limpiarCadena($_GET["fechainicial"]):"";
$fechaf=isset($_GET["fechafinal"])?limpiarCadena($_GET["fechafinal"]):"";

$detalle =isset($_POST["detalleR"])? json_decode($_POST['detalleR'], true) : "";
$FechaIR=isset($_POST["fechainicalR"])?limpiarCadena($_POST["fechainicalR"]):"";
$FechaFR=isset($_POST["fechafinalR"])?limpiarCadena($_POST["fechafinalR"]):"";
$table_name = "servicio_asistencial";

function correoenvio($mensaje,$asunto){
                         $mail = new PHPMailer();
                         $mail->PluginDir = "phpMailer/";
                         $mail->Mailer = "smtp";
                         $mail->IsSMTP();
                         $mail->SMTPAuth = true;
                         $mail->Host = "p3plmcpnl504321.prod.phx3.secureserver.net";
                         $mail->Port = 465;
                         $mail->Username = "no-reply@regencysa.net";
                         $mail->Password = "Pr0t1nc0315*";
                         $mail->SMTPSecure = "ssl";
                         $mail->From     = 'no-reply@regencysa.net';
                         $mail->FromName = utf8_decode('SISTEMA DASHBOARD');
                         $mail->AddAddress('analista.tecnologia2@regency.com.co');
                         $mail->WordWrap = 200;
                         $mail->IsHTML(true);
                         $mail->Subject  =  utf8_decode($asunto);
                         $mail->Body     =  utf8_decode('
      
      <div style=" width: 45%;
         display: block;
              margin-left: auto;
             margin-right: auto;">
       '.$mensaje.'<br><br>
       
      </div><br>');
                            if($mail->send()){
                             return true;   
                            }else{
                              return false;   
                            }  
}

switch ($_GET["op"]) {
  case 'guardar':
    try {
		
	if (empty($id)) {
		
			 $data_values = array('CENTRO_SERVICIO'=>$base,"FECHA_SERVICIO"=>$fecha,'TIPO_EVENTO_SERVICIO'=>$tipoEvento,
       'PR'=>$Prinicial,'METROS'=>$Metros,'UF'=>$Uf,'TIPO_VEHICULO_SERVICIO'=>$VehuculoServicio,'PLACA_VEHICULO'=>$placa,
       'PR_SALIDA'=>$prsalida,'RN_SALIDA'=>$rnSalida,'DATOS_SERVICIO'=>$DatosServicio,'ESTADO_SERVICO'=>1);
       $rspta = $ejecucion->insertar_id($table_name, $data_values);

       if($rspta){
        if($DatosServicio!=5){
         $data_values = array('ID_SERVICIO_DETALLE'=>$rspta,"PR_TRASLADO"=>$prtraslado,'RUTA_TRASLADO'=>$rntraslado,
       'TIPO_VEHICULO_ATENDIDO'=>$VehiculoAtendido,'CATEGORIA'=>$Categoria,'HORA_REPORTE'=>$HoraReporte,'HORA_LLEGADA'=>$HoraLLegada,'HORA_INICIO_TRASLADO'=>$HoraInicio,
       'HORA_FIN_TRASLADO'=>$Horafin,'HORA_FIN_SERVICIO'=>$Horafinservicio,'HORA_BASE'=>$HoraBase);
       $rspta = $ejecucion->insertar('detalle_servicio_general', $data_values); 
       echo $rspta? "Registro Exitoso" : "Error no se pudo registrar el detalle";

        }else{
        $data_values = array('ID_SERVICIO_DETALLE_AMBU'=>$rspta,"NUM_PERSONA_ATENDIDAS"=>$PersonasHeridas,'HERIDOS_GRAVES'=>$graves,
       'HERIDOS_LEVES'=>$leves,'HERIDOS_ILESOS'=>$ilesos,"FALLECIDOS"=>$Fallecidos,'HORA_REPORTE'=>$HoraReporte,'HORA_LLEGADA'=>$HoraLLegada,'HORA_INICIO_TRASLADO'=>$HoraInicio,
       'HORA_FIN_TRASLADO'=>$Horafin,'HORA_FIN_SERVICIO'=>$Horafinservicio,'HORA_BASE'=>$HoraBase);
       $rspta = $ejecucion->insertar('datalle_ambulancia', $data_values); 
       echo $rspta? "Registro Exitoso" : "Error no se pudo registrar el detalle";
        }

       }else{
         echo "Error no se pudo realizar el registro";	 
       }
		  
      }else{
		
		$data_values =array('CENTRO_SERVICIO'=>$base,"FECHA_SERVICIO"=>$fecha,'TIPO_EVENTO_SERVICIO'=>$tipoEvento,
       'PR'=>$Prinicial,'METROS'=>$Metros,'UF'=>$Uf,'TIPO_VEHICULO_SERVICIO'=>$VehuculoServicio,'PLACA_VEHICULO'=>$placa,
       'PR_SALIDA'=>$prsalida,'RN_SALIDA'=>$rnSalida,'DATOS_SERVICIO'=>$DatosServicio,'ESTADO_SERVICO'=>1);
        $where_condition = array('ID_SERVICIO_ASISTENCIAL' => $id);
        $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
        if($rspta){
          if($DatosServicio!=5){

        $data_values =array("PR_TRASLADO"=>$prtraslado,'RUTA_TRASLADO'=>$rntraslado,
       'TIPO_VEHICULO_ATENDIDO'=>$VehiculoAtendido,'CATEGORIA'=>$Categoria,'HORA_REPORTE'=>$HoraReporte,'HORA_LLEGADA'=>$HoraLLegada,'HORA_INICIO_TRASLADO'=>$HoraInicio,
       'HORA_FIN_TRASLADO'=>$Horafin,'HORA_FIN_SERVICIO'=>$Horafinservicio,'HORA_BASE'=>$HoraBase);
        $where_condition = array('ID_SERVICIO_DETALLE' => $id);
        $rspta = $ejecucion->editar("detalle_servicio_general", $data_values, $where_condition);

        }else{

        $data_values =array("NUM_PERSONA_ATENDIDAS"=>$PersonasHeridas,'HERIDOS_GRAVES'=>$graves,
       'HERIDOS_LEVES'=>$leves,'HERIDOS_ILESOS'=>$ilesos,"FALLECIDOS"=>$Fallecidos,'HORA_REPORTE'=>$HoraReporte,'HORA_LLEGADA'=>$HoraLLegada,'HORA_INICIO_TRASLADO'=>$HoraInicio,
       'HORA_FIN_TRASLADO'=>$Horafin,'HORA_FIN_SERVICIO'=>$Horafinservicio,'HORA_BASE'=>$HoraBase);
        $where_condition = array('ID_SERVICIO_DETALLE_AMBU' => $id);
        $rspta = $ejecucion->editar("datalle_ambulancia", $data_values, $where_condition);
        }
         echo $rspta? "Registro actulizado": "Error no se actulizo el detalle del registro"; 

        }else{
        echo "Error no se actulizo el registro";
        }
        		
		
      }
         
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
	
   case 'Reporte':
        try {
        $arreglo_grua= [];
        $arreglo_carroTaller= [];
        $arreglo_inspectorvial= [];
        $arreglo_accidente= [];
        $arreglo_ambulancia= [];

        $arreglo_grua[]=["CENRTRO OPERATIVO","FECHA SERVICIO","TIPO EVENTO","PR","METROS","UF","TIPO VEHUCULO CONSECIÓN","VEHICULO CONSECIÓN","PR SALIDA","RN SALIDA","PR TRASLADO","RUTA TRASLADO","TIPO VEHICULO ATENDIDO","CATEGORIA","HORA REPORTE","HORA LLEGADA","HORA INICIO TRASLADO","HORA FIN TRASLADO","HORA FIN SERVICIO","HORA BASE"];
        $arreglo_carroTaller[]=["CENRTRO OPERATIVO","FECHA SERVICIO","TIPO EVENTO","PR","METROS","UF","TIPO VEHUCULO CONSECIÓN","VEHICULO CONSECIÓN","PR SALIDA","RN SALIDA","TIPO VEHICULO ATENDIDO","CATEGORIA","HORA REPORTE","HORA LLEGADA","HORA INICIO TRASLADO","HORA FIN TRASLADO","HORA FIN SERVICIO","HORA BASE"];
        $arreglo_inspectorvial[]=["CENRTRO OPERATIVO","FECHA SERVICIO","TIPO EVENTO","PR","METROS","UF","TIPO VEHUCULO CONSECIÓN","VEHICULO CONSECIÓN","PR SALIDA","RN SALIDA","TIPO VEHICULO ATENDIDO","CATEGORIA","HORA REPORTE","HORA LLEGADA","HORA INICIO TRASLADO","HORA FIN TRASLADO","HORA FIN SERVICIO","HORA BASE"];
        $arreglo_accidente[]=["CENRTRO OPERATIVO","FECHA SERVICIO","TIPO EVENTO","PR","METROS","UF","TIPO VEHUCULO CONSECIÓN","VEHICULO CONSECIÓN","PR SALIDA","RN SALIDA","PR TRASLADO","RUTA TRASLADO","TIPO VEHICULO ATENDIDO","CATEGORIA","HORA REPORTE","HORA LLEGADA","HORA INICIO TRASLADO","HORA FIN TRASLADO","HORA FIN SERVICIO","HORA BASE"];
        $arreglo_ambulancia[]=["CENRTRO OPERATIVO","FECHA SERVICIO","TIPO EVENTO","PR","METROS","UF","TIPO VEHUCULO CONSECIÓN","VEHICULO CONSECIÓN","PR SALIDA","RN SALIDA","PERSONAS ATENDIDAS","HERIDOS GRAVES","HERIDOS LEVES","HERIDOS ILESOS","FALLECIDOS","HORA REPORTE","HORA LLEGADA","HORA INICIO TRASLADO","HORA FIN TRASLADO","HORA FIN SERVICIO","HORA BASE"];

        $rspta = $Consulta->ReporteAsistencialGrua($detalle,$FechaIR,$FechaFR);
         while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
         {

          switch($reg->DATOS_SERVICIO){
            case "1":
            $arreglo_grua[]=[
            $reg->NombreCentroOP, 
            $reg->FECHA_SERVICIO,
            $reg->NOMBRE_TIPO_EVENTO,
            $reg->PR,
            $reg->METROS,
            $reg->UF,
            $reg->NOM_TIPO_VEHICULO,
            $reg->PLACA_VEHICULO,
            $reg->PR_SALIDA,
            $reg->RN_SALIDA,
            $reg->PR_TRASLADO,
            $reg->RUTA_TRASLADO,
            $reg->TIPO_VEHICULO_ATENDIDO,
            $reg->CATEGORIA,
            $reg->HORA_REPORTE,
            $reg->HORA_LLEGADA,
            $reg->HORA_INICIO_TRASLADO,
            $reg->HORA_FIN_TRASLADO,
            $reg->HORA_FIN_SERVICIO,
            $reg->HORA_BASE
          ];  
              break;

            case "2":
            $arreglo_carroTaller[]=[
            $reg->NombreCentroOP,   
            $reg->FECHA_SERVICIO,
            $reg->NOMBRE_TIPO_EVENTO,
            $reg->PR,
            $reg->METROS,
            $reg->UF,
            $reg->NOM_TIPO_VEHICULO,
            $reg->PLACA_VEHICULO,
            $reg->PR_SALIDA,
            $reg->RN_SALIDA,
            $reg->TIPO_VEHICULO_ATENDIDO,
            $reg->CATEGORIA,
            $reg->HORA_REPORTE,
            $reg->HORA_LLEGADA,
            $reg->HORA_INICIO_TRASLADO,
            $reg->HORA_FIN_TRASLADO,
            $reg->HORA_FIN_SERVICIO,
            $reg->HORA_BASE
          ];  
          
              break;
            case "3":
            $arreglo_inspectorvial[]=[
            $reg->NombreCentroOP,   
            $reg->FECHA_SERVICIO,
            $reg->NOMBRE_TIPO_EVENTO,
            $reg->PR,
            $reg->METROS,
            $reg->UF,
            $reg->NOM_TIPO_VEHICULO,
            $reg->PLACA_VEHICULO,
            $reg->PR_SALIDA,
            $reg->RN_SALIDA,
            $reg->TIPO_VEHICULO_ATENDIDO,
            $reg->CATEGORIA,
            $reg->HORA_REPORTE,
            $reg->HORA_LLEGADA,
            $reg->HORA_INICIO_TRASLADO,
            $reg->HORA_FIN_TRASLADO,
            $reg->HORA_FIN_SERVICIO,
            $reg->HORA_BASE
          ];  
          
              break;
            case "4":
            $arreglo_accidente[]=[
            $reg->NombreCentroOP,   
            $reg->FECHA_SERVICIO,
            $reg->NOMBRE_TIPO_EVENTO,
            $reg->PR,
            $reg->METROS,
            $reg->UF,
            $reg->NOM_TIPO_VEHICULO,
            $reg->PLACA_VEHICULO,
            $reg->PR_SALIDA,
            $reg->RN_SALIDA,
            $reg->PR_TRASLADO,
            $reg->RUTA_TRASLADO,
            $reg->TIPO_VEHICULO_ATENDIDO,
            $reg->CATEGORIA,
            $reg->HORA_REPORTE,
            $reg->HORA_LLEGADA,
            $reg->HORA_INICIO_TRASLADO,
            $reg->HORA_FIN_TRASLADO,
            $reg->HORA_FIN_SERVICIO,
            $reg->HORA_BASE
          ];  
              break;
          }

         }
          $rspta = $Consulta->ReporteAsistencialAmbulancia($detalle,$FechaIR,$FechaFR);
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {
            $arreglo_ambulancia[]=[
            $reg->NombreCentroOP,   
            $reg->FECHA_SERVICIO,
            $reg->NOMBRE_TIPO_EVENTO,
            $reg->PR,
            $reg->METROS,
            $reg->UF,
            $reg->NOM_TIPO_VEHICULO,
            $reg->PLACA_VEHICULO,
            $reg->PR_SALIDA,
            $reg->RN_SALIDA,
            $reg->NUM_PERSONA_ATENDIDAS,
            $reg->HERIDOS_GRAVES,
            $reg->HERIDOS_LEVES,
            $reg->HERIDOS_ILESOS,
            $reg->FALLECIDOS,
            $reg->HORA_REPORTE,
            $reg->HORA_LLEGADA,
            $reg->HORA_INICIO_TRASLADO,
            $reg->HORA_FIN_TRASLADO,
            $reg->HORA_FIN_SERVICIO,
            $reg->HORA_BASE
          ];  
          }

          echo json_encode(array("grua"=>$arreglo_grua,"carroTaller"=>$arreglo_carroTaller,
          "inspectorvial"=>$arreglo_inspectorvial,"accidente"=>$arreglo_accidente,
          "ambulancia"=>$arreglo_ambulancia)); 
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;	 


		
	case 'Solicitud':
        try {
        $data_values = array ('ESTADO_SERVICO'=>2);
        $where_condition = array('ID_SERVICIO_ASISTENCIAL'=> $id);
        $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
		 if($rspta){
              $mensajec="Saludos, se registro una solicitud de aprobación para la modificacion de los datos de un registro del detalle de ejecucion asistenciales, porfavor ingrese al sistema para aprobar o rechazar esta solicitud.";
              $asunto="SOLICITUD DE MODIFICACION";
                        if(correoenvio($mensajec,$asunto)){
                           echo 'Solicitud eneviada exitosamente';
                        
                        }else{
                            echo 'el registro quedo en estado de aprobación, pero no se envio la notificación de correo al personal de tecnología.';
                            
                        }
         }else{
             echo 'Error no se pudo cambiar estado del registro';
               
         } 	
        
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;	
		
	case 'AprobarRechazar':
        try {
        $data_values = array ('ESTADO_SERVICO'=>$estadom);
        $where_condition = array('ID_SERVICIO_ASISTENCIAL'=> $id);
        $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
	    echo $rspta? "Registro existoso": "no se realizo el registro";    
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;		
		
    case 'listar':
      try {
    
          $rspta = $Consulta->listarEjecucionAsistecial($estado,$centro,$fechai,$fechaf);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {  
			$bot="";  
			if($reg->ESTADO_SERVICO==1){   
            $bot='<SPAN title="Solicitud de edición"><button class="btn btn-light" onclick="solicitud('.$reg->ID_SERVICIO_ASISTENCIAL.')"><i class="fa fa-pencil-square-o" style=""></i></button></SPAN>
            <SPAN title="Editar"><button class="btn btn-light" onclick="mostrar2('.$reg->ID_SERVICIO_ASISTENCIAL.')"><i class="fa fa-pencil" style=""></i></button></SPAN>';  
			}else if($reg->ESTADO_SERVICO==2 and $_SESSION['IdUsuarios']==1){
			 $bot='<SPAN title="Aprobar"><button class="btn btn-light" onclick="aprobar('.$reg->ID_SERVICIO_ASISTENCIAL.')"><i class="fa  fa-check" style=""></i></button></SPAN> <SPAN title="Rechazar"><button class="btn btn-light" onclick="rechazar('.$reg->ID_SERVICIO_ASISTENCIAL.')"><i class="fa fa-times" style=""></i></button></SPAN>'; 	
			}else if($reg->ESTADO_SERVICO==3){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_SERVICIO_ASISTENCIAL.')"><i class="fa fa-eye" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
            "0"=>$reg->NombreCentroOP,
            "1"=>$reg->FECHA_SERVICIO,
            "2"=>$reg->NOM_TIPO_VEHICULO,
			  "3"=>$reg->PLACA_VEHICULO,
        "4"=>$reg->NOMBRE_TIPO_EVENTO,
        "5"=>$reg->PR,
        "6"=>$reg->METROS,
        "7"=>$reg->UF,
        "8"=>$reg->PR_SALIDA,
        "9"=>$reg->RN_SALIDA,
        "10"=>$bot
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
          $where_condition = array('ID_SERVICIO_ASISTENCIAL'=>$id);//$_GET["estado"]);   
           $rspta = $ejecucion->mostrar($table_name, $where_condition);
           if($rspta["DATOS_SERVICIO"]!="5"){
           $where_condition = array('ID_SERVICIO_DETALLE'=>$id);//$_GET["estado"]);   
           $rspta2 = $ejecucion->mostrar("detalle_servicio_general", $where_condition);
           }else{
           $where_condition = array('ID_SERVICIO_DETALLE_AMBU'=>$id);//$_GET["estado"]);   
           $rspta2 = $ejecucion->mostrar("datalle_ambulancia", $where_condition);
           }
           echo json_encode(array("general"=>$rspta,"Detalle"=>$rspta2));
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        
        case 'mostrarProyecto':
        try {  
           $rspta = $Consulta->ProyectoVehiculo($contrato);
           echo json_encode($rspta);
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;

        case 'select1':
        try {
           $where_condition = array('EstadoCentroOP'=>1);//$_GET["estado"]);   
           $rspta = $ejecucion->validar('centrooperativo', $where_condition); 
           echo "<option value=''>Seleccione Centro operación...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
              echo "<option value='$reg->IDCentroOP'>$reg->NombreCentroOP</option>";
            }
			 
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
		
		case 'select2':
          try {
            $rspta = $Consulta->SelectVehiculoNormal(); 
            $datos=array();
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
              $datos[]=array("nombre"=>$reg->PLACA_VEH." ".$reg->NOM_TIPO_VEHICULO,
              "icono"=>'<i class="fa fa-car" aria-hidden="true"></i>',
              "url"=>$reg->PLACA_VEH);
            }
            echo json_encode(array("datos"=>$datos));			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;

           case 'select3':
        try {
           $where_condition = array('ESTADO_EVENTO'=>1);//$_GET["estado"]);   
           $rspta = $ejecucion->validar('tipo_evento_asistencial', $where_condition); 
           echo "<option value=''>Seleccione tipo evento...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
              echo "<option value='$reg->ID_TIPO_EVENTO'>$reg->NOMBRE_TIPO_EVENTO</option>";
            }
			 
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
									  
									  
           case 'validar':
          try {
           $rspta = $Consulta->validar2($contrato, $vehiculo,$fecha);	  
			if($rspta->num_rows<=0){
				echo true;
			}else{
				echo false;
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