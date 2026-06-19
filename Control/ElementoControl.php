<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$elemento = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idelemento"])?limpiarCadena($_POST["idelemento"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])?limpiarCadena($_POST["descripcion"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "elementosflota";

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
		 $imagen="";
		 if(isset($_FILES["miarchivo"]) and $_FILES["miarchivo"]["name"]){
		  if(esImagen($_FILES['miarchivo']['tmp_name'])){
                         $tipo = pathinfo($_FILES['miarchivo']['tmp_name'], PATHINFO_EXTENSION); 
                           $imagen ='data:'.mime_content_type($_FILES['miarchivo']['tmp_name']).';base64,'.base64_encode(file_get_contents($_FILES['miarchivo']['tmp_name']));   
                        }
		 } 
		  
		 $data_values = array('NOMBRE_ELEMENTO'=>$nombre,"DESCRIPCION"=>$descripcion,"IMAGEN_REFERENCIA"=>$imagen,"ESTADO_ELEMENTO"=>1);
         $rspta = $elemento->insertarvh($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		  
      } else {
		 $imagen="";
		 if(isset($_FILES["miarchivo"]) and $_FILES["miarchivo"]["name"]){
		  if(esImagen($_FILES['miarchivo']['tmp_name'])){
                         $tipo = pathinfo($_FILES['miarchivo']['tmp_name'], PATHINFO_EXTENSION); 
                           $imagen ='data:'.mime_content_type($_FILES['miarchivo']['tmp_name']).';base64,'.base64_encode(file_get_contents($_FILES['miarchivo']['tmp_name']));   
         }
		$data_values = array('NOMBRE_ELEMENTO' => $nombre,'DESCRIPCION'=>$descripcion,'IMAGEN_REFERENCIA'=>$imagen);
        $where_condition = array('IDELEMETO' => $id);
        $rspta = $elemento->editarvh($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro"; 	 
		}else{
		$data_values = array('NOMBRE_ELEMENTO' => $nombre,'DESCRIPCION'=>$descripcion);
        $where_condition = array('IDELEMETO' => $id);
        $rspta = $elemento->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro"; 
		 }   
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
    
          $where_condition = array('ESTADO_ELEMENTO'=>$estado);//$_GET["estado"]);   
          $rspta = $elemento->validar($table_name, $where_condition); 
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDELEMETO.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->IDELEMETO.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->ESTADO_ELEMENTO==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDELEMETO.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->IDELEMETO.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
              "0"=>$reg->NOMBRE_ELEMENTO,
              "1"=>$reg->DESCRIPCION,
			  "2"=>' <img src="'.$reg->IMAGEN_REFERENCIA.'" id="imagen" class="img-rounded"  style=" display: block;
              margin-left: auto;
             margin-right: auto;
			 width: 20%">',	
             "3"=>$bot
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
          $where_condition = array('IDELEMETO'=>$id);//$_GET["estado"]);   
           $rspta = $elemento->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('ESTADO_ELEMENTO' => 1);
            $where_condition = array('IDELEMETO' => $id);
            $rspta = $elemento->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('ESTADO_ELEMENTO' => 0);
            $where_condition = array('IDELEMETO' => $id);
            $rspta = $elemento->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;

  default:
    echo "La opción seleccionada no existe";
    break;
}

?>