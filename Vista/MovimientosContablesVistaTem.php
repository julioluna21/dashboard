<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M41",$modulosAcceso)){	
include('header.php');
?>
<style>
.upload-csv-wrapper {
  position: relative;
}
.upload-csv-input {
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  position: absolute;
  z-index: -1;
}
.upload-csv-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  border: 2px dashed #c9c9c9;
  border-radius: 8px;
  background-color: #fafafa;
  padding: 22px 15px;
  cursor: pointer;
  transition: all .25s ease-in-out;
}
.upload-csv-label:hover,
.upload-csv-label.dragover {
  border-color: #871F1B;
  background-color: #fbecec;
}
.upload-csv-icon {
  font-size: 26px;
  color: #871F1B;
  margin-bottom: 8px;
}
.upload-csv-text {
  font-size: 13px;
  color: #666;
}
.upload-csv-link {
  color: #871F1B;
  font-weight: 600;
  text-decoration: underline;
}
.upload-csv-filename {
  display: none;
  margin-top: 10px;
  font-size: 13px;
  font-weight: 600;
  color: #333;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 6px 12px;
}
.upload-csv-filename.show {
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.upload-csv-filename .fa-file-text-o {
  color: #871F1B;
}
.upload-csv-remove {
  cursor: pointer;
  color: #999;
}
.upload-csv-remove:hover {
  color: #871F1B;
}

</style>

 <div id="page-content-wrapper" style="">
        <div class="x_panel" style="background-color: white;color: black;">

        <!-- Modal Generar Movimientos Contables -->
<div class="modal fade" id="modalGenerarFilas" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">REGISTRAR MOVIMINETOS CONTABLES</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="selEmpresaFilas">Empresa</label>
          <select id="selEmpresaFilas" class="form-control"></select>
        </div>
        <div class="form-group">
          <label for="fechaInicioFilas">Fecha inicio</label>
          <input type="date" id="fechaInicioFilas" class="form-control">
        </div>
        <div class="form-group">
          <label for="fechaFinFilas">Fecha fin</label>
          <input type="date" id="fechaFinFilas" class="form-control">
          <small class="form-text text-muted">Máximo 30 días desde la fecha inicio.</small>
        </div>
        <div class="form-group">
          <label for="archivoCsvFilas">Archivo CSV</label>
          <div class="upload-csv-wrapper" id="uploadCsvWrapperFilas">
            <input type="file" id="archivoCsvFilas" class="upload-csv-input" accept=".csv,text/csv" required>
            <label for="archivoCsvFilas" class="upload-csv-label" id="uploadCsvLabelFilas">
              <i class="fa fa-cloud-upload upload-csv-icon"></i>
              <span class="upload-csv-text">Arrastra el archivo aquí o <span class="upload-csv-link">selecciónalo</span></span>
              <span class="upload-csv-filename" id="uploadCsvFilenameFilas">
                <i class="fa fa-file-text-o"></i>
                <span id="uploadCsvFilenameTextFilas"></span>
                <i class="fa fa-times upload-csv-remove" id="uploadCsvRemoveFilas" title="Quitar archivo"></i>
              </span>
            </label>
          </div>
          <small class="form-text text-muted">Formato .csv</small>
        </div>
        <div id="cargandoGenerarFilas" class="text-center" style="display:none;">
          <i class="fa fa-spinner fa-spin"></i> Cargando, por favor espere...
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="btnCancelarGenerarFilas" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarGenerarFilas" onclick="guardarGenerarFilas()">Guardar</button>
      </div>
    </div>
  </div>
</div>



        <div class="x_content">
		<div class=" shadow p-3  mb-5 bg-white rounded">
		   <SPAN title="actualizar Registro" style="float:right">
              <!--span - abarcar. Es un contenedor en línea. Sirve para aplicar estilo al texto o agrupar elementos en línea.-->
              <button class="btn" style="background: #871F1B; color:white;" id="btnagregar" onclick="abrirModalGenerarFilas()">
                <!--Al hacer click, muestra el formulario-->
                <i class="fa fa-plus-square">
                  <!--Muestra el texto marcado con un estilo en cursiva o italica.-->
                </i> registrar movimientos
              </button>
            </SPAN>
            
		 <div class="x_title  ">
            <h1>Moviminetos Contables <small> Registro</small></h1>
            <h2><small id="smallFecha"> </small></h2>
            <div class="clearfix"></div>
			
          </div><br><br>
            <!-- Inicio Formulario -->
           
			<div class="panel-body" style="width:100%" id="listadoregistros">
              <div class="row" style="width:100%">
                <div style="width:100%">
                    <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist" style="width:100%">
                     
                    </div>
                  </nav>
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent" style="width:100%">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="x_content">
            
                     <div class="panel-body table-responsive" >  
						 
				     <div id="invoice">

   
    <div class="invoice overflow-auto">

    <div id="registros">
     <table id="tbllistado" class="table table-striped table-bordered table-hover" style="width:100%">
                      <thead>
                        <tr id="thead-ordenes">
                          <th style="min-width:150px;">año</th>
                          <th style="min-width:150px;">Periodo</th>
                          <th style="min-width:100px;">Empresa</th>
                          <th style="min-width:200px;">Nombre Empresa</th>
                          <th>Tipo Documento</th>
                          <th style="min-width:100px;">Docto</th>
                          <th style="min-width:100px;">Periodo Docto</th>
                          <th style="min-width:200px;">Fecha Actualizacion Docto</th>
                          <th style="min-width:200px;">Fecha Aprobacion Docto</th>
                          <th style="min-width:200px;">Fecha Anulacion Docto</th>
                          <th style="min-width:200px;">Usuario Creacion Docto</th>
                          <th style="min-width:200px;">Usuario Aprobacion Docto</th>
                          <th style="min-width:200px;">Usuario Anulacion Docto</th>
                          <th style="min-width:300px;">Notas Docto</th>
                          <th style="min-width:200px;">Fecha_docto</th>
                          <th style="min-width:200px;">Cuenta</th>
                          <th style="min-width:200px;">Nombre_auxiliar</th>
                          <th style="min-width:100px;">Cuenta_n1</th>
                          <th style="min-width:200px;">Nombre_cuenta_n1</th>
                          <th style="min-width:100px;">Cuenta_n2</th>
                          <th style="min-width:200px;">Nombre_cuenta_n2</th>
                          <th style="min-width:100px;">Cuenta_n3</th>
                          <th style="min-width:200px;">Nombre_cuenta_n3</th>
                          <th style="min-width:100px;">Cuenta_n4</th>
                          <th style="min-width:200px;">Nombre_cuenta_n4</th>
                          <th style="min-width:200px;">CO_movto</th>
                          <th style="min-width:200px;">CO </th>
                          <th style="min-width:200px;">Regional CO_movto</th>
                          <th style="min-width:200px;">Regional</th>
                          <th style="min-width:200px;">Nombre Regional</th>
                          <th style="min-width:200px;">Unidad de Negocio</th>
                          <th style="min-width:200px;">nombre Unidad de Negocio</th>
                          <th style="min-width:200px;">Tercero</th>
                          <th style="min-width:200px;">Nombre Tercero</th>
                          <th style="min-width:200px;">Grupo_ccosto</th>
                          <th style="min-width:200px;">Centro de Costo</th>
                          <th style="min-width:200px;">Nombre Centro de Costo</th>
                          <th style="min-width:200px;">Debitos</th>
                          <th style="min-width:200px;">Creditos</th>
                          <th style="min-width:200px;">Deb Libro 2</th>
                          <th style="min-width:200px;">Cred Libro 2</th>
                          <th style="min-width:200px;">Movto_libro2</th>
                        </tr>
                      </thead>
                      <tbody id="tbody-ordenes">
                      </tbody>
                    </table></div>
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
	 
	 
<!-- Contenido aqui va todo el DIV del contenido.. -->

<!-- /page content -->

<!-- footer content -->
<?php

include('footer.php');

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>ahora 
<script type="text/javascript" src="../Ajax/movimientosContalesTemAjax.js"></script>

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