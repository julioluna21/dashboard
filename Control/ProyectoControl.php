<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$proyecto = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idproyecto"])?limpiarCadena($_POST["idproyecto"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$contrato=isset($_POST["contrato"])?limpiarCadena($_POST["contrato"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "Proyectos";

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		 
	     $data_values = array('nombreProyecto'=>$nombre,"IDcontratoProyecto"=>$contrato,"estadoProyecto"=>1);
         $rspta = $proyecto->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		  
      } else {
         $data_values = array('nombreProyecto'=>$nombre,"IDcontratoProyecto"=>$contrato,"estadoProyecto"=>1);
        $where_condition = array('Idproyctos' => $id);
        $rspta = $proyecto->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
		  

          $rspta = $Consulta->listarProyectos($_GET["estado"]);
    
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->Idproyctos.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->Idproyctos.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->estadoProyecto==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->Idproyctos.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->Idproyctos.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
              "0"=>$reg->nombreProyecto,
              "1"=>$reg->NombreContrato,	
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
          $where_condition = array('Idproyctos'=>$id);//$_GET["estado"]);   
           $rspta = $proyecto->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('estadoProyecto' => 1);
            $where_condition = array('Idproyctos' => $id);
            $rspta = $proyecto->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('estadoProyecto' => 0);
            $where_condition = array('Idproyctos' => $id);
            $rspta = $proyecto->editar($table_name, $data_values, $where_condition);
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