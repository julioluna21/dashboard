<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
setlocale(LC_ALL,'es-Es');// Activa la localización con el sistema para mostrar en español
date_default_timezone_set("America/Lima");
$vehiculo = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idvehiculo"])?limpiarCadena($_POST["idvehiculo"]):"";
$placa=isset($_POST["placa"])?limpiarCadena($_POST["placa"]):"";
$marca=isset($_POST["marca"])?limpiarCadena($_POST["marca"]):"";
$modelo=isset($_POST["modelo"])?limpiarCadena($_POST["modelo"]):"";
$tipovh=isset($_POST["tipovh"])?limpiarCadena($_POST["tipovh"]):"";
$propietario=isset($_POST["propietario"])?limpiarCadena($_POST["propietario"]):"";
$comodato=isset($_POST["comodato"])?limpiarCadena($_POST["comodato"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$fechaR=date("Y-m-d");
$table_name = "VEHICULOS";

function esImagen($path)
    {
                $imageSizeArray = getimagesize($path);
                $imageTypeArray = $imageSizeArray[2];
                return (bool)(in_array($imageTypeArray , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)));
     }


switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		  
		  $where_condition = array('PLACA_VEH'=>$placa);//$_GET["estado"]);   
          $rspta = $vehiculo->validar($table_name, $where_condition); 
		  if($rspta->num_rows<=0){
			    $imagen="";
					 if(isset($_FILES["miarchivo"]) and $_FILES["miarchivo"]["name"]){
                        if(esImagen($_FILES['miarchivo']['tmp_name'])){
                         $tipo = pathinfo($_FILES['miarchivo']['tmp_name'], PATHINFO_EXTENSION); 
                           $imagen ='data:'.mime_content_type($_FILES['miarchivo']['tmp_name']).';base64,'.base64_encode(file_get_contents($_FILES['miarchivo']['tmp_name']));   
                        }
                        
                    }
		  
		 $data_values = array('PLACA_VEH'=>$placa,"TIPO_VEH"=>$tipovh,'MARCA_VEH'=>$marca,'MODELO_VEH'=>$modelo,'PROPIETARIO_VEH'=>$propietario,'TIPO_COMODATO'=>$comodato,'IMG_VEHICULO'=>$imagen,'ESTADO_VEHICULO'=>1);
         $rspta = $vehiculo->insertarvh($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	
			  
		  }else{
			  echo 'La placa ingresada ya esta registrada';
		  }
		 
		  
      } else {
		  
		     $imagen="";
					 if(isset($_FILES["miarchivo"]) and $_FILES["miarchivo"]["name"]){
                        if(esImagen($_FILES['miarchivo']['tmp_name'])){
                         $tipo = pathinfo($_FILES['miarchivo']['tmp_name'], PATHINFO_EXTENSION); 
                           $imagen ='data:'.mime_content_type($_FILES['miarchivo']['tmp_name']).';base64,'.base64_encode(file_get_contents($_FILES['miarchivo']['tmp_name']));   
                        }
                        
                    } 
		  
		$data_values ="";  
		if($imagen!=""){
		$data_values = array('PLACA_VEH'=>$placa,"TIPO_VEH"=>$tipovh,'MARCA_VEH'=>$marca,'MODELO_VEH'=>$modelo,'PROPIETARIO_VEH'=>$propietario,'TIPO_COMODATO'=>$comodato,'IMG_VEHICULO'=>$imagen);	
		}else{
		$data_values = array('PLACA_VEH'=>$placa,"TIPO_VEH"=>$tipovh,'MARCA_VEH'=>$marca,'MODELO_VEH'=>$modelo,'PROPIETARIO_VEH'=>$propietario,'TIPO_COMODATO'=>$comodato);
		}  
        
        $where_condition = array('ID_VEH' => $id);
        $rspta = $vehiculo->editarvh($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {

          $rspta = $Consulta->listarVehiculo($estado);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          { 
			  
			if($reg->ID_VEH!=77){
			$bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_VEH.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->ID_VEH.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->ESTADO_VEHICULO==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_VEH.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->ID_VEH.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             } 
			  
			 $comodato="PROPIO";
			 if($reg->TIPO_COMODATO==2){
				$comodato="TERCERO"; 
			 } else if($reg->TIPO_COMODATO==3){
				$comodato="VINCULADO"; 
			 }
			 
            $data[]=array(
              "0"=>$reg->PLACA_VEH ,
              "1"=>$reg->MARCA_VEH,
			  "2"=>$reg->MODELO_VEH,
			  "3"=>$reg->NOM_TIPO_VEHICULO,
			  "4"=>$reg->PROPIETARIO_VEH,
			  "5"=>$comodato,
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
      case 'mostrar':
        try {
          $where_condition = array('ID_VEH'=>$id);//$_GET["estado"]);   
           $rspta = $vehiculo->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('ESTADO_VEHICULO' => 1);
            $where_condition = array('ID_VEH' => $id);
            $rspta = $vehiculo->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
        case 'desactivar':
         try {  
		 $where_condition = array('FECHA_HORA_FIN>'=>$fechaR.' 00:00:00'," and ID_VEHICULO_NOVEDAD"=>$id);//$_GET["estado"]); 
		 $rspta = $vehiculo->validar('NOVEDAD_FLOTA', $where_condition);  
		 if($rspta->num_rows<=0){
			 
		 $where_condition = array('ESTADO_DIS'=>1, ' and ID_VEHICULOS_DIS'=>$id);//$_GET["estado"]); 
		 $rspta = $vehiculo->validar('DISPONIBILIDAD_FLOTA', $where_condition);  
		 if($rspta->num_rows<=0){
			
			$data_values = array('ESTADO_VEHICULO' => 0);
            $where_condition = array('ID_VEH' => $id);
            $rspta = $vehiculo->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";	 
			 
		 }else{
			 
		 $data_values = array('FECHA_FIN_DIS' => $fechaR, 'ESTADO_DIS'=>0);
         $where_condition = array('ID_VEHICULOS_DIS' => $id,'ESTADO_DIS'=>1);
         $rspta = $vehiculo->editar('DISPONIBILIDAD_FLOTA', $data_values, $where_condition);
		 if($rspta){
		    $data_values = array('ESTADO_VEHICULO' => 0);
            $where_condition = array('ID_VEH' => $id);
            $rspta = $vehiculo->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";	 
			  	 	
		}else{
			echo 'Error no fue posible anular la disponibilidad del vehiculo';
		}	 
	  }
		 }else{
			 echo "Error no se puede inactivar el vehiculo, ya que teiene resgistrada una novedad con una fecha final mayor a la fecha actual";
		 }  
			  
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		
		case 'select':
          try {
           $where_condition = array('estadotipo'=>1);//$_GET["estado"]);   
           $rspta = $vehiculo->validar('TIPO_VEHICULOS', $where_condition); 
           echo "<option value=''>Seleccione Tipo...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->ID_TIP_VEH'>$reg->NOM_TIPO_VEHICULO</option>";
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