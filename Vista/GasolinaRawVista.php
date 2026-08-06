<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);
if(in_array("M42",$modulosAcceso)){
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

    <!-- Modal Cargar CSV de Gasolina -->
    <div class="modal fade" id="modalCargarGasolina" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">CARGAR REGISTROS DE GASOLINA</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="mesCargaGasolina">Mes a cargar</label>
              <input type="month" id="mesCargaGasolina" class="form-control">
            </div>
            <div class="form-group">
              <label>Archivo Excel</label>
              <div class="upload-csv-wrapper" id="uploadCsvWrapperGasolina">
                <input type="file" id="archivoCsvGasolina" class="upload-csv-input" accept=".csv" required>
                <label for="archivoCsvGasolina" class="upload-csv-label" id="uploadCsvLabelGasolina">
                  <i class="fa fa-cloud-upload upload-csv-icon"></i>
                  <span class="upload-csv-text">Arrastra el archivo aquí o <span class="upload-csv-link">selecciónalo</span></span>
                  <span class="upload-csv-filename" id="uploadCsvFilenameGasolina">
                    <i class="fa fa-file-text-o"></i>
                    <span id="uploadCsvFilenameTextGasolina"></span>
                    <i class="fa fa-times upload-csv-remove" id="uploadCsvRemoveGasolina" title="Quitar archivo"></i>
                  </span>
                </label>
              </div>
              <small class="form-text text-muted">Formato .csv</small>
            </div>
            <div id="cargandoGuardarGasolina" class="text-center" style="display:none;">
              <i class="fa fa-spinner fa-spin"></i> Cargando, por favor espere...
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btnCancelarGasolina" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="btnGuardarGasolina" onclick="guardarCargaGasolina()">Guardar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="x_content">
      <div class=" shadow p-3  mb-5 bg-white rounded">

        <SPAN title="Cargar Registro" style="float:right">
          <button class="btn" style="background: #871F1B; color:white;" id="btnagregar" onclick="abrirModalCargaGasolina()">
            <i class="fa fa-plus-square"></i> Cargar csv
          </button>
        </SPAN>

        <div class="x_title  ">
          <h1>Gasolina <small>Registro</small></h1>
          <div class="clearfix"></div>
        </div><br><br>

        <div class="panel-body table-responsive" style="width:100%" id="listadoregistros">
          <table id="tbllistado" class="table table-striped table-bordered table-hover" style="width:100%">
            <thead>
              <tr id="thead-ordenes">
                <th style="min-width:150px;">cliente</th>
                <th style="min-width:150px;">proveedor</th>
                <th style="min-width:100px;">nro_identificacion</th>
                <th style="min-width:200px;">codigo_sap</th>
                <th>no_venta</th>
                <th style="min-width:150px;">fecha</th>
                <th style="min-width:150px;">estacion</th>
                <th style="min-width:100px;">regional</th>
                <th style="min-width:200px;">id_eds</th>
                <th style="min-width:200px;">placa</th>
                <th style="min-width:200px;">conductor</th>
                <th style="min-width:200px;">combustible</th>
                <th style="min-width:200px;">cantidad</th>
                <th style="min-width:200px;">precio</th>
                <th style="min-width:300px;">unidad_venta</th>
                <th style="min-width:200px;">total_venta</th>
                <th style="min-width:200px;">kilometraje</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>ahora
<script type="text/javascript" src="../Ajax/gasolinaRawAjax.js"></script>

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
