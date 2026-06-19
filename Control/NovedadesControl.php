<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
setlocale(LC_ALL,'es-Es');// Activa la localización con el sistema para mostrar en español
date_default_timezone_set("America/Lima");
$Novedades = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idnovedad"])?limpiarCadena($_POST["idnovedad"]):"";
$proyecto=isset($_POST["Proyecto"])?limpiarCadena($_POST["Proyecto"]):"";
$titulo=isset($_POST["titulo"])?limpiarCadena($_POST["titulo"]):"";
$novedad=isset($_POST["novedad"])?limpiarCadena($_POST["novedad"]):"";
$fecha=isset($_POST["fecha"])?limpiarCadena($_POST["fecha"]):"";
$uen=isset($_POST["uen"])?limpiarCadena($_POST["uen"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$fechahora=date("Y-m-d H:i:s");
$fechaR=date("Y-m-d");
$table_name = "NOVEDADES";



switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		  
		 $data_values = array('TITULONOVEDAD'=>$titulo,"PROYECTONOVEDAD"=>$proyecto,"UENNOVEDAD"=>$uen,"NOVEDADREGISTRADA"=>$novedad,"FECHAREGISTRO"=>$fecha,"USUARIO_NOVEDAD"=>$_SESSION['Idcolaborador'],"ESTADO_NOVEDAD"=>1);
         $rspta = $Novedades->insertar($table_name, $data_values);
         echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";	 
		 //echo $rspta ? "Registro exitoso" : print_r($data_values);	 
      } else { 
		 $data_values = array('IDNOVEDAD_DETALLE'=>$id,"RESPUESTAS"=>$novedad,"IDUSUARIORESPUESTA"=>$_SESSION['Idcolaborador'],"FECHARESPUESTA"=>$fechahora);
        $rspta = $Novedades->insertar('DETALLE_NOVEDAD', $data_values);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
				
		
    case 'listar':
      try {
    
          $rspta = $Consulta->listarnovedadesr($estado);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            
				 $bot='<SPAN title="Mostrar"><button class="btn btn-light" data-toggle="modal" data-target="#modal-nota" onclick="mostrarcontenido('.$reg->IDNOVEDAD.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="Finalizar"><button type="button" class="btn btn-light" onclick="finalizar('.$reg->IDNOVEDAD.')"><i class="fa fa-sign-out" style=""></i></button></SPAN>';  
			  
			 if($reg->ESTADO_NOVEDAD==0){
				 $bot='<SPAN title="Mostrar"><button class="btn btn-light" data-toggle="modal" data-target="#modal-nota" onclick="mostrarcontenido('.$reg->IDNOVEDAD.')"><i class="fa fa-eye" style=""></i></button></SPAN>';  
			 }
			 $fechaf="N/D"; 
			 if($reg->FECHACIERRE!=""){
			 $fechaf=$reg->FECHACIERRE;	 
			 } 
             
			 
            $data[]=array(
              "0"=>$reg->TITULONOVEDAD,
			  "1"=>$reg->nombreProyecto,	
			  "2"=>$reg->NombreUen,
			  "3"=>$reg->FECHAREGISTRO,
			  "4"=>$fechaf,	
              "5"=>$bot
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
   
       
        case 'finalizar':
          try {
            $data_values = array('ESTADO_NOVEDAD' => 0,"FECHACIERRE"=>$fechaR);
            $where_condition = array('IDNOVEDAD' => $id);
            $rspta = $Novedades->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Novedad cerrada exitosamente": "Error no se cerro la novedad";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		case 'select':
          try {
           $where_condition = array('estadoProyecto'=>1);//$_GET["estado"]);   
           $rspta = $Novedades->validar('Proyectos', $where_condition); 
           echo "<option value=''>Seleccione Proyecto...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->Idproyctos'>$reg->nombreProyecto</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
		
		case 'select2':
          try {
           $where_condition = array('EstadoUen'=>1);//$_GET["estado"]);   
           $rspta = $Novedades->validar('uen', $where_condition); 
           echo "<option value=''>Seleccione Uen...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDUen'>$reg->NombreUen</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
		
		
		case 'mostrarNovedad':
          try {
          $where_condition = array('IDNOVEDAD'=>$id);//$_GET["estado"]);   
          $rspta = $Novedades->mostrar($table_name, $where_condition);
		  $novedadregistrada=$rspta["NOVEDADREGISTRADA"];
			
		  $contenido='<div class="ntexto"><div class="titulo"><h8  style="">'.$rspta["TITULONOVEDAD"].' </h8><br><br></div>'.$novedadregistrada.' <br><small style="font-weight: bold; float:right">'.$rspta["FECHAREGISTRO"].'</small></div><br>';
		  $rspta = $Consulta->mostrarNovedadetalle($id);  
		  if($rspta->num_rows>0){
		  $contenido=$contenido.'<h8  style="font-weight: bold;">ANOTACIONES NOVEDAD</h8><br>';	  
		  while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {
		  $contenido=$contenido.'<div class="ntexto">'.$reg->RESPUESTAS.'<br> <small style="font-weight: bold; float:right">'.$reg->FECHARESPUESTA.'</small>	</div>';	  
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