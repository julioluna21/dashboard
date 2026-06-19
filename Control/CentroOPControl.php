<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$Centro = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idcentro"])?limpiarCadena($_POST["idcentro"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$regional=isset($_POST["regional"])?limpiarCadena($_POST["regional"]):"";
$tipo=isset($_POST["tipo"])?limpiarCadena($_POST["tipo"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "centrooperativo";

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		 $data_values = array('NombreCentroOP'=>$nombre,"IDRegionalCentroOP"=>$regional,"TipoCentroOP"=>$tipo,"EstadoCentroOP"=>1);
         $rspta = $Centro->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		  
      } else {
        $data_values = array('NombreCentroOP' => $nombre,'IDRegionalCentroOP'=>$regional,'TipoCentroOP'=>$tipo);
        $where_condition = array('IDCentroOP' => $id);
        $rspta = $Centro->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
    
          $rspta = $Consulta->listarCentro($estado);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDCentroOP.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->IDCentroOP.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->EstadoCentroOP==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDCentroOP.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->IDCentroOP.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
              "0"=>$reg->NombreCentroOP,
              "1"=>$reg->NombreRegional,
			  "2"=>$reg->TipoCentroOP,	
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
          $where_condition = array('IDCentroOP'=>$id);//$_GET["estado"]);   
           $rspta = $Centro->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('EstadoCentroOP' => 1);
            $where_condition = array('IDCentroOP' => $id);
            $rspta = $Centro->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('EstadoCentroOP' => 0);
            $where_condition = array('IDCentroOP' => $id);
            $rspta = $Centro->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		 case 'select':
          try {
           $where_condition = array('EstadoRegional'=>1);//$_GET["estado"]);   
           $rspta = $Centro->validar('regional', $where_condition); 
           echo "<option value=''>Seleccione Regional...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDRegional'>$reg->NombreRegional</option>";
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