<?php
session_start();//inicia la session, permite guardar variables de sesion
require_once "../Modelo/ColaboradorModelo.php";//Utilizará este archivo

require '../PHPMailer-master/src/Exception.php';
                require '../PHPMailer-master/src/PHPMailer.php';
                require '../PHPMailer-master/src/SMTP.php';
                use PHPMailer\PHPMailer\PHPMailer;
                use PHPMailer\PHPMailer\Exception;  

$Colaborador=new colaborador();//crea un nuevo articulo
//carga las variables con los valores recibidos y limpia los que no se usaran
$idcolaborador=isset($_POST["idcolaborador"])? limpiarCadena($_POST["idcolaborador"]):"";
$cedula=isset($_POST["cedula"])? limpiarCadena($_POST["cedula"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";

$correo=isset($_POST["correo"])? limpiarCadena($_POST["correo"]):"";
$clave=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$acceso=isset($_POST['permiso']) ? $_POST['permiso'] : false;
$per="";
 if($acceso){
        foreach($acceso as $selected){
            if($per==""){
                $per=$selected;
            }else{
              $per=$per.",".$selected;  
            }
              
         } 
        }


function correoenvio($correo,$mensaje,$asunto){
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
                         $mail->AddAddress($correo);
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


//opciones
switch ($_GET["op"])
    {
            case 'guardar'://primer caso
                if (empty($idcolaborador)) {
					$rspta=$Colaborador->validar($cedula);
                    if($rspta->num_rows<=0){
					  if($rspta=$Colaborador->insertar($cedula,strtoupper($nombre),$correo)){
						  $id=$rspta;
						  $clave = hash("SHA256", $cedula);
						  $rspta=$Colaborador->insertarUsuario($id,$cedula,$clave,$per);
                          echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";
					  }else{
						 echo "Error no se pudo realizar el registro"; 	 
					  }
                    
					}else{
						echo 'Error el colaborador ya esta registrado';
					}
                    
                }else{
                    
                    if($rspta=$Colaborador->editar($idcolaborador,$cedula,strtoupper($nombre),$correo)){
						$rspta=$Colaborador->editarperfil($idcolaborador,$per);
						 echo $rspta ? "Registro actualizado" : "No se pudo actualizar";
					}else{
						echo 'Error no se pudo realizar la actulizacion';
					}
                           
                }
                            
            break;
            case 'mostrar':
       
                    $rspta=$Colaborador->mostrar($idcolaborador);
                    //Codificar el resultado utilizando json
                    echo json_encode($rspta);
            break;
		
		
		    case 'editarclave':
                    $clave = hash("SHA256", $clave);
                    $rspta=$Colaborador->editarClave($idcolaborador,$clave);
                    //Codificar el resultado utilizando json
                    echo $rspta? "Clave cambiada Exitosamente": "Error no se pudo actulizar la calve";
            break;
		
            case 'anular':
                       if($rspta=$Colaborador->anular($idcolaborador)){
						   
					   $rspta=$Colaborador->anularUsuario($idcolaborador);   
					   echo $rspta ? "anulado exitoso" : "No se pudo anular el Usaurio";   	   
					   }else{
						   echo "No se pudo anular el registro";
					   }
            break;
        
             case 'activar':
                   
		 if($rspta=$Colaborador->activar($idcolaborador)){   
					   $rspta=$Colaborador->activarUsuario($idcolaborador);   
					    echo $rspta ? "activado exitoso" : "No se pudo activar el usuario";      	   
					   }else{
						   echo "No se pudo activar el registro";
					   }  
                            
            break;
        
        
            case 'select':
            $rspta=$Colaborador->select();
            echo "<option value=''>Seleccione Observador...</option>";
           while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
           {
                  echo "<option value='$reg->IDCOLABORADOR'>$reg->NOMBRE_COLABORADOR</option>";
            
           }
            break;
        
        
   
        
        
            case 'listar'://activado por el ajax en el scrip articulos
        
        
                    $rspta=$Colaborador->listar($_GET["estado"]);//Carga la rspta con lista de articulos
                    //Vamos a declarar un array
                    $data= Array();
        
                    while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
                    {
                        
                      $bot='<SPAN title="Editar">
                                            <button class="btn btn-light" onclick="mostrar('.$reg->IDCOLABORADOR.')">
                                                    <i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular">
                    <button type="button" class="btn btn-light" onclick="anular('.$reg->IDCOLABORADOR.')">
                    <i class="fa fa-trash" style=""></i></button></SPAN> 
					<SPAN title="Cambiar Clave"><button type="button" id="calve" class="btn btn-light" data-toggle="modal" data-target="#modal-clave" onclick="clave('.$reg->IDCOLABORADOR.')"><i class="fa fa-key" aria-hidden="true"></i></button></SPAN>';       
                
                        $estado="Activo";
                        if($reg->ESTADOCOLABORADOR==0){
                        $estado="Inactivo";
                        $bot='<SPAN title="Editar">
                                            <button class="btn btn-light" onclick="mostrar('.$reg->IDCOLABORADOR.')">
                                                    <i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="Activar">
                     <button type="button" class="btn btn-light" onclick="activar('.$reg->IDCOLABORADOR.')">
                     <i class="fa fa-check" style=""></i></button></SPAN>
					 <SPAN title="Cambiar Clave"><button type="button" id="calve" class="btn btn-light" data-toggle="modal" data-target="#modal-clave" onclick="clave('.$reg->IDCOLABORADOR.')"><i class="fa fa-key" aria-hidden="true"></i></button></SPAN>';
                            
                        }
                               
                        
                            $data[]=array(//arreglo con los datos de las columnas
								    "0"=>$reg->CEDULA_COLABORADOR,
                                    "1"=>$reg->NOMBRE_COLABORADOR,
								    "2"=>$reg->CORREO_COLABORADOR,
                                    "3"=>$estado,
                                    "4"=>$bot,
                            );
                    }
                 
                    $results = array(//variable con el resultado del arreglo
                            "sEcho"=>1, //Información para el datatables
                            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
                            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
                            "aaData"=>$data);
                    echo json_encode($results);//muestra el resultado
            break;
		
		        case 'verificar'://verifica el login
                $LoginUsuarios=isset($_POST["LoginUsuarios"])? limpiarCadena($_POST["LoginUsuarios"]):"";
                $ClaveUsuarios=isset($_POST["ClaveUsuarios"])? limpiarCadena($_POST["ClaveUsuarios"]):"";

                //Hash SHA256 en la contraseña
                    $clavehash=hash("SHA256",$ClaveUsuarios);

                    $rspta=$Colaborador->verificar($LoginUsuarios, $clavehash);//respuesta de la verificación
                    if ($rspta) {
                        $fetch=$rspta->fetch_object();//variable con el resultado
                        if (isset($fetch)){//si no es nulo
                         //Declaramos las variables de sesión
                                $_SESSION['IdUsuarios']=$fetch->ID_USUARIO;
                                $_SESSION['Idcolaborador']=$fetch->IDCOLABORADORUSUARIO;
							    $_SESSION['perfil']=$fetch->PERFIL;
							    $_SESSION['nombrecolab']=$fetch->NOMBRE_COLABORADOR;
                                echo json_encode(array('error' =>false));       
                               
                        }else{
                         echo json_encode(array('error' =>true));   
                        }
                    }else{
                        echo json_encode(array('error' =>true));
                    }

            break;
        
         case 'salir':
                                 
            session_unset();
//            //Destruìmos la sesión
            session_destroy();
//            //Redireccionamos al login
            header("Location: ../Vista/login.php");
             
            break;
		
		
		 case 'recuperar':
        $LoginUsuarios=isset($_POST["LoginUsuarios"])? limpiarCadena($_POST["LoginUsuarios"]):"";
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $contrasena=substr(str_shuffle($permitted_chars), 0, 10);
        if($rspta=$Colaborador->CorreoColaborador($LoginUsuarios)){
         $correo=$rspta['CORREO_COLABORADOR'];
         $id=$rspta['IDCOLABORADOR'];
         $nombre=$rspta['NOMBRE_COLABORADOR'];     
         $clavehash=hash("SHA256",$contrasena);
         $rspta=$Colaborador->editarClave($id,$clavehash);
         if($rspta){
              $mensajec="Saludos $nombre, su nueva contraseña para ingresar al sistema es la siguiente:<br><br>Contraseña:$contrasena<br><br> Cuando ingresé al sistema se recomienda cambiar esta contraseña por razones de seguridad.<br><br> Este es un sistema automático porfavor no responder este mensaje.";
              $asunto="RESTABLECER CLAVE";
                        if(correoenvio($correo,$mensajec,$asunto)){
                            echo json_encode(array('mensaje' =>"Se cambio tu contraseña exitosamente, las credenciales se enviaron a tu correo electrónico.")); 
                        
                        }else{
                            echo json_encode(array('mensaje' =>"No se envio el correo de notificación.")); 
                            
                        }
         }else{
             echo json_encode(array('mensaje' =>"No se pudo recuperar la contraseña.")); 
               
         }    
        }else{
            echo json_encode(array('mensaje' =>'El usurio ingresado no existe en el sistema.')); 
            
        }
        
        
        break;
          
    }