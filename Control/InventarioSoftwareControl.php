<?php
session_start();
require_once "../Modelo/InventarioSoftwareModelo.php";

$InventarioSoftware=new inventarioSoftware();
$id=isset($_POST["id"])? limpiarCadena($_POST["id"]):"";
$nombreapp=isset($_POST["nombreapp"])? limpiarCadena($_POST["nombreapp"]):"";
$alcance=isset($_POST["alcance"])? limpiarCadena($_POST["alcance"]):"";
$fecha=isset($_POST["fecha"])? limpiarCadena($_POST["fecha"]):"";
$area=isset($_POST["area"])? limpiarCadena($_POST["area"]):"";
$lider=isset($_POST["lider"])? limpiarCadena($_POST["lider"]):"";
$tipo=isset($_POST["tipo"])? limpiarCadena($_POST["tipo"]):"";
$link=isset($_POST["link"])? limpiarCadena($_POST["link"]):"";

switch ($_GET["op"])
    {
            case 'guardar':
                if (empty($id)) {
                    $rspta=$InventarioSoftware->insertar($nombreapp,$alcance,$fecha,$area,$lider,$tipo,$link);
                    echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";
                }else{
                    $rspta=$InventarioSoftware->editar($id,$nombreapp,$alcance,$fecha,$area,$lider,$tipo,$link);
                    echo $rspta ? "Registro actualizado" : "No se pudo actualizar";
                }
            break;

            case 'mostrar':
                    $rspta=$InventarioSoftware->mostrar($id);
                    echo json_encode($rspta);
            break;

            case 'anular':
                    $rspta=$InventarioSoftware->anular($id);
                    echo $rspta ? "anulado exitoso" : "No se pudo anular el registro";
            break;

            case 'activar':
                    $rspta=$InventarioSoftware->activar($id);
                    echo $rspta ? "activado exitoso" : "No se pudo activar el registro";
            break;

            case 'listar':
                    $rspta=$InventarioSoftware->listar($_GET["estado"]);
                    $data= Array();

                    while ($reg = $rspta->fetch_object())
                                {
                                $estado = "Activo";

                                $bot = '
                                        <button type="button" class="btn btn-light" title="Editar" onclick="mostrar('.$reg->ID_INVENTARIO_SOFTWARE.')">
                                        <i class="fa fa-eye"></i>
                                        </button>

                                        <button type="button" class="btn btn-light" title="Anular" onclick="anular('.$reg->ID_INVENTARIO_SOFTWARE.')">
                                        <i class="fa fa-trash"></i>
                                        </button>
                                ';

                                if ($reg->ESTADO == 0)
                                {
                                        $estado = "Inactivo";

                                        $bot = '
                                        <button type="button" class="btn btn-light" title="Editar" onclick="mostrar('.$reg->ID_INVENTARIO_SOFTWARE.')">
                                                <i class="fa fa-eye"></i>
                                        </button>

                                        <button type="button" class="btn btn-light" title="Activar" onclick="activar('.$reg->ID_INVENTARIO_SOFTWARE.')">
                                                <i class="fa fa-check"></i>
                                        </button>
                                        ';
                                }
                                

                        $link_html = $reg->LINK_CARPETA ? '<a href="'.$reg->LINK_CARPETA.'" target="_blank">Ver carpeta</a>' : '';

                            $data[]=array(
                                    "0"=>$reg->NOMBREAPP,
                                    "1"=>$reg->ALCANCE,
                                    "2"=>$reg->FECHA_ULTIMA_VERSION,
                                    "3"=>$reg->AREA_RESPONSABLE,
                                    "4"=>$reg->LIDER_FUNCIONAL,
                                    "5"=>$reg->TIPO_DESARROLLO,
                                    "6"=>$link_html,
                                    "7"=>$estado,
                                    "8"=>$bot,
                            );
                    }

                    $results = array(
                            "sEcho"=>1,
                            "iTotalRecords"=>count($data),
                            "iTotalDisplayRecords"=>count($data),
                            "aaData"=>$data);
                    echo json_encode($results);
            break;
    }