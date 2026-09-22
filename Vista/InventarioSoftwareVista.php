<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);
if(in_array("M44",$modulosAcceso)){
include('header.php');
?>

 <div id="page-content-wrapper" style="">

               <div class="x_panel" style="background-color: white;color: black;">

          <div class="x_content">

			<div class=" shadow p-3  mb-5 bg-white rounded">
			   <SPAN title="Agregar Registro" style="float:right">
              <button class="btn" style="background: #871F1B; color:white;" id="btnagregar" onclick="mostrarform(true)">
                <i class="fa fa-plus-square">
                </i> Nuevo Registro
              </button>
            </SPAN>
			 <div class="x_title  ">
            <h1>Inventario de Software <small>Registro</small></h1>
            <div class="clearfix"></div>

          </div><br><br>

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">

            <form name="formregistros" id="formregistros" method="POST">
                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Nombre Aplicación:</label>
                                <input type="hidden" class="form-control" name="id" id="id" >
                                <input type="text" class="form-control" name="nombreapp" id="nombreapp" required autofocus>
                              </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Fecha Última Versión:</label>
                                <input type="date" class="form-control" name="fecha" id="fecha">
                              </div>
                            </div>

                            <div class="row">
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Alcance:</label>
                                <textarea class="form-control" name="alcance" id="alcance"></textarea>
                              </div>
                            </div>

                            <div class="row">
                              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Área Responsable:</label>
                                <input type="text" class="form-control" name="area" id="area">
                              </div>
                              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Líder Funcional:</label>
                                <input type="text" class="form-control" name="lider" id="lider">
                              </div>
                              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Tipo de Desarrollo:</label>
                                <input type="text" class="form-control" name="tipo" id="tipo">
                              </div>
                            </div>

                            <div class="row">
                              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Link Carpeta:</label>
                                <input type="text" class="form-control" name="link" id="link">
                              </div>
                            </div>

                             <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="botones2">
                              <SPAN title="Guardar Registro">
                                <button class="btn btn-secondary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar
                                </button>
                              </SPAN>
                              <SPAN title="Cancelar Registro">
                                <button class="btn" style="background: #871F1B; color:white;" onclick="cancelarform()"  type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar
                                </button>
                              </SPAN>
                            </div>
                        </div>
                          </form>

              </div>

            <!-- Listado Registros-->
				<div class="panel-body" style="width:100%" id="listadoregistros">
              <div class="row" style="width:100%">
                <div style="width:100%">
                  <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist" style="width:100%">
                      <a class="nav-item nav-link active" id="item1" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true" onclick="listar(1)">ACTIVOS</a>
                      <a class="nav-item nav-link " id="item2" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" onclick="listar(0)">INACTIVOS</a>
                    </div>
                  </nav>
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent" style="width:100%">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="x_content">
                     <div class="panel-body table-responsive" >
					     <div id="invoice">
    <div class="invoice overflow-auto">
                <table border="0" cellspacing="0" cellpadding="0" id="tbllistado">
                    <thead>
                        <tr>
                            <th>NOMBRE APP</th>
                            <th>ALCANCE</th>
                            <th>FECHA ÚLTIMA VERSIÓN</th>
                            <th>ÁREA RESPONSABLE</th>
                            <th>LÍDER FUNCIONAL</th>
                            <th>TIPO DESARROLLO</th>
                            <th>CARPETA</th>
                            <th>ESTADO</th>
                            <th style="min-width: 150px;">ACCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
    </div>
</div>
                      </div>

                  </div>

                    </div>

                  </div>

              </div>
        </div>

            </div>
  </div>
          </div>
        </div>
	  </div>

<?php

include('footer.php');

?>

<script type="text/javascript" src="../Ajax/InventarioSoftwareAjax.js"></script>

<?php
}else{
  echo "<script>
  <!--
  window.location.replace('login.php');
  //-->
  </script>";
}
}else{
  echo "<script>
  <!--
  window.location.replace('login.php');
  //-->
  </script>";
}

?>
