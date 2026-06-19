<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M29",$modulosAcceso)){	
?>

<div class="modal fade" id="modal-renovar" tabindex="-1" role="dialog" aria-labelledby="modal-nota-label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content" style="width:300px; margin-left: 60px;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modal-preguntas-label">RENOVAR</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
														
						<form name="formrenovar" id="formrenovar" method="POST" style="width:100%;">								
														  
                                                        <div class="modal-body">
                                                          
                                               <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
							                   <label>Fecha Inicio Renovación:</label>
							                   <input type="hidden" name="idpresupuesto" id="idpresupuesto2">
							                   <input type="date" class="form-control" name="fechaI" id="fechaI2" required>
                                               </div>	
                                                            
                                                        </div>
							
							<div class="modal-footer">
                                                            <button type="button" class="btn" style="background: #871F1B; color:white;" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-secondary" id="btnGuardar2">Guardar</button>
                                                        </div>
                       
						</form>  
                                                    </div>
                                                </div>
                                            </div>

<?php
include('header.php');	
?>	

 <div id="page-content-wrapper" style="">
	 
	 
	 
	 
               <div class="x_panel" style="background-color: white;color: black;">
          
          <div class="x_content">
			  
			  
			  
		<div class=" shadow p-3  mb-5 bg-white rounded">
		   <SPAN title="Agregar Registro" style="float:right">
              <!--span - abarcar. Es un contenedor en línea. Sirve para aplicar estilo al texto o agrupar elementos en línea.-->
              <button class="btn" style="background: #871F1B; color:white;" id="btnagregar" onclick="mostrarform(true)">
                <!--Al hacer click, muestra el formulario-->
                <i class="fa fa-plus-square">
                  <!--Muestra el texto marcado con un estilo en cursiva o italica.-->
                </i> Nuevo Registro
              </button>
            </SPAN>
			
		 <div class="x_title  ">
            <h1>PRESUPUESTO <small>Registro</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros" method="POST">
                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Proveedor:</label>
                                <input type="hidden"  name="idpresupuesto" id="idpresupuesto" >
								<input type="hidden"  name="valor" id="valor" >  
                                <select class="form-control" name="proveedor" id="proveedor" required>
								</select>	
                              </div>
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Unidad Negocio:</label>
                                <select class="form-control" name="uen" id="uen" required>
								</select>
                              </div>	
                            
                            </div>
				
				            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Aplica contrato:</label>
                                <select class="form-control" name="aplica" id="aplica" required>
							    <option value="">Seleccione...</option>
								<option value="SI">SI</option>
								<option value="NO">NO</option>	
								</select>
                              </div>
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Num Contrato:</label>
                                <input type="text" class="form-control" name="contrato" id="contrato" min="0"  autofocus>
                              </div>	
                            
                            </div>
				
				            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Empresa:</label>
                                <select class="form-control" name="empresa" id="empresa" required>
								</select>
                              </div>
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Tiempo de cobro:</label>
                                <select class="form-control" name="cobro" id="cobro" required>
							    <option value="">Seleccione...</option>
								<option value="1">MENSUAL</option>
								<option value="2">BIMESTRAL</option>	
								<option value="3">TRIMESTRAL</option>	
								<option value="4">SEMESTRAL</option>
								<option value="5">ANUAL</option>		
								</select>
                              </div>	
                            
                            </div>
				
				            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Fecha inicio:</label>
                                <input type="date" class="form-control" name="fechaI" id="fechaI" required>
                              </div>
								
							   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Tipo Pago:</label>
                                <select class="form-control" name="tipoPago" id="tipoPago" required>
							    <option value="">Seleccione...</option>
								<option value="1">ANTICIPADO</option>
								<option value="2">VENCIDO</option>			
								</select>
                              </div>	
                            
                            </div>
				
				            <div class="row">	
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Detalle:</label>
                                <textarea class="form-control" name="detalle" id="detalle"></textarea>
                              </div> 
                            
                            </div> 
				
				          
				            <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded">
								
							<div class="row">
                               <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Centro Operativo:</label>
                                <select class="form-control" name="Centroop" id="Centroop" >		
								</select>
                              </div>	
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Item:</label>  
                              <select class="form-control" name="Item" id="Item" >  		
							  </select>
                              </div>
							 </div>	
				
				            <div class="row">	
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Cantidad:</label>  
                              <input type="text" class="form-control" id="cantidad" onkeypress="Numero(event)" onkeyup="puntostexto(event)">
                              </div>
								
							  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Valor Unitario:</label>
                                <input type="text" class="form-control" id="valorU" onkeypress="Numero(event)" onkeyup="puntostexto(event)" >
                              </div>	
								
								 <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12" id="botones2" >
							  <br> 
                              <SPAN title="Agregar">
                                <button class="btn btn-secondary" type="button" onclick="agregartabla()"  style="margin-top:7px;"><i class="fa fa-plus"></i> Agregar
                                </button>
                              </SPAN>       
                            </div>
							 </div>
							  
							 
							  
						<div class="panel-body table-responsive">
                                            <table id="tbdetalle" class="table table-striped table-bordered "
                                                style="width:100%">
                                                <thead>
                                                    <th>Anular</th>
													<th>Centro Operativo</th>
                                                    <th>Item</th>
                                                    <th>Cantidad</th>
													<th>Valor Unitario</th>
													<th>Valor Total</th>
                                                </thead>
                                                <tbody id="infod">

                                                </tbody>
                                            </table>
                                        </div>	  
				
                  </div>
				
				           
                          
                             <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="botones2">
                              <SPAN title="Guardar Registro">
                                <button class="btn btn-secondary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar
                                </button>
                              </SPAN>       
<!--ejecuta cancelar formulario-->
                              <SPAN title="Cancelar Registro">
                                <button class="btn" style="background: #871F1B; color:white;" onclick="cancelarform()"  type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar
                                </button>
                              </SPAN>
                            </div>
                        </div>
                          </form>
                      
                 
                 
              </div>
			  
		  
			   
            <!-- Listado Registros-->

            <!-- centro listado de articulos-->
        
            <!-- end form for validations -->
			  
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
        
      
            
              
                <table border="0" cellspacing="0" cellpadding="0" id="tbllistado" style="width:100%;">
                    <thead>
                        <tr>
                            <th >PROVEEDOR</th>
                            <th >UNIDAD DE NEGOCIO</th>
							<th >EMPRESA</th>
							<th >FECHA INICIO</th>
						    <th >VALOR PRESUPUESTO</th>	
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
	 
	 
<!-- Contenido aqui va todo el DIV del contenido.. -->

<!-- /page content -->

<!-- footer content -->
<?php

include('footer.php');

?>

<script type="text/javascript" src="../Ajax/PresupuestoAjax.js"></script>

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