<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$combustiblet = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idcombistible"])?limpiarCadena($_POST["idcombistible"]):"";
$proyecto=isset($_POST["proyecto"])?limpiarCadena($_POST["proyecto"]):"";
$ano=isset($_POST["ano"])?limpiarCadena($_POST["ano"]):"";
$mes=isset($_POST["MES"])?limpiarCadena($_POST["MES"]):"";
$vehiculo=isset($_POST["vehiculo"])?limpiarCadena($_POST["vehiculo"]):"";
$combustible=isset($_POST["combustible"])?limpiarCadena(str_replace(".","",$_POST["combustible"])):"";
$kilometros=isset($_POST["kilometros"])?limpiarCadena(str_replace(".","",$_POST["kilometros"])):"";
$servicios=isset($_POST["servicios"])?limpiarCadena(str_replace(".","",$_POST["servicios"])):"";
$peajes=isset($_POST["peajes"])?limpiarCadena(str_replace(".","",$_POST["peajes"])):"";
$mantenimiento=isset($_POST["mantenimiento"])?limpiarCadena(str_replace(".","",$_POST["mantenimiento"])):"";
$dineroCombustible=isset($_POST["plataConbustible"])?limpiarCadena(str_replace(".","",$_POST["plataConbustible"])):"";
$pasos=isset($_POST["pasos"])?limpiarCadena(str_replace(".","",$_POST["pasos"])):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "COMBUSTIBLE";

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		  
		 $where_condition = array("IDVEHUCULOCOMBUSTIBLE"=>$vehiculo,'AND PROYECTO_COMBUSTIBLE'=>$proyecto," AND ANO_COMBUSTIBLE"=>$ano," AND MES_COMBUSTIBLE"=>$mes);//$_GET["estado"]); 
		 $rspta = $combustiblet->validar($table_name, $where_condition);  
		 if($rspta->num_rows<=0){
		$data_values = array("IDVEHUCULOCOMBUSTIBLE"=>$vehiculo,'PROYECTO_COMBUSTIBLE '=>$proyecto,"ANO_COMBUSTIBLE"=>$ano,"MES_COMBUSTIBLE"=>$mes,"GALONES"=>$combustible,"KILOMETROS"=>$kilometros,"SERVICIOS"=>$servicios,"PEJAE"=>$peajes,"MANTENIMIENTO"=>$mantenimiento,"DINEROCOMBUSTIBLE"=>$dineroCombustible,"CANTIDADPEAJES"=>$pasos,"ESTADO_COMBUSTIBLE"=>1);
         $rspta = $combustiblet->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		 }else{
			 echo "Error ya existe un registro con este perido cargado al proyecto y vehiculo seleccionado.";
		 } 
 
		  
      } else {
		 $where_condition = array("IDVEHUCULOCOMBUSTIBLE"=>$vehiculo,'AND PROYECTO_COMBUSTIBLE'=>$proyecto," AND ANO_COMBUSTIBLE"=>$ano," AND MES_COMBUSTIBLE"=>$mes);//$_GET["estado"]); 
		 $rspta = $combustiblet->validar($table_name, $where_condition);  
		 if($rspta->num_rows>0){
		 $reg=$rspta->fetch_object();
		 if($id==$reg->ID_COMBUSTIBLE){
		 $data_values = array("IDVEHUCULOCOMBUSTIBLE"=>$vehiculo,'PROYECTO_COMBUSTIBLE '=>$proyecto,"ANO_COMBUSTIBLE"=>$ano,"MES_COMBUSTIBLE"=>$mes,"GALONES"=>$combustible,"KILOMETROS"=>$kilometros,"SERVICIOS"=>$servicios,"PEJAE"=>$peajes,"MANTENIMIENTO"=>$mantenimiento,"DINEROCOMBUSTIBLE"=>$dineroCombustible,"CANTIDADPEAJES"=>$pasos);
         $where_condition = array('ID_COMBUSTIBLE ' => $id);
         $rspta = $combustiblet->editar($table_name, $data_values, $where_condition);
         echo $rspta? "Registro actulizado": "Error no se actulizo el registro";	 
		 }else{
			echo "Error ya existe un registro con este perido cargado al proyecto Y vehiculo seleccionado."; 
		 }	 
		 }else{
			 
		 $data_values = array("IDVEHUCULOCOMBUSTIBLE"=>$vehiculo,'PROYECTO_COMBUSTIBLE '=>$proyecto,"ANO_COMBUSTIBLE"=>$ano,"MES_COMBUSTIBLE"=>$mes,"GALONES"=>$combustible,"KILOMETROS"=>$kilometros,"SERVICIOS"=>$servicios,"PEJAE"=>$peajes,"MANTENIMIENTO"=>$mantenimiento,"DINEROCOMBUSTIBLE"=>$dineroCombustible,"CANTIDADPEAJES"=>$pasos);
         $where_condition = array('ID_COMBUSTIBLE ' => $id);
         $rspta = $combustiblet->editar($table_name, $data_values, $where_condition);
         echo $rspta? "Registro actulizado": "Error no se actulizo el registro";		 
			 
		 }  
        
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
    

          $rspta = $Consulta->listarCombustible($estado);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_COMBUSTIBLE.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->ID_COMBUSTIBLE.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->ESTADO_COMBUSTIBLE==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_COMBUSTIBLE.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->ID_COMBUSTIBLE.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
			  "0"=>$reg->PLACA_VEH,	
              "1"=>$reg->nombreProyecto,
              "2"=>$reg->MES_COMBUSTIBLE." DEL ".$reg->ANO_COMBUSTIBLE,
			  "3"=>number_format($reg->GALONES,0, '', '.'),
			  "4"=>number_format($reg->KILOMETROS,0, '', '.'),
			  "5"=>number_format($reg->SERVICIOS,0, '', '.'),	
			  "6"=>number_format($reg->PEJAE,0, '', '.'),	
			  "7"=>number_format($reg->CANTIDADPEAJES,0, '', '.'),		
			  "8"=>number_format($reg->MANTENIMIENTO,0, '', '.'),
			  "9"=>number_format($reg->DINEROCOMBUSTIBLE,0, '', '.'),	
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
          $where_condition = array('ID_COMBUSTIBLE'=>$id);//$_GET["estado"]);   
           $rspta = $combustiblet->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
		
		case 'mostrarProyecto':
        try {
          $where_condition = array('ID_VEHICULOS_DIS'=>$vehiculo, " AND ESTADO_DIS"=>1);//$_GET["estado"]);   
          $rspta = $combustiblet->mostrar('DISPONIBILIDAD_FLOTA', $where_condition);
          echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('ESTADO_COMBUSTIBLE' => 1);
            $where_condition = array('ID_COMBUSTIBLE' => $id);
            $rspta = $combustiblet->editar('DISPONIBILIDAD_FLOTA', $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('ESTADO_COMBUSTIBLE' => 0);
            $where_condition = array('ID_COMBUSTIBLE' => $id);
            $rspta = $combustiblet->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		case 'select':
          try {   
            
			$where_condition = array('estadoProyecto'=>1);//$_GET["estado"]);   
            $rspta = $combustiblet->validar('Proyectos', $where_condition); 
			echo "<option value=''>Seleccione..</option>";    
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->Idproyctos'>$reg->nombreProyecto</option>";
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