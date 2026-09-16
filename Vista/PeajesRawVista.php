<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);
if(in_array("M43",$modulosAcceso)){ // CAMBIO: código de módulo nuevo (placeholder, ver nota abajo)
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
<!-- CAMBIO: todo el bloque <style> queda IGUAL, son clases genéricas de UI, no dependen de "gasolina" -->

<div id="page-content-wrapper" style="">
    <div class="x_panel" style="background-color: white;color: black;">

    <!-- Modal Cargar CSV de Peajes -->
    <div class="modal fade" id="modalCargarPeajes" tabindex="-1" role="dialog"> <!-- CAMBIO: id -->
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">CARGAR REGISTROS DE PEAJES</h5> <!-- CAMBIO: texto -->
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="mesCargaPeajes">Mes a cargar</label> <!-- CAMBIO: id -->
              <input type="month" id="mesCargaPeajes" class="form-control"> <!-- CAMBIO: id -->
            </div>
            <div class="form-group">
              <label>Archivo Excel</label>
              <div class="upload-csv-wrapper" id="uploadCsvWrapperPeajes"> <!-- CAMBIO: id -->
                <input type="file" id="archivoCsvPeajes" class="upload-csv-input" accept=".csv" required> <!-- CAMBIO: id -->
                <label for="archivoCsvPeajes" class="upload-csv-label" id="uploadCsvLabelPeajes"> <!-- CAMBIO: id -->
                  <i class="fa fa-cloud-upload upload-csv-icon"></i>
                  <span class="upload-csv-text">Arrastra el archivo aquí o <span class="upload-csv-link">selecciónalo</span></span>
                  <span class="upload-csv-filename" id="uploadCsvFilenamePeajes"> <!-- CAMBIO: id -->
                    <i class="fa fa-file-text-o"></i>
                    <span id="uploadCsvFilenameTextPeajes"></span> <!-- CAMBIO: id -->
                    <i class="fa fa-times upload-csv-remove" id="uploadCsvRemovePeajes" title="Quitar archivo"></i> <!-- CAMBIO: id -->
                  </span>
                </label>
              </div>
              <small class="form-text text-muted">Formato .csv</small>
            </div>
            <div id="cargandoGuardarPeajes" class="text-center" style="display:none;"> <!-- CAMBIO: id -->
              <i class="fa fa-spinner fa-spin"></i> Cargando, por favor espere...
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btnCancelarPeajes" data-dismiss="modal">Cancelar</button> <!-- CAMBIO: id -->
            <button type="button" class="btn btn-primary" id="btnGuardarPeajes" onclick="guardarCargaPeajes()">Guardar</button> <!-- CAMBIO: id + nombre de función -->
          </div>
        </div>
      </div>
    </div>

    <div class="x_content">
      <div class=" shadow p-3  mb-5 bg-white rounded">

        <SPAN title="Cargar Registro" style="float:right">
          <button class="btn" style="background: #871F1B; color:white;" id="btnagregar" onclick="abrirModalCargaPeajes()"> <!-- CAMBIO: nombre de función -->
            <i class="fa fa-plus-square"></i> Cargar csv
          </button>
        </SPAN>

        <div class="x_title  ">
          <h1>Peajes <small>Registro</small></h1> <!-- CAMBIO: texto -->
          <div class="clearfix"></div>
        </div><br><br>

        <div class="panel-body table-responsive" style="width:100%" id="listadoregistros">
          <table id="tbllistado" class="table table-striped table-bordered table-hover" style="width:100%">
            <thead>
              <tr id="thead-ordenes">
                <th style="min-width:150px;">fecha_recepcion</th>
                <th style="min-width:150px;">fecha_emision</th>
                <th style="min-width:150px;">tipo_transaccion</th>
                <th style="min-width:200px;">codigo_transaccion</th>
                <th style="min-width:100px;">placa</th>
                <th style="min-width:80px;">categoria</th>
                <th style="min-width:150px;">peaje</th>
                <th style="min-width:100px;">carril</th>
                <th style="min-width:100px;">sentido</th>
                <th style="min-width:150px;">valor_inicial</th>
                <th style="min-width:150px;">valor_cobrado</th>
                <th style="min-width:150px;">valor_final</th>
                <th style="min-width:150px;">receptor_facturacion</th>
                <th style="min-width:250px;">cufe_dian</th>
                <th style="min-width:200px;">fecha_carga</th>
              </tr>
            </thead>
            <tbody id="tbody-ordenes">
            </tbody>
          </table>
        </div>

      </div>
    </div>

    </div>
</div>

<?php
include('footer.php');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>
<script type="text/javascript" src="../Ajax/PeajesRawAjax.js"></script> <!-- CAMBIO: nombre del archivo JS -->

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