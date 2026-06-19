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
$fecha=isset($_POST["fecha"])?limpiarCadena($_POST["fecha"]):"";
$contrato=isset($_POST["centro"])?limpiarCadena($_POST["centro"]):"";
$categoria=isset($_POST["categoria"])?limpiarCadena($_POST["categoria"]):"";
$totalTrafico=isset($_POST["totaltrafico"])?limpiarCadena(str_replace(".","",$_POST["totaltrafico"])):"";
$TotalRecuado=isset($_POST["totalrecudo"])?limpiarCadena(str_replace(".","",$_POST["totalrecudo"])):"";
$Traficoex=isset($_POST["traficoex"])?limpiarCadena(str_replace(".","",$_POST["traficoex"])):"";
$recudoex=isset($_POST["recudoex"])?limpiarCadena(str_replace(".","",$_POST["recudoex"])):"";
$traficoley=isset($_POST["traficoexentos"])?limpiarCadena(str_replace(".","",$_POST["traficoexentos"])):"";
$estadom=isset($_POST["estadom"])?limpiarCadena($_POST["estadom"]):"";
$traficoconsecion=isset($_POST["traficoConsecion"])?limpiarCadena(str_replace(".","",$_POST["traficoConsecion"])):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$centro=isset($_GET["centrobus"])?limpiarCadena($_GET["centrobus"]):"";
$fechai=isset($_GET["fechainicial"])?limpiarCadena($_GET["fechainicial"]):"";
$fechaf=isset($_GET["fechafinal"])?limpiarCadena($_GET["fechafinal"]):"";
$table_name = "DETALLE_EJECUCION_PEAJES";

function correoenvio($mensaje,$asunto){
                         $mail = new PHPMailer();
                         $mail->PluginDir = "phpMailer/";
                         $mail->Mailer = "smtp";
                         $mail->IsSMTP();
                         $mail->SMTPAuth = true;
                         $mail->Host = "regencysa.net";
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
		 $data_values = array('ID_EJECUCION_CONTRATO'=>$contrato,"CATEGORIA"=>$categoria,'FEC_DET_EJE_PEAJE'=>$fecha,'TOTAL_TRAIFICO'=>$totalTrafico,'TOTAL_RECUDO'=>$TotalRecuado,'TOTAL_TRAFICO_EXCLUSIVO'=>$Traficoex,'TOTAL_RECUDO_EXCLUSIVO'=>$recudoex,'TRAFICO_EXT_LEY'=>$traficoley,'TRAFICO_EXT_CONSECION'=>$traficoconsecion,'ESTADO_JECUCION'=>1);
         $rspta = $ejecucion->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		  
      } else {
		
		$rspta = $Consulta->validar($contrato, $categoria,$fecha);	  
		if($rspta->num_rows>0){
		$reg=$rspta->fetch_object();
		if($reg->IDEJECUCIONPEAJE==$id){
		 $data_values = array ('ID_EJECUCION_CONTRATO'=>$contrato,"CATEGORIA"=>$categoria,'FEC_DET_EJE_PEAJE'=>$fecha,'TOTAL_TRAIFICO'=>$totalTrafico,'TOTAL_RECUDO'=>$TotalRecuado,'TOTAL_TRAFICO_EXCLUSIVO'=>$Traficoex,'TOTAL_RECUDO_EXCLUSIVO'=>$recudoex,'TRAFICO_EXT_LEY'=>$traficoley,'TRAFICO_EXT_CONSECION'=>$traficoconsecion,'ESTADO_JECUCION'=>1);
        $where_condition = array('IDEJECUCIONPEAJE' => $id);
        $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";		
		}else{
			echo "error ya existe un registro para esa fecha, caetgoria y centro operativo seleccionado";
		}		 
		}else{
		$data_values = array ('ID_EJECUCION_CONTRATO'=>$contrato,"CATEGORIA"=>$categoria,'FEC_DET_EJE_PEAJE'=>$fecha,'TOTAL_TRAIFICO'=>$totalTrafico,'TOTAL_RECUDO'=>$TotalRecuado,'TOTAL_TRAFICO_EXCLUSIVO'=>$Traficoex,'TOTAL_RECUDO_EXCLUSIVO'=>$recudoex,'TRAFICO_EXT_LEY'=>$traficoley,'TRAFICO_EXT_CONSECION'=>$traficoconsecion,'ESTADO_JECUCION'=>1);
        $where_condition = array('IDEJECUCIONPEAJE' => $id);
        $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";	
		}
       
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
		
	  case 'importar':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
      if (!empty($_FILES['miarchivo']['name']) && in_array($_FILES['miarchivo']['type'], $csvMimes)) {
        if (is_uploaded_file($_FILES['miarchivo']['tmp_name'])) {
          $csvFile = fopen($_FILES['miarchivo']['tmp_name'], 'r');
          fgetcsv($csvFile);  
		 $con = 0;
         $mensaje=""; 	
          while (($datos = fgetcsv($csvFile, 0, ';')) != false){
		  
        
        // Creamos el SQL para insertar
	
        $dato1=str_replace('"','',$datos[0]);
        $dato2=str_replace('"','',$datos[1]);   
        $dato3=str_replace('"','',$datos[2]);   
        $dato4=str_replace('"','',$datos[3]);
        $dato5=str_replace('"','',$datos[4]);   
        $dato6=str_replace('"','',$datos[5]);
        $dato7=str_replace('"','',$datos[6]);
        $dato8=str_replace('"','',$datos[7]);
        $dato9=str_replace('"','',$datos[8]);
        $dato10=str_replace("'",'',$datos[9]);         
        
        $dato1=str_replace("'",'',$dato1);//quita las comillas en eeste caso las comillas " si son comillas simple modificar ' 
        $dato2=str_replace("'",'',$dato2);   
        $dato3=str_replace("'",'',$dato3);   
        $dato4=str_replace("'",'',$dato4);
        $dato5=str_replace("'",'',$dato5);   
        $dato6=str_replace("'",'',$dato6);
        $dato7=str_replace("'",'',$dato7);
        $dato8=str_replace("'",'',$dato8); 
        $dato9=str_replace("'",'',$dato9);     
        $dato10=str_replace("'",'',$dato10);   
            
        $dato1=str_replace(".",'',$dato1);//quita las comillas en eeste caso las comillas " si son comillas simple modificar ' 
        $dato2=str_replace(".",'',$dato2);   
        $dato3=str_replace(".",'',$dato3);   
        $dato4=str_replace(".",'',$dato4);
        $dato5=str_replace(".",'',$dato5);   
        $dato6=str_replace(".",'',$dato6);
        $dato7=str_replace(".",'',$dato7);
        $dato8=str_replace(".",'',$dato8); 
        $dato9=str_replace(".",'',$dato9);     
        $dato10=str_replace(".",'',$dato10);   
            
        $dato1=str_replace("$",'',$dato1);//quita las comillas en eeste caso las comillas " si son comillas simple modificar ' 
        $dato2=str_replace("$",'',$dato2);   
        $dato3=str_replace("$",'',$dato3);   
        $dato4=str_replace("$",'',$dato4);
        $dato5=str_replace("$",'',$dato5);   
        $dato6=str_replace("$",'',$dato6);
        $dato7=str_replace("$",'',$dato7);
        $dato8=str_replace("$",'',$dato8); 
        $dato9=str_replace("$",'',$dato9);     
        $dato10=str_replace("$",'',$dato10);    
            if($dato3!="" and $dato5!="" and $dato7!="" and $dato8!="" and $dato9!="" ){
                
              if($dato3!=0 or $dato5!=0 or $dato7!=0 or $dato8!=0 or $dato9!=0 ){
                /*$arr = explode('/', $dato10);
                $newDate = $arr[2].'-'.$arr[1].'-'.$arr[0];*/
			//$rspta = $Consulta->validar($contrato, $dato2,$dato10);	  
			 //if($rspta->num_rows<=0){
				 $data_values = array('ID_EJECUCION_CONTRATO'=>$contrato,"CATEGORIA"=>$dato2,'FEC_DET_EJE_PEAJE'=>$dato10,'TOTAL_TRAIFICO'=>$dato3,'TOTAL_RECUDO'=>($dato3*$dato4),'TOTAL_TRAFICO_EXCLUSIVO'=>$dato5,'TOTAL_RECUDO_EXCLUSIVO'=>($dato5*$dato6),'TRAFICO_EXT_LEY'=>$dato7,'TRAFICO_EXT_CONSECION'=>($dato8+$dato9),'ESTADO_JECUCION'=>1);
				  
            if($rspta = $ejecucion->insertar($table_name, $data_values)){
                
            }else{
                $mensaje=$mensaje."linia ".($con+1)." Error no se almaceno <br>";
            } 
				 
			 //}else{
				// $mensaje=$mensaje."linia ".($con+1)." Error no se almaceno existe registro<br>";
			 //}	  
			
                
            }    
               
            
    
            }
        
               
		  
			$con++;  
          }
          echo'Datos importados correctamente  '.$mensaje;
        }else{
          echo 'Error al importar datos';
        }
      }else{
        echo 'No se ha proporcionado un archivo o formato no compatible.';
      }
  } else {
      echo 'Método no permitido par el envío del archivo.';
  }
  break;	
		
	case 'Solicitud':
        try {
        $data_values = array ('ESTADO_JECUCION'=>2);
        $where_condition = array('IDEJECUCIONPEAJE'=> $id);
        $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
		 if($rspta){
              $mensajec="Saludos, se registro una solicitud de aprobación para la modificacion de los datos de un registro del detalle de ejecucion peajes, porfavor ingrese al sistema para aprobar o rechazar esta solicitud.";
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
        $data_values = array ('ESTADO_JECUCION'=>$estadom);
        $where_condition = array('IDEJECUCIONPEAJE'=> $id);
        $rspta = $ejecucion->editar($table_name, $data_values, $where_condition);
	    echo $rspta? "Registro existoso": "no se realizo el registro";    
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;		
		
    case 'listar':
      try {
    
          $rspta = $Consulta->listarEjecucionpeaje($estado,$centro,$fechai,$fechaf);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {   
			$bot="";   
			if($reg->ESTADO_JECUCION==1 and $_SESSION['IdUsuarios']!=1){   
            $bot='<SPAN title="Solicitud de edición"><button class="btn btn-light" onclick="solicitud('.$reg->IDEJECUCIONPEAJE.')"><i class="fa fa-pencil-square-o" style=""></i></button></SPAN>';  
			}else if($reg->ESTADO_JECUCION==2 and $_SESSION['perfil']==1){
			 $bot='<SPAN title="Aprobar"><button class="btn btn-light" onclick="aprobar('.$reg->IDEJECUCIONPEAJE.')"><i class="fa  fa-check" style=""></i></button></SPAN> <SPAN title="Rechazar"><button class="btn btn-light" onclick="rechazar('.$reg->IDEJECUCIONPEAJE.')"><i class="fa fa-times" style=""></i></button></SPAN>'; 	
			}else if($reg->ESTADO_JECUCION==3){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDEJECUCIONPEAJE.')"><i class="fa fa-eye" style=""></i></button></SPAN>';     
            }else if($reg->ESTADO_JECUCION==1 and  $_SESSION['IdUsuarios']==1){
				$bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDEJECUCIONPEAJE.')"><i class="fa fa-eye" style=""></i></button></SPAN>'; 
			}  
			 
            $data[]=array(
              "0"=>$reg->NombreCentroOP,
              "1"=>$reg->NOMBRE_CATEGORIA,
			  "2"=>$reg->FEC_DET_EJE_PEAJE,
			  "3"=>number_format($reg->TOTAL_TRAIFICO,0, '', '.'),
			  "4"=>"$".(number_format($reg->TOTAL_RECUDO,0, '', '.')),
			  "5"=>number_format($reg->TOTAL_TRAFICO_EXCLUSIVO,0, '', '.'),
			  "6"=>"$".(number_format($reg->TOTAL_RECUDO_EXCLUSIVO,0, '', '.')),
			  "7"=>number_format($reg->TRAFICO_EXT_LEY,0, '', '.'),
			  "8"=>number_format($reg->TRAFICO_EXT_CONSECION,0, '', '.'),	
              "9"=>$bot
				
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
          $where_condition = array('IDEJECUCIONPEAJE'=>$id);//$_GET["estado"]);   
           $rspta = $ejecucion->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;

        case 'select1':
        try {
          $rspta = $Consulta->selectContrato();  
           echo "<option value=''>Seleccione Centro operación...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                echo "<option value='$reg->IDEjecucion'>$reg->NombreCentroOP</option>";
            }
			 
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
	
        case 'select2':
        try {
          $rspta = $Consulta->selectCategoria($contrato,$fecha);  
           echo "<option value=''>Seleccione Categoría...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                echo "<option value='$reg->IDCATEGORIA'>$reg->NOMBRE_CATEGORIA</option>";
            }
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
		
		  case 'select3':
        try {
          $rspta = $Consulta->selectCategoria2();  
           echo "<option value=''>Seleccione Categoría...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                echo "<option value='$reg->IDCATEGORIA'>$reg->NOMBRE_CATEGORIA</option>";
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