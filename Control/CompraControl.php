<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";

setlocale(LC_ALL,'es-Es');// Activa la localización con el sistema para mostrar en español
date_default_timezone_set("America/Lima");
$presupuesto = new configuracion();
$Consulta = new consultas();

$id=isset($_POST["idpresupuesto"])?limpiarCadena($_POST["idpresupuesto"]):"";
$proveedor=isset($_POST["proveedor"])?limpiarCadena($_POST["proveedor"]):"";
$uen=isset($_POST["uen"])?limpiarCadena($_POST["uen"]):"";
$empresa=isset($_POST["empresa"])?limpiarCadena($_POST["empresa"]):"";
$Fechainicio=isset($_POST["fechaI"])?limpiarCadena($_POST["fechaI"]):"";
$valor=isset($_POST["valor"])?limpiarCadena($_POST["valor"]):"";
$detalle=isset($_POST["detalle"])?limpiarCadena($_POST["detalle"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "compras";
$fechaActual=date("Y-m-d H:i:s");

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		  
		$anio = date("Y", strtotime($Fechainicio));
		$mes = date("m", strtotime($Fechainicio)); 
		$where_Condition=array("MES_CERRADO>"=>$mes," and ANIO_CERRADO"=>$anio); 
		$validacion=$presupuesto->validar("meses_cerrados",$where_Condition); 
		if($validacion->num_rows<=0){
		$data_values = array('NOMBRE_ELEMENTO'=>$detalle,"FECHACOMPRA"=>$Fechainicio,"MESCOMPRA"=>$mes,"ANIOCOMPRA"=>$anio,"IDPROVEEDORCOMPRA"=>$proveedor,"IDEMPRESACOMPRA"=>$empresa,"IDUENCOMPRA"=>$uen,"VALORCOMPRA"=>$valor,"ESTADOCOMPRA"=>1);
         $rspta = $presupuesto->insertar($table_name, $data_values);
		echo $rspta? "Registro exitoso": "Error no se realizo el registro";		
		}else{
			echo "Error no se puede agregar el registro, ya que el mes de la fecha seleccionada ya esta cerrada en la gestion de gastos";
		} 
		 
      } else {
		
		$anio = date("Y", strtotime($Fechainicio));
		$mes = date("m", strtotime($Fechainicio)); 
		$where_Condition=array("MES_CERRADO>"=>$mes," and ANIO_CERRADO"=>$anio); 
		$validacion=$presupuesto->validar("meses_cerrados",$where_Condition); 
		if($validacion->num_rows<=0){
		$data_values = array('NOMBRE_ELEMENTO'=>$detalle,"FECHACOMPRA"=>$Fechainicio,"MESCOMPRA"=>$mes,"ANIOCOMPRA"=>$anio,"IDPROVEEDORCOMPRA"=>$proveedor,"IDEMPRESACOMPRA"=>$empresa,"IDUENCOMPRA"=>$uen,"VALORCOMPRA"=>$valor);
        $where_condition = array('IDCOMPRA' => $id);
        $rspta = $presupuesto->editar($table_name, $data_values, $where_condition);
        echo $rspta? "Registro actulizado": "Error no se actulizo el registro";	
		}else{
	    echo "Error no se puede agregar el registro, ya que el mes de la fecha seleccionada ya esta cerrada en la gestion de gastos";
		}
	  
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
	
		
    case 'listar':
      try {
    
          $rspta = $Consulta->ListarCompras($estado); 
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          { 
			  
			 $bot='<SPAN title="Mostrar"><button class="btn btn-light" data-toggle="modal" data-target="#modal-renovar" onclick="mostrar('.$reg->IDCOMPRA.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->IDCOMPRA.')"><i class="fa fa-trash" style=""></i></button></SPAN>';   
			  
			if($reg->ESTADOCOMPRA==0){ 
				 $bot='<SPAN title="Mostrar"><button class="btn btn-light" data-toggle="modal" data-target="#modal-renovar" onclick="mostrar('.$reg->IDCOMPRA.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->IDCOMPRA.')"><i class="fa fa-check" style=""></i></button></SPAN>';      
             }  
			 
            $data[]=array(
              "0"=>$reg->RAZON_SOCIAL,
              "1"=>$reg->NombreUen,	
			  "2"=>$reg->NombreEmpresa,	
			  "3"=>$reg->FECHACOMPRA,		
			  "4"=>"$".$reg->VALORCOMPRA,		
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
      case 'mostrar':
        try {
          $where_condition = array('IDCOMPRA'=>$id);//$_GET["estado"]);   
           $rspta = $presupuesto->mostrar($table_name, $where_condition);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('ESTADOCOMPRA' => 1);
            $where_condition = array('IDCOMPRA' => $id);
            $rspta = $presupuesto->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('ESTADOCOMPRA' => 0);
            $where_condition = array('IDCOMPRA' => $id);
            $rspta = $presupuesto->editar($table_name, $data_values, $where_condition);
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