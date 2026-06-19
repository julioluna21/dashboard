<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";


$ejecucion = new configuracion();
$inventario = new consultas();
$id=isset($_POST["idinventario"])?limpiarCadena($_POST["idinventario"]):"";
$vehiculo="";
$datos = isset($_POST["info"]) ? json_decode(json_encode($_POST['info']),true) : "";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "detalle_inventario";
if($datos!=""){
$id=limpiarCadena($datos[0]['idinventario']);
$vehiculo=limpiarCadena($datos[0]['vehiculo']);	
}

switch ($_GET["op"]) {
  case 'guardar':
    try {
		
	if (empty($id)) {
		
		 if($datos!=""){
		  $data_values = array('ID_VEHICULO_INVENTARIO'=>$vehiculo,"ESTADO_INEVENTARIO"=>1);
          $rspta = $ejecucion->insertar_id('inventario', $data_values);
          $idInventario=$rspta; 
		 if($rspta){
		 for($i=0;$i<count($datos);$i++){
			 $elemento=limpiarCadena($datos[$i]['elemento']);
			 $estadoelemento=limpiarCadena($datos[$i]['estadoelemento']);
			 $cantidad=limpiarCadena($datos[$i]['cantidad']);
             $observacion=limpiarCadena($datos[$i]['observacion']);
			 $data_values = array('ID_INVENARIOINV'=>$idInventario,"ID_ELEMENTOINV"=>$elemento,'CANTIDADINV'=>$cantidad,'ESTADOINV'=>$estadoelemento,"OBSERVACIONINV"=>$observacion);
             $rspta = $ejecucion->insertar($table_name, $data_values);        
          }
		 echo "Registro exitoso";	 
		 }else{
			echo "Error no se realizo el registro del encabezado";	  
		 }	 

		 }else{
			echo "Error no se realizo el registro";	 
		 }
		    
      }else{
		$where_condition = array('ID_INVENARIOINV'=>$id);
		$rspta = $ejecucion->borrar("detalle_inventario",$where_condition);	  
		for($i=0;$i<count($datos);$i++){
			 $elemento=limpiarCadena($datos[$i]['elemento']);
			 $estadoelemento=limpiarCadena($datos[$i]['estadoelemento']);
			 $cantidad=limpiarCadena($datos[$i]['cantidad']);
             $observacion=limpiarCadena($datos[$i]['observacion']);
			 $data_values = array('ID_INVENARIOINV'=>$id,"ID_ELEMENTOINV"=>$elemento,'CANTIDADINV'=>$cantidad,'ESTADOINV'=>$estadoelemento,"OBSERVACIONINV"=>$observacion);
             $rspta = $ejecucion->insertar($table_name, $data_values);        
          }
		 echo "Registro actulizado exitosamente.";				
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
			
		
    case 'listar':
      try {
    
          $rspta = $inventario->Listarinventario($estado);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {  
			$bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_INVENTARIO.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->ID_INVENTARIO.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->ESTADO_INEVENTARIO==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_INVENTARIO.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->ID_INVENTARIO.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
              "0"=>$reg->PLACA_VEH,
              "1"=>'<SPAN title="PDF"><button class="btn btn-light" onclick="generarpdf('.$reg->ID_INVENTARIO.','."'".$reg->PLACA_VEH."'".')"><i class="fa fa-cloud-download" style=""></i></button></SPAN>',
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
           $rspta = $inventario->MostrarInventario($id);
		    $data= Array();	
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                     $data[]=$reg;
            
			}
           echo json_encode(array("iformacion"=>$data));
			
		
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
		
		 case 'activar':
          try {
            $data_values = array('ESTADO_INEVENTARIO' => 1);
            $where_condition = array('ID_INVENTARIO' => $id);
            $rspta = $ejecucion->editar('inventario', $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('ESTADO_INEVENTARIO' => 0);
            $where_condition = array('ID_INVENTARIO' => $id);
            $rspta = $ejecucion->editar('inventario', $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;

     	case 'select':
          try {
           $rspta = $inventario->vehiculosinventario();
           echo "<option value=''>Seleccione vehiculo...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->ID_VEH'>$reg->PLACA_VEH</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
				
		
		case 'select2':
          try {
           $where_condition = array('ESTADO_ELEMENTO'=>1);//$_GET["estado"]);   
           $rspta = $ejecucion->validar('elementosflota', $where_condition); 
           echo "<option value=''>Seleccione elemento...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDELEMETO'>$reg->NOMBRE_ELEMENTO</option>";
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