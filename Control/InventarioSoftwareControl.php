<?php
session_start();
require_once "../Modelo/ConfiguracionModelo.php";

$InventarioSoftware=new configuracion();
$table_name="inventario_software";
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
                    $data_values=array(
                        "NOMBREAPP"=>$nombreapp,
                        "ALCANCE"=>$alcance,
                        "FECHA_ULTIMA_VERSION"=>$fecha,
                        "AREA_RESPONSABLE"=>$area,
                        "LIDER_FUNCIONAL"=>$lider,
                        "TIPO_DESARROLLO"=>$tipo,
                        "LINK_CARPETA"=>$link,
                        "ESTADO"=>1,
                    );
                    $rspta=$InventarioSoftware->insertarvh($table_name,$data_values);
                    echo $rspta ? "Registro exitoso" : "No se pudo realizar el registro";
                }else{
                    $data_values=array(
                        "NOMBREAPP"=>$nombreapp,
                        "ALCANCE"=>$alcance,
                        "FECHA_ULTIMA_VERSION"=>$fecha,
                        "AREA_RESPONSABLE"=>$area,
                        "LIDER_FUNCIONAL"=>$lider,
                        "TIPO_DESARROLLO"=>$tipo,
                        "LINK_CARPETA"=>$link,
                    );
                    $where_condition=array("ID_INVENTARIO_SOFTWARE"=>$id);
                    $rspta=$InventarioSoftware->editarvh($table_name,$data_values,$where_condition);
                    echo $rspta ? "Registro actualizado" : "No se pudo actualizar";
                }
            break;

            case 'mostrar':
                    $rspta=$InventarioSoftware->mostrar($table_name,array("ID_INVENTARIO_SOFTWARE"=>$id));
                    echo json_encode($rspta);
            break;

            case 'anular':
                    $rspta=$InventarioSoftware->editarvh($table_name,array("ESTADO"=>0),array("ID_INVENTARIO_SOFTWARE"=>$id));
                    echo $rspta ? "anulado exitoso" : "No se pudo anular el registro";
            break;

            case 'activar':
                    $rspta=$InventarioSoftware->editarvh($table_name,array("ESTADO"=>1),array("ID_INVENTARIO_SOFTWARE"=>$id));
                    echo $rspta ? "activado exitoso" : "No se pudo activar el registro";
            break;

            case 'listar':
                    // ESTADO es int(2), solo vale 0 o 1. configuracion::listar() arma el WHERE
                    // sin comillas alrededor del valor (ESTADO = $value), así que escapar
                    // comillas (limpiarCadena) no protegería nada acá -- se exige que sea un
                    // entero real, cortando cualquier inyección SQL vía este parámetro de la URL.
                    $estado = filter_var($_GET["estado"] ?? '', FILTER_VALIDATE_INT);
                    if ($estado === false) { $estado = 1; }
                    $rspta=$InventarioSoftware->listar($table_name,array("ESTADO"=>$estado));
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