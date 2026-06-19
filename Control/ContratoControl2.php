<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$Contrato = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["Idcontrato"])?limpiarCadena($_POST["Idcontrato"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$uen=isset($_POST["uen"])?limpiarCadena($_POST["uen"]):"";
$empresa=isset($_POST["empresa"])?limpiarCadena($_POST["empresa"]):"";
$cliente=isset($_POST["Cliente"])?limpiarCadena($_POST["Cliente"]):"";
$Estadocontrato=isset($_POST["EstadoContrato"])?limpiarCadena($_POST["EstadoContrato"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";

$fechai=isset($_POST["fechainicio"])?limpiarCadena($_POST["fechainicio"]):"";
$fechaf=isset($_POST["fechafinal"])?limpiarCadena($_POST["fechafinal"]):"";
$valorm=isset($_POST["valorm"])?limpiarCadena(str_replace(".","",$_POST["valorm"])):"";
$valort=isset($_POST["valort"])?limpiarCadena(str_replace(".","",$_POST["valort"])):"";
$nos=isset($_POST["nos"])?limpiarCadena($_POST["nos"]):"";
$tarifa=isset($_POST["tarifa"])?limpiarCadena($_POST["tarifa"]):"";
$objeto=isset($_POST["objeto"])?limpiarCadena($_POST["objeto"]):"";

$table_name = "contrato";

switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)){
		  
		 $data_values = array('NombreContrato' =>$nombre,'IDUenContratro'=>$uen,'IDEmpresaContrato'=>$empresa,'IDClienteContrato'=>$cliente,'idestadocontrato'=>$Estadocontrato);
         $rspta = $Contrato->insertar_id($table_name, $data_values);  
		 
		 if($rspta){
		 $id=$rspta;	 
		  $data_values = array('IDContratoDetalle' =>$id,'NoOS'=>$nos,'FechaInicio'=>$fechai,'fechaFinal'=>$fechaf,'ObjetoContrato'=>$objeto,'ValorMensualContrato'=>$valorm,'ValorTotalContrato'=>$valort,'TipoTarifa'=>$tarifa);
         $rspta = $Contrato->insertar_id('detallecontrato', $data_values);  
		 echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";		 
		 }else{
			 echo 'No se registro el detalle del documento';
		 }  
		  
      }else {
        $data_values = array('NombreContrato' => $nombre,'IDUenContratro'=>$uen,'IDEmpresaContrato'=>$empresa,'IDClienteContrato'=>$cliente,"idestadocontrato"=>$Estadocontrato);
        $where_condition = array('IDcontrato' => $id);
        $rspta = $Contrato->editar($table_name, $data_values, $where_condition);
		  
		if($rspta){ 
		  $data_values = array('IDContratoDetalle' =>$id,'NoOS'=>$nos,'FechaInicio'=>$fechai,'fechaFinal'=>$fechaf,'ObjetoContrato'=>$objeto,'ValorMensualContrato'=>$valorm,'ValorTotalContrato'=>$valort,'TipoTarifa'=>$tarifa);
		 $where_condition = array('IDContratoDetalle' => $id);	
         $rspta = $Contrato->editar('detallecontrato', $data_values, $where_condition);
		 echo $rspta? "Registro actulizado": "Error no se actulizo el registro";	 
		 }else{
			 echo 'No se registro el detalle del documento';
		 }  
     
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
    
          $rspta = $Consulta->listarContrato($estado);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDcontrato.')"><i class="fa fa-eye" style=""></i></button></SPAN>';
			 
			 
            $data[]=array(
              "0"=>$reg->NombreContrato,
              "1"=>$reg->NombreUen,
			  "2"=>$reg->NombreEmpresa,	
			  "3"=>$reg->NombreCliente,	
			  "4"=>$reg->nombreEstado,		
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
           $rspta=$Consulta->detallecontrato($id);
           echo json_encode($rspta);
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
   
		
		 case 'select':
          try {
           $where_condition = array('EstadoUen'=>1);//$_GET["estado"]);   
           $rspta = $Contrato->validar('uen', $where_condition); 
           echo "<option value=''>Seleccione Uen...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDUen'>$reg->NombreUen</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
		
		   case 'select2':
          try {
           $where_condition = array('EstadoEmpresa'=>1);//$_GET["estado"]);   
           $rspta = $Contrato->validar('empresas', $where_condition); 
           echo "<option value=''>Seleccione Empresa...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDempresa'>$reg->NombreEmpresa</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
		
		    case 'select3':
          try {
           $where_condition = array('EtadoCliente'=>1);//$_GET["estado"]);   
           $rspta = $Contrato->validar('clientes', $where_condition); 
           echo "<option value=''>Seleccione Cliente...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDClinente'>$reg->NombreCliente</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
		
		case 'select4':
          try {
           $where_condition = array('1'=>1);//$_GET["estado"]);   
           $rspta = $Contrato->validar('estadoContrato', $where_condition); 
           echo "<option value=''>Seleccione Estado...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->Idestado'>$reg->nombreEstado</option>";
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