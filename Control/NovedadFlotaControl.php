<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
setlocale(LC_ALL,'es-Es');// Activa la localización con el sistema para mostrar en español
date_default_timezone_set("America/Lima");
$Novedades = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idnovedad"])?limpiarCadena($_POST["idnovedad"]):"";
$vehiculo=isset($_POST["vehiculo"])?limpiarCadena($_POST["vehiculo"]):"";
$tiponovedad=isset($_POST["tiponovedad"])?limpiarCadena($_POST["tiponovedad"]):"";
$fechainical=isset($_POST["fechainicio"])?limpiarCadena($_POST["fechainicio"]):"";
$frechafinal=isset($_POST["fechafinal"])?limpiarCadena($_POST["fechafinal"]):"";
$Novedad=isset($_POST["novedad"])?limpiarCadena($_POST["novedad"]):"";
$operatividad=isset($_POST["operatividad"])?limpiarCadena($_POST["operatividad"]):"";
$contigencia=isset($_POST["contingencia"])?limpiarCadena($_POST["contingencia"]):"";
$placacotigencia=isset($_POST["palacacont"])?limpiarCadena($_POST["palacacont"]):"";
$sistema=isset($_POST["Sistema"])?limpiarCadena($_POST["Sistema"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$fechahora=date("Y-m-d H:i:s");
$table_name = "NOVEDAD_FLOTA";


function minutosTranscurridos($fecha_i,$fecha_f)
           {
             $minutos = ((strtotime($fecha_i)-strtotime($fecha_f))/60);
             $minutos = abs($minutos); $minutos = floor($minutos);
             return $minutos;
           }

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		  
		 $fechai=date("Y/m/d H:i:s",strtotime($fechainical));        
         $fechaf=date("Y/m/d H:i:s",strtotime($frechafinal)); 
		 $horas=minutosTranscurridos($fechai,$fechaf); 
		  
		 if($operatividad=="OPERATIVO"){
		 $contigencia="NO REQUIERE";
		 $placacotigencia="N/D";	 
		 } 
		 if($contigencia=="NO REQUIERE"){
		 $placacotigencia="N/D";	 
		 } 
		  
		 if($placacotigencia==""){
		 $placacotigencia="N/D";	 
		 } 
		  
		  
		 $data_values = array('ID_VEHICULO_NOVEDAD'=>$vehiculo,"TIPO_NOVEDAD"=>$tiponovedad,"FECHA_HORA_INICIO"=>$fechainical,"FECHA_HORA_FIN"=>$frechafinal,"NOVEDAD"=>$Novedad,"ESTADO_NOVEDADA"=>1,"HORAS_TRANSCURRIDAS"=>$horas,"OPERATIVIDAD"=>$operatividad,"CONTIGENCIA"=>$contigencia,"PLACACONTIGENCIA"=>$placacotigencia,"SISTEMA"=>$sistema);
         $rspta = $Novedades->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		  
      } else {
         
		 if($operatividad=="OPERATIVO"){
		 $contigencia="NO REQUIERE";
		 $placacotigencia="N/D";	 
		 } 
		 if($contigencia=="NO REQUIERE"){
		 $placacotigencia="N/D";	 
		 } 
		  
		 if($placacotigencia==""){
		 $placacotigencia="N/D";	 
		 } 
		  
		  
		 $data_values = array('ID_VEHICULO_NOVEDAD'=>$vehiculo,"TIPO_NOVEDAD"=>$tiponovedad,"NOVEDAD"=>$Novedad,"OPERATIVIDAD"=>$operatividad,"CONTIGENCIA"=>$contigencia,"PLACACONTIGENCIA"=>$placacotigencia,"SISTEMA"=>$sistema);
        $where_condition = array('ID_NOVEDAD' => $id);
        $rspta = $Novedades->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
		
   case 'guardarNota':
    try {
		 $data_values = array('ID_NOVEDAD_NOTA'=>$id,"NOTA"=>$Novedad,"FECHANOTA"=>$fechahora,"ESTADONOTA"=>1);
         $rspta = $Novedades->insertar('NOTAS_NOVEDADES', $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro de la nota";	  
      
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;	
		
	case 'editarFechas':
    try {
		
	    $fechai=date("Y/m/d H:i:s",strtotime($fechainical));        
        $fechaf=date("Y/m/d H:i:s",strtotime($frechafinal)); 
		$horas=minutosTranscurridos($fechai,$fechaf); 
		
		$data_values = array("FECHA_HORA_INICIO"=>$fechainical,"FECHA_HORA_FIN"=>$frechafinal,"HORAS_TRANSCURRIDAS"=>$horas);
        $where_condition = array('ID_NOVEDAD' => $id);
        $rspta = $Novedades->editar($table_name, $data_values, $where_condition);
		if($rspta){
		 $data_values = array('ID_NOVEDAD_NOTA'=>$id,"NOTA"=>"Se amplia fecha final a $frechafinal de acuerdo a la siguiente novedad: ".$Novedad,"FECHANOTA"=>$fechahora,"ESTADONOTA"=>1);
         $rspta = $Novedades->insertar('NOTAS_NOVEDADES', $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro de la nota";
		}else{
			echo 'Error no se pudo editra las fechas';
		}	  
      
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;		
		
    case 'listar':
      try {
    
          $rspta = $Consulta->listarNovedadesFlota($estado);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_NOVEDAD.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="Agregar Nota"><button class="btn btn-light" onclick="mostrarnotas('.$reg->ID_NOVEDAD.')"><i class="fa fa-sticky-note-o" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->ID_NOVEDAD.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->ESTADO_NOVEDADA==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_NOVEDAD.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->ID_NOVEDAD.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
              "0"=>$reg->PLACA_VEH,
			  "1"=>$reg->TIPO_NOVEDAD,
			  "2"=>$reg->NOMBRE_SISTEMA_AFECTADO,	
			  "3"=>$reg->OPERATIVIDAD,
			  "4"=>$reg->CONTIGENCIA,
			  "5"=>$reg->PLACACONTIGENCIA,	
              "6"=>$reg->FECHA_HORA_INICIO,
			  "7"=>$reg->FECHA_HORA_FIN,
			  "8"=>round($reg->HORAS_TRANSCURRIDAS/60)." Horas",	
			  //"8"=>$reg->NOVEDAD,
				"9"=>'<SPAN title="Novedades"><button type="button" class="btn btn-light" data-toggle="modal" data-target="#modal-nota" onclick="mostrarcontenido('.$reg->ID_NOVEDAD.')"><i class="fa fa-sticky-note" style=""></i></button></SPAN>',
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
          $where_condition = array('ID_NOVEDAD'=>$id);//$_GET["estado"]);   
           $rspta = $Novedades->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('ESTADO_NOVEDADA' => 1);
            $where_condition = array('ID_NOVEDAD' => $id);
            $rspta = $Novedades->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('ESTADO_NOVEDADA' => 0);
            $where_condition = array('ID_NOVEDAD' => $id);
            $rspta = $Novedades->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		case 'select':
          try {
           $where_condition = array('ESTADO_VEHICULO'=>1);//$_GET["estado"]);   
           $rspta = $Novedades->validar('VEHICULOS', $where_condition); 
           echo "<option value=''>Seleccione vehiculo...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->ID_VEH'>$reg->PLACA_VEH</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
		
		
		case 'mostrarNovedad':
          try {
           $where_condition = array('ID_NOVEDAD'=>$id);//$_GET["estado"]);   
           $rspta = $Novedades->mostrar($table_name, $where_condition);
		   $novedadregistrada=$rspta["NOVEDAD"];
			
		   $contenido='<div class="ntexto"><h8  style="font-weight: bold;">NOVEDAD REGISTRADA: </h8>'.$novedadregistrada.'<br> <small style="font-weight: bold; float:right">'.$rspta["FECHA_HORA_INICIO"].'</small></div><br>';
			$rspta = $Consulta->Mostraranotas($id);  
		  if($rspta->num_rows>0){
		  $contenido=$contenido.'<h8  style="font-weight: bold;">NOTAS REGISTRADAS</h8><br>';	  
		  while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {
		  $contenido=$contenido.'<div class="ntexto">'.$reg->NOTA.'<br> <small style="font-weight: bold; float:right">'.$reg->FECHANOTA.'</small>	</div>';	  
		  }	  
		  }
          
		  echo $contenido;
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;

  default:
    echo "La opción seleccionada no existe";
    break;
}

?>