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
$aplicaContrato=isset($_POST["aplica"])?limpiarCadena($_POST["aplica"]):"";
$numcontrato=isset($_POST["contrato"])?limpiarCadena($_POST["contrato"]):"";
$empresa=isset($_POST["empresa"])?limpiarCadena($_POST["empresa"]):"";
$tiempoCobro=isset($_POST["cobro"])?limpiarCadena($_POST["cobro"]):"";
$Fechainicio=isset($_POST["fechaI"])?limpiarCadena($_POST["fechaI"]):"";
$valor=isset($_POST["valor"])?limpiarCadena($_POST["valor"]):"";
$TipoPago=isset($_POST["tipoPago"])?limpiarCadena($_POST["tipoPago"]):"";
$detalle=isset($_POST["detalle"])?limpiarCadena($_POST["detalle"]):"";
$detalleItems=isset($_POST["DatosDetalle"])?json_decode($_POST['DatosDetalle'], true):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$table_name = "presupuesto";
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
		 $data_values = array('ID_PROVEEDOR_PRS'=>$proveedor,"UNIDAD_NEGOCIO_PRS"=>$uen,"EMPRESA_PRS"=>$empresa,"APLICA_CONTRATO"=>$aplicaContrato,"NOCONTRATO_PRS"=>$numcontrato,"TIEMPO_COBRO"=>$tiempoCobro,"VALOR_PRESUPUESTO"=>$valor,"DETALLE_PRESUPUESTO"=>$detalle,"FECHA_INICIO"=>$Fechainicio,"TIPO_PAGO"=>$TipoPago,"ESTADO_PRESUPUESTO"=>1);
         $rspta = $presupuesto->insertar_id($table_name, $data_values);
         if($rspta){
	    $idpr=$rspta;		 
		$meses="";	 
		switch($tiempoCobro){
			case 1:
			$meses=1;	
			break;
			case 2:
			$meses=2;	
			break;
			case 3:
			$meses=3;	
			break;
			case 4:
			$meses=6;	
			break;	
			case 5:
		    $meses=12;	
			break;	
		}	 	
			 
		 $anio2=$anio;	 
		 $fechasuma=$Fechainicio;
		 if($TipoPago==1){
		 $data_values = array('ID_PRESUPUESTOM'=>$idpr,"ANIOP"=>$anio,"MESP"=>$mes,"FECHA_INICIALM"=>$Fechainicio);	 
		 $rspta = $presupuesto->insertar("meses_presupuesto", $data_values);	 
		 }
		 $fecha=""; 
		 while($anio==$anio2){
			 
		 $suma="+".$meses." month";
         $fecha = new DateTime($fechasuma);
         $fecha->modify($suma);
         $fecha=$fecha->format("Y-m-d");
		 $anio2 = date("Y", strtotime($fecha));
		 $mes = date("m", strtotime($fecha));	 
		 $fechasuma=$fecha;	 
		 $data_values = array('ID_PRESUPUESTOM'=>$idpr,"ANIOP"=>$anio2,"MESP"=>$mes,"FECHA_INICIALM"=>$Fechainicio);	 
		 $rspta = $presupuesto->insertar("meses_presupuesto", $data_values);	 	  
		 }
			 
		$data_values = array('RENOVACION' => $fecha);
        $where_condition = array('ID_PRESUPUESTO ' => $idpr);
        $rspta = $presupuesto->editar($table_name, $data_values, $where_condition);
		
		if($rspta){
			
		foreach ($detalleItems as $item) {
        $data_values = array('ID_PRESUPUESTO_DETALLE'=>$idpr,"ITEM_PRESUPUESTO"=>$item['iditem'],"CENTRO_OPERATIVO"=>$item['idcentro'],"CANTIDAD"=>$item['cantidad'],"VALOR_UNITARIO"=>$item['valorU'],"VALOR_TOTAL"=>$item['valorT']);	 
		$rspta = $presupuesto->insertar("detalle_presupuesto", $data_values);	
        }	
	    echo "Registro exitoso ";
		}else{
			echo "Error no se registro la fecha de renovacion";	
		}	 

		 }else{
			 echo "Error no se pudo realizar el registro";
		 }	 
			 
			 
		 }else{
			 echo "Error cambie la fecha de inicio al mes superior al ultimo cerrado en la gestión del gasto";
		 } 
		
		 
      } else {
		
		$where_condition = array('ID_PRESUPUESTO'=>$id);//$_GET["estado"]);   
        $rspta = $presupuesto->mostrar($table_name, $where_condition);	
	    $fechai=$rspta['FECHA_INICIO'];
		$tipoP=$rspta['TIEMPO_COBRO'];	
		  
		if($fechai==$Fechainicio and $tipoP==$tiempoCobro){
		$data_values = array('ID_PROVEEDOR_PRS'=>$proveedor,"UNIDAD_NEGOCIO_PRS"=>$uen,"EMPRESA_PRS"=>$empresa,"APLICA_CONTRATO"=>$aplicaContrato,"NOCONTRATO_PRS"=>$numcontrato,"VALOR_PRESUPUESTO"=>$valor,"DETALLE_PRESUPUESTO"=>$detalle,"TIPO_PAGO"=>$TipoPago);
        $where_condition = array('ID_PRESUPUESTO' => $id);
        $rspta = $presupuesto->editar($table_name, $data_values, $where_condition);			
		if($rspta){
		$where_condition = array('ID_PRESUPUESTO_DETALLE'=>$id);
		$rspta = $presupuesto->borrar("detalle_presupuesto", $where_condition);		
		foreach ($detalleItems as $item) {
        $data_values = array('ID_PRESUPUESTO_DETALLE'=>$id,"ITEM_PRESUPUESTO"=>$item['iditem'],"CENTRO_OPERATIVO"=>$item['idcentro'],"CANTIDAD"=>$item['cantidad'],"VALOR_UNITARIO"=>$item['valorU'],"VALOR_TOTAL"=>$item['valorT']);	 
		$rspta = $presupuesto->insertar("detalle_presupuesto", $data_values);	
        }	
	    echo  "Registro actulizado";
		}else{
		echo "Error no se actulizo el registro";	
		}		
			
		}else{
			
		if($fechai<=$Fechainicio){	
		
		$anio = date("Y", strtotime($Fechainicio));
		$mes = date("m", strtotime($Fechainicio));
		
		$where_Condition=array("MES_CERRADO>"=>$mes," and ANIO_CERRADO"=>$anio); 
		$validacion=$presupuesto->validar("meses_cerrados",$where_Condition); 
		if($validacion->num_rows<=0){
		
		$rspta = $Consulta->PresupuestoValidar($anio, $id); 
			
		if($rspta->num_rows<=0){
			
		$where_condition = array('ID_PRESUPUESTOM'=>$id," and FECHA_INICIALM"=>$fechai);
		$rspta = $presupuesto->borrar("meses_presupuesto",$where_condition);
		$data_values = array('ID_PROVEEDOR_PRS'=>$proveedor,"UNIDAD_NEGOCIO_PRS"=>$uen,"EMPRESA_PRS"=>$empresa,"APLICA_CONTRATO"=>$aplicaContrato,"NOCONTRATO_PRS"=>$numcontrato,"TIEMPO_COBRO"=>$tiempoCobro,"VALOR_PRESUPUESTO"=>$valor,"DETALLE_PRESUPUESTO"=>$detalle,"FECHA_INICIO"=>$Fechainicio,"TIPO_PAGO"=>$TipoPago);
        $where_condition = array('ID_PRESUPUESTO' => $id);
        $rspta = $presupuesto->editar($table_name, $data_values, $where_condition);	
		if($rspta){	 
		$meses="";	 
		switch($tiempoCobro){
			case 1:
			$meses=1;	
			break;
			case 2:
			$meses=2;	
			break;
			case 3:
			$meses=3;	
			break;
			case 4:
			$meses=6;	
			break;	
			case 5:
		    $meses=12;	
			break;	
		}	 	
			 
		 $anio = date("Y", strtotime($Fechainicio));
		 $mes = date("m", strtotime($Fechainicio)); 
		 $anio2=$anio;	 
		 $fechasuma=$Fechainicio;
		 if($TipoPago==1){
		 $data_values = array('ID_PRESUPUESTOM'=>$id,"ANIOP"=>$anio,"MESP"=>$mes,"FECHA_INICIALM"=>$Fechainicio);	 
		 $rspta = $presupuesto->insertar("meses_presupuesto", $data_values);	 
		 }
		 $fecha=""; 
		 while($anio==$anio2){
			 
		 $suma="+".$meses." month";
         $fecha = new DateTime($fechasuma);
         $fecha->modify($suma);
         $fecha=$fecha->format("Y-m-d");
		 $anio2 = date("Y", strtotime($fecha));
		 $mes = date("m", strtotime($fecha));	 
		 $fechasuma=$fecha;	 
		 $data_values = array('ID_PRESUPUESTOM'=>$id,"ANIOP"=>$anio2,"MESP"=>$mes, "FECHA_INICIALM"=>$Fechainicio);	 
		 $rspta = $presupuesto->insertar("meses_presupuesto", $data_values);	 	  
		 }
			 
		$data_values = array('RENOVACION' => $fecha);
        $where_condition = array('ID_PRESUPUESTO ' => $id);
        $rspta = $presupuesto->editar($table_name, $data_values, $where_condition);	  
		if($rspta){
		$where_condition = array('ID_PRESUPUESTO_DETALLE'=>$id);
		$rspta = $presupuesto->borrar("detalle_presupuesto", $where_condition);		
		foreach ($detalleItems as $item) {
        $data_values = array('ID_PRESUPUESTO_DETALLE'=>$id,"ITEM_PRESUPUESTO"=>$item['iditem'],"CENTRO_OPERATIVO"=>$item['idcentro'],"CANTIDAD"=>$item['cantidad'],"VALOR_UNITARIO"=>$item['valorU'],"VALOR_TOTAL"=>$item['valorT']);	 
		$rspta = $presupuesto->insertar("detalle_presupuesto", $data_values);	
        }	
	    echo "Registro editado exitosamente";	
		}else{
			echo "Error no se registro la fecha de renovacion";	
		}		
			
		 }else{
			 echo "Error no se pudo editar el registro";
		 }		
			
			
		}else{
	    
		$reg=$rspta->fetch_object();	
		$mesconsulta=$reg->MES_EJECUCION;
		if($mes>$mesconsulta){
			
		
		$rspta = $Consulta->BorarmeseI($id,$anio,$mes);
		$rspta = $Consulta->BorarmeseF($id,$anio,$fechai);	
		$data_values = array('ID_PROVEEDOR_PRS'=>$proveedor,"UNIDAD_NEGOCIO_PRS"=>$uen,"EMPRESA_PRS"=>$empresa,"APLICA_CONTRATO"=>$aplicaContrato,"NOCONTRATO_PRS"=>$numcontrato,"TIEMPO_COBRO"=>$tiempoCobro,"VALOR_PRESUPUESTO"=>$valor,"DETALLE_PRESUPUESTO"=>$detalle,"FECHA_INICIO"=>$Fechainicio,"TIPO_PAGO"=>$TipoPago);
        $where_condition = array('ID_PRESUPUESTO' => $id);
        $rspta = $presupuesto->editar($table_name, $data_values, $where_condition);	
		if($rspta){	 
		$meses="";	 
		switch($tiempoCobro){
			case 1:
			$meses=1;	
			break;
			case 2:
			$meses=2;	
			break;
			case 3:
			$meses=3;	
			break;
			case 4:
			$meses=6;	
			break;	
			case 5:
		    $meses=12;	
			break;	
		}	 	
			 
		 $anio = date("Y", strtotime($Fechainicio));
		 $mes = date("m", strtotime($Fechainicio)); 
		 $anio2=$anio;	 
		 $fechasuma=$Fechainicio;
		 if($TipoPago==1){
		 $data_values = array('ID_PRESUPUESTOM'=>$id,"ANIOP"=>$anio,"MESP"=>$mes,"FECHA_INICIALM"=>$Fechainicio);	 
		 $rspta = $presupuesto->insertar("meses_presupuesto", $data_values);	 
		 }
		 $fecha=""; 
		 while($anio==$anio2){
			 
		 $suma="+".$meses." month";
         $fecha = new DateTime($fechasuma);
         $fecha->modify($suma);
         $fecha=$fecha->format("Y-m-d");
		 $anio2 = date("Y", strtotime($fecha));
		 $mes = date("m", strtotime($fecha));	 
		 $fechasuma=$fecha;	 
		 $data_values = array('ID_PRESUPUESTOM'=>$id,"ANIOP"=>$anio2,"MESP"=>$mes,"FECHA_INICIALM"=>$Fechainicio);	 
		 $rspta = $presupuesto->insertar("meses_presupuesto", $data_values);	 	  
		 }
			 
		$data_values = array('RENOVACION' => $fecha);
        $where_condition = array('ID_PRESUPUESTO ' => $id);
        $rspta = $presupuesto->editar($table_name, $data_values, $where_condition);	 
		if($rspta){
		$where_condition = array('ID_PRESUPUESTO_DETALLE'=>$id);
		$rspta = $presupuesto->borrar("detalle_presupuesto", $where_condition);		
		foreach ($detalleItems as $item) {
        $data_values = array('ID_PRESUPUESTO_DETALLE'=>$id,"ITEM_PRESUPUESTO"=>$item['iditem'],"CENTRO_OPERATIVO"=>$item['idcentro'],"CANTIDAD"=>$item['cantidad'],"VALOR_UNITARIO"=>$item['valorU'],"VALOR_TOTAL"=>$item['valorT']);	 
		$rspta = $presupuesto->insertar("detalle_presupuesto", $data_values);	
        }	
	    echo "Registro editado exitosamente";	
		}else{
			echo "Error no se registro la fecha de renovacion";	
		}		 
		 }else{
			 echo "Error no se pudo editar el registro";
		 }		
			
			
		}else{
			echo "Error debe cambiar la fecha de inicio a un mes mayor al ultimo registrado como facturado";
		}	
			
		}	
			
		}else{
			echo "Error cambie la fecha de inicio al mes superior al ultimo cerrado en la gestión del gasto";
			
		}	 
	 
		 }else{
			echo "Erro la fecha seleccionada es menor a la ultima registrada";
		}	
			
		
			
		} 
		  
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
	case 'Renovar':
		
		 try {
		
	    $anio = date("Y", strtotime($Fechainicio));
	    $mes = date("m", strtotime($Fechainicio));
	
		$where_Condition=array("MES_CERRADO>"=>$mes," and ANIO_CERRADO"=>$anio); 
		$validacion=$presupuesto->validar("meses_cerrados",$where_Condition); 
		
		if($validacion->num_rows<=0){
			
		$where_condition = array('ID_PRESUPUESTO'=>$id);//$_GET["estado"]);   
    $rspta = $presupuesto->mostrar($table_name, $where_condition);	
	$TipoPago=$rspta['TIEMPO_COBRO'];
	$tiempoCobro=$rspta['TIPO_PAGO'];
	if($rspta['RENOVACION']<=$Fechainicio){
		
	$meses="";	 
		switch($tiempoCobro){
			case 1:
			$meses=1;	
			break;
			case 2:
			$meses=2;	
			break;
			case 3:
			$meses=3;	
			break;
			case 4:
			$meses=6;	
			break;	
			case 5:
		    $meses=12;	
			break;	
		}	 	
			 
		 $anio2=$anio;	 
		 $fechasuma=$Fechainicio;
		 if($TipoPago==1){
		 $data_values = array('ID_PRESUPUESTOM'=>$id,"ANIOP"=>$anio,"MESP"=>$mes,"FECHA_INICIALM"=>$Fechainicio);	 
		 $rspta = $presupuesto->insertar("meses_presupuesto", $data_values);	 
		 }
		 $fecha=""; 
		 while($anio==$anio2){
			 
		 $suma="+".$meses." month";
         $fecha = new DateTime($fechasuma);
         $fecha->modify($suma);
         $fecha=$fecha->format("Y-m-d");
		 $anio2 = date("Y", strtotime($fecha));
		 $mes = date("m", strtotime($fecha));	 
		 $fechasuma=$fecha;	 
		 $data_values = array('ID_PRESUPUESTOM'=>$id,"ANIOP"=>$anio2,"MESP"=>$mes,"FECHA_INICIALM"=>$Fechainicio);	 
		 $rspta = $presupuesto->insertar("meses_presupuesto", $data_values);	 	  
		 }
			 
		$data_values = array('RENOVACION' => $fecha);
        $where_condition = array('ID_PRESUPUESTO ' => $id);
        $rspta = $presupuesto->editar($table_name, $data_values, $where_condition);	 
		echo "Renovación exitoso";	 	
		
		
	}else{
		echo "Error la fecha seleccionada es menor a meses ya facturados";
	}	
			
		}else{
			echo "Error cambie la fecha de inicio al mes superior al ultimo cerrado en la gestión del gasto";
		}
			 
		 } catch (\Throwable $th) {
        echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
      }	 
		
	break;	
		
    case 'listar':
      try {
    
          $rspta = $Consulta->listarPresupuesto($estado); 
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          { 
			  
			  
			if($fechaActual>$reg->RENOVACION){
			$bot='<SPAN title="Renovar"><button class="btn btn-light" data-toggle="modal" data-target="#modal-renovar" onclick="renovar('.$reg->ID_PRESUPUESTO.')"><i class="fa fa-repeat" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->ID_PRESUPUESTO.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  	
				
			}else{
			 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->ID_PRESUPUESTO.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->ID_PRESUPUESTO.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  	
			}  
	
			if($reg->ESTADO_PRESUPUESTO==0){ 
				 $bot='<SPAN title="Activar"><button type="button" class="btn btn-light" onclick="activar('.$reg->ID_PRESUPUESTO.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
              "0"=>$reg->RAZON_SOCIAL,
              "1"=>$reg->NombreUen,	
			  "2"=>$reg->NombreEmpresa,	
			  "3"=>$reg->FECHA_INICIO,		
			  "4"=>"$".number_format($reg->VALOR_PRESUPUESTO),		
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
          $where_condition = array('ID_PRESUPUESTO'=>$id);//$_GET["estado"]);   
           $rspta = $presupuesto->mostrar($table_name, $where_condition);
			
		    $datos=array();
            $rspta2 = $Consulta->detallePresuesto($id);
			while($reg=$rspta2->fetch_object()){
			$datos[]=$reg;	
			}

           echo json_encode(array("general"=>$rspta,"detalle"=>$datos));
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
		
		
        case 'activar':
          try {
            $data_values = array('ESTADO_PRESUPUESTO' => 1);
            $where_condition = array('ID_PRESUPUESTO' => $id);
            $rspta = $presupuesto->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('ESTADO_PRESUPUESTO' => 0);
            $where_condition = array('ID_PRESUPUESTO' => $id);
            $rspta = $presupuesto->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		case 'select':
          try {
           $where_condition = array('ESTADO_PROVEEDOR'=>1);//$_GET["estado"]);   
           $rspta = $presupuesto->validar('proveedores', $where_condition); 
           echo "<option value=''>Seleccione proveedor...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->ID_PROVEEDOR'>$reg->RAZON_SOCIAL</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
		
		case 'select2':
          try {
           $where_condition = array('EstadoUen'=>1);//$_GET["estado"]);   
           $rspta = $presupuesto->validar('uen', $where_condition); 
           echo "<option value=''>Seleccione unidad de negocio...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDUen'>$reg->NombreUen</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
		
		case 'select3':
          try {
           $where_condition = array('EstadoEmpresa'=>1);//$_GET["estado"]);   
           $rspta = $presupuesto->validar('empresas', $where_condition); 
           echo "<option value=''>Seleccione empresa...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDempresa'>$reg->NombreEmpresa</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
		
		case 'select4':
          try {
           $where_condition = array('EstadoCentroOP'=>1);//$_GET["estado"]);   
           $rspta = $presupuesto->validar('centrooperativo', $where_condition); 
           echo "<option value=''>Seleccione Centro Operativo...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDCentroOP'>$reg->NombreCentroOP</option>";
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