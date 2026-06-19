<?php
session_start();
require "../Modelo/ConfiguracionModelo.php";
require "../Modelo/ConsultasAnidadas.php";
$ejecucion = new configuracion();
$Consulta = new consultas();

$Proyecto=isset($_POST["proyecto"])?limpiarCadena($_POST["proyecto"]):"";
$FechaIR=isset($_POST["fechaInicio"])?limpiarCadena($_POST["fechaInicio"]):"";
$FechaFR=isset($_POST["fechaFin"])?limpiarCadena($_POST["fechaFin"]):"";

switch ($_GET["op"]) {
  
   case 'Reporte':
        try {
        $arreglo_general= [];
        $arreglo_general[]=["PROYECTO","CENRTRO OPERATIVO","SERVICIO","FECHA SERVICIO","TIPO EVENTO","UF","TIPO VEHUCULO CONSECIÓN","PERSONAS ATENDIDAS","HERIDOS GRAVES","HERIDOS LEVES","HERIDOS ILESOS","FALLECIDOS","HORA REPORTE","HORA LLEGADA","HORA INICIO TRASLADO","HORA FIN TRASLADO","HORA FIN SERVICIO"];

        $rspta = $Consulta->ReporteAsistencialGruaGeneral($Proyecto,$FechaIR,$FechaFR);
         while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
         {
        $servicio="";
         switch($reg->DATOS_SERVICIO){
            case "1":
           $servicio="GRUA";
              break;
            case "2":
            $servicio="CARRO TALLER";
              break;
            case "3":
            $servicio="INSPECTOR VIAL";
              break;
            case "4":
            $servicio="ACCIDENTE";
              break;
          }
          $arreglo_general[]=[
            $reg->nombreProyecto, 
            $reg->NombreCentroOP, 
            $servicio, 
            $reg->FECHA_SERVICIO,
            $reg->NOMBRE_TIPO_EVENTO,
            $reg->UF,
            $reg->NOM_TIPO_VEHICULO,
            "0",
            "0",
            "0",
            "0",
            "0",
            $reg->HORA_REPORTE,
            $reg->HORA_LLEGADA,
            $reg->HORA_INICIO_TRASLADO,
            $reg->HORA_FIN_TRASLADO,
            $reg->HORA_FIN_SERVICIO,
          ];  
         }
          $rspta = $Consulta->ReporteAmbulanciaGeneral($Proyecto,$FechaIR,$FechaFR);
          while ($reg=$rspta->fetch_object())//mientras exista objeto en la respuesta
          {
            $arreglo_general[]=[
            $reg->nombreProyecto, 
            $reg->NombreCentroOP, 
            "AMBULANCIA", 
            $reg->FECHA_SERVICIO,
            $reg->NOMBRE_TIPO_EVENTO,
            $reg->UF,
            $reg->NOM_TIPO_VEHICULO,
            $reg->NUM_PERSONA_ATENDIDAS,
            $reg->HERIDOS_GRAVES,
            $reg->HERIDOS_LEVES,
            $reg->HERIDOS_ILESOS,
            $reg->FALLECIDOS,
            $reg->HORA_REPORTE,
            $reg->HORA_LLEGADA,
            $reg->HORA_INICIO_TRASLADO,
            $reg->HORA_FIN_TRASLADO,
            $reg->HORA_FIN_SERVICIO,
          ];  

          }

          echo json_encode(array("general"=>$arreglo_general)); 
            
        } catch (\Throwable $th) {
          echo http_response_code(400) . " Error durante el proceso: " . $th->getMessage();
        }
        break;	 

        case 'SelectProyecto':
        try {
           $where_condition = array('estadoProyecto'=>1);//$_GET["estado"]);   
           $rspta = $ejecucion->validar('Proyectos', $where_condition); 
           echo "
           <option value=''>Seleccione Centro operación...</option>
           <option value='todos'>Todos</option>";
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