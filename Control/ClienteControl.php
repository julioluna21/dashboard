<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$cliente = new configuracion();
$Consulta = new consultas();


$id=isset($_POST["idcliente"])?limpiarCadena($_POST["idcliente"]):"";
$nit=isset($_POST["nit"])?limpiarCadena($_POST["nit"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$tipo=isset($_POST["tipo"])?limpiarCadena($_POST["tipo"]):"";
$administrador=isset($_POST["administrador"])?limpiarCadena($_POST["administrador"]):"";
$estado=isset($_GET["estado"])?limpiarCadena($_GET["estado"]):"";
$socios=isset($_POST['permiso']) ? $_POST['permiso'] : false;
$table_name = "clientes";


switch ($_GET["op"]) {
  case 'guardar':
    try {
      if (empty($id)) {
		 $where_condition = array('NitCliente'=>$nit);//$_GET["estado"]); 
		 $rspta = $cliente->validar($table_name, $where_condition);  
		 if($rspta->num_rows<=0){
	     $data_values = array('NitCliente'=>$nit,"NombreCliente"=>$nombre,"TipoCliente"=>$tipo,"AdministradorCliente"=>$administrador,"EtadoCliente"=>1);
         $rspta = $cliente->insertar_id($table_name, $data_values);
		if($rspta){
		if($socios){
        foreach($socios as $selected){
		 $data_values = array('IDClienteU'=>$rspta,"IDSocioU"=>$selected,"EstadoClienteSocio"=>1);
         $rspta2 = $cliente->insertar('clientessocios', $data_values);	
         } 
		  echo "Registro exitoso";	
        }
		}else{
		 echo "No se pudo realizar el registro";	
		}	 	 
			  
		  }else{
			  echo "Error el nit ya se encuentra registrado";
		  }
		  
      } else {
        $data_values = array('NitCliente'=>$nit,"NombreCliente"=>$nombre,"TipoCliente"=>$tipo,"AdministradorCliente"=>$administrador,"EtadoCliente"=>1);
        $where_condition = array('IDClinente' => $id);
        $rspta = $cliente->editar($table_name, $data_values, $where_condition);
		if($rspta){
		$where_condition = array('IDClienteU' => $id);
        $rspta = $cliente->borrar('clientessocios', $where_condition);	
		if($socios){
        foreach($socios as $selected){
		 $data_values = array('IDClienteU'=>$id,"IDSocioU"=>$selected,"EstadoClienteSocio"=>1);
         $rspta = $cliente->insertar_id('clientessocios', $data_values);	
         } 
		  echo "Registro actulizado";	
        }
		}else{
		 echo "Error no se actulizo el registro";	
		}	  
      }
    } catch (\Throwable $th) {
      echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
    }
    break;
		
    case 'listar':
      try {
    
          $rspta = $Consulta->listarClientes($_GET["estado"]);
          $data = Array();
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {            
            $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDClinente.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="anular('.$reg->IDClinente.')"><i class="fa fa-trash" style=""></i></button></SPAN>';  
			
			if($reg->EtadoCliente==0){ 
				 $bot='<SPAN title="Editar"><button class="btn btn-light" onclick="mostrar('.$reg->IDClinente.')"><i class="fa fa-eye" style=""></i></button></SPAN> <SPAN title="anular"><button type="button" class="btn btn-light" onclick="activar('.$reg->IDClinente.')"><i class="fa fa-check" style=""></i></button></SPAN>';     
             }  
			 
            $data[]=array(
              "0"=>$reg->NitCliente,
              "1"=>$reg->NombreCliente,
			  "2"=>$reg->NombreTipoCliente,
			  "3"=>$reg->AdministradorCliente,	
              "4"=>$bot
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
          $where_condition = array('IDClinente'=>$id);//$_GET["estado"]);   
           $rspta = $cliente->mostrar($table_name, $where_condition);
			
		 $where_condition = array('IDClienteU'=>$id);//$_GET["estado"]);   
         $rspta2 = $cliente->validar('clientessocios', $where_condition);
		 $arreglo= array();
		    while ($reg=$rspta2->fetch_object())//mientras exista objeto en la respuesta
            {
               $arreglo[]=$reg;
            }		
		echo json_encode(array("dato1"=>$rspta,"dato2"=>$arreglo));
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;
        case 'activar':
          try {
            $data_values = array('EtadoCliente' => 1);
            $where_condition = array('IDClinente' => $id);
            $rspta = $cliente->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Activado": "Error no se activo el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
        case 'desactivar':
          try {
            $data_values = array('EtadoCliente' => 0);
            $where_condition = array('IDClinente' => $id);
            $rspta = $cliente->editar($table_name, $data_values, $where_condition);
            echo $rspta? "Registro Desactivado": "Error no se desactivar el registro";
          } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
          }
          break;
		
		 case 'select':
          try {
           $where_condition = array('EstadoTipoCliente'=>1);//$_GET["estado"]);   
           $rspta = $cliente->validar('tipoclientes', $where_condition); 
           echo "<option value=''>Seleccione Tipo...</option>";
            while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
            {
                  echo "<option value='$reg->IDTipoCliente'>$reg->NombreTipoCliente</option>";
            }
			  
           } catch (\Throwable $th) {
            echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
           }
          break;
		
		case 'socios':
                    $where_condition = array('EstadoSocio'=>1);//$_GET["estado"]);   
                    $rspta = $cliente->validar('socios', $where_condition); 
                    echo '<label>Socios:</label><br>';
                    while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
                    {
                       echo '<label>'.$reg->NitSocio.'-'.$reg->NombreSocio.'</label>
                    <SPAN title="'.$reg->NombreSocio.'" style="float:right">
                        <input type="checkbox" class="" name="permiso[]" id="r'.$reg->IDSocio.'" value="'.$reg->IDSocio.'"  /></SPAN><br>'; 
                    }
            break;
        
	

  default:
    echo "La opción seleccionada no existe";
    break;
}

?>