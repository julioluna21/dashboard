<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$Item = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["iditem"])?limpiarCadena($_POST["iditem"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "item";

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		 $data_values = array('NOMBRE_ITEM'=>$nombre,"ESTADO_ITEM"=>1);
         $rspta = $Item->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		  
      } else {
        $data_values = array('NOMBRE_ITEM' => $nombre);
        $where_condition = array('IDITEM' => $id);
        $rspta = $Item->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
          $conditio= array("ESTADO_ITEM"=>$estado);
          $rspta = $Item->listar($table_name,$conditio);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDITEM.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->IDITEM.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->ESTADO_ITEM==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDITEM.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->IDITEM.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
			  "0"=>$reg->IDITEM,	
              "1"=>$reg->NOMBRE_ITEM,
              "2"=>$bot
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
          $where_condition = array('IDITEM'=>$id);//$_GET["estado"]);   
           $rspta = $Item->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('ESTADO_ITEM' => 1);
            $where_condition = array('IDITEM' => $id);
            $rspta = $Item->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('ESTADO_ITEM' => 0);
            $where_condition = array('IDITEM' => $id);
            $rspta = $Item->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		 case 'select':
          try {
           $where_condition = array('ESTADO_ITEM'=>1);//$_GET["estado"]);   
           $rspta = $Item->validar($table_name, $where_condition); 
           echo "<option value=''>Seleccione Item...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDITEM'>$reg->NOMBRE_ITEM</option>";
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