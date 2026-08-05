<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M41",$modulosAcceso)){	
include('header.php');
?>





 <div id="page-content-wrapper" style="">
        <div class="x_panel" style="background-color: white;color: black;">  



        <!-- Modal Generar Movimientos Contables -->
<div class="modal fade" id="modalGenerarFilas" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ACTUALIZAR MOVIMINETOS CONTABLES</h5>
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
          <small class="form-text text-muted">Máximo 15 días desde la fecha inicio.</small>
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




        <!-- Modal Generar Movimientos Contables -->
<div class="modal fade" id="modalcierremes" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">CERRAR MES CONTABLE</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="anio">Año</label>
          <select id="anio" class="form-control">
            <option value="2026">2026</option>
            <option value="2027">2027</option>
            <option value="2028">2028</option>
            <option value="2029">2029</option>  
          </select>
        </div>
        <div class="form-group">
          <label for="mes">Mes</label>
          <select id="mes" class="form-control">
            <option value="1">Enero</option>
            <option value="2">Febrero</option>
            <option value="3">Marzo</option>
            <option value="4">Abril</option>
            <option value="5">Mayo</option>
            <option value="6">Junio</option>
            <option value="7">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="btnCancelarmeses" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarmeses" onclick="guardarcierre()">Guardar</button>
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
                </i> actulizar movimientos
              </button>
            </SPAN>
            
            <SPAN title="Insertar Registro" style="float:right; margin-right: 10px;">
              <!--span - abarcar. Es un contenedor en línea. Sirve para aplicar estilo al texto o agrupar elementos en línea.-->
              <button class="btn" style="background: #871F1B; color:white;" id="btnagregar" onclick="abrirModalCierreMes()">
                <!--Al hacer click, muestra el formulario-->
                <i class="fa fa-lock">
                  <!--Muestra el texto marcado con un estilo en cursiva o italica.-->
                </i> cerrar mes
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
                      <a class="nav-item nav-link active" id="item1" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true" onclick="tablas(1)">REGISTROS</a>
                      <a class="nav-item nav-link " id="item2" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" onclick="tablas(2)">MESES CERRADOS</a>
                     
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

                    <div id="mesesCerrados">
     <table id="tbllistadomeses" class="table table-striped table-bordered table-hover" style="width:100%">
                      <thead>
                        <tr id="thead-ordenes">
                        <th style="min-width:150px;">Año</th>
                        <th style="min-width:150px;">Mes</th>
                        <th style="min-width:100px;">Estado</th>
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
	  </div>
	 
	 
<!-- Contenido aqui va todo el DIV del contenido.. -->

<!-- /page content -->

<!-- footer content -->
<?php

include('footer.php');

?>

<script type="text/javascript" src="../Ajax/movimientosContalesAjax.js"></script>

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