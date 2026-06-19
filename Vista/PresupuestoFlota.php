<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M34",$modulosAcceso)){	

?>

<div class="modal fade" id="modal-estado" tabindex="-1" role="dialog" aria-labelledby="modal-estado-label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content" style="width:300px; margin-left: 60px;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="titulo">AGREGAR RQ</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
														
						<form name="formestado" id="formestado" method="POST" style="width:100%;">								
														  
                                                        <div class="modal-body">
                                                          
                                               <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
							                   <label id="texto">Numero RQ</label>
							                   <input type="hidden" name="idPresupuesto" id="idPresupuesto2">
											   <input type="hidden" name="estado"  value="5">   
							                   <input type="text" class="form-control" name="Vestado" id="Vestado" required>
                                               </div>	
												
											   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
							                   <label>Link Cloudfleet</label>
							                   <input type="text" class="form-control" name="enlace" id="enlace">
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


<div class="modal fade" id="modal-enlace" tabindex="-1" role="dialog" aria-labelledby="modal-enlace-label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content" style="width:300px; margin-left: 60px;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="titulo">ENLACE CLOUDFLEET</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
														
						<form name="formenlace" id="formenlace" method="POST" style="width:100%;">								
														  
                                               <div class="modal-body">
												
											   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
							                   <label>Link Cloudfleet</label>
											    <input type="hidden" name="idPresupuesto" id="idPresupuesto4">	   
							                   <input type="text" class="form-control" name="enlace" id="enlace2" required>
                                               </div>	
                                                        				
                                                            
                                                        </div>
							
							<div class="modal-footer">
                                                            <button type="button" class="btn" style="background: #871F1B; color:white;" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-secondary" id="btnGuardar4">Guardar</button>
                                                        </div>
                       
						</form>  
                                                    </div>
                                                </div>
                                            </div>

<div class="modal fade" id="modal-ejecutado" tabindex="-1" role="dialog" aria-labelledby="modal-ejecutado-label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content" style="width:300px; margin-left: 60px;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">AGREGAR OC</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
														
						<form name="formEjecutado" id="formEjecutado" method="POST" style="width:100%;">								
														  
                                                        <div class="modal-body">
															
											   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
							                   <label >Numero OC</label>
											   <input type="hidden" name="idPresupuesto" id="idPresupuesto3">
											   <input type="hidden" name="estado"  value="4"> 	   
							                   <input type="text" class="form-control" name="Vestado" id="Vestado2" required>
                                               </div>					
                                                          
                                               <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
							                   <label>Valor Ejecutado</label>
							                   <input type="text" class="form-control" name="valorej" id="valorej" onkeypress="Numero(event)" onkeyup="puntostexto(event)" required>
                                               </div>	
                                                            
                                                        </div>
							
							<div class="modal-footer">
                                                            <button type="button" class="btn" style="background: #871F1B; color:white;" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-secondary" id="btnGuardar3">Guardar</button>
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
			
		<div>
			<SPAN title="NUEVO REGISTRO" style="float:right" id="btnu">
              <!--span - abarcar. Es un contenedor en línea. Sirve para aplicar estilo al texto o agrupar elementos en línea.-->
              <button class="btn" style="background: #871F1B; color:white;" id="" onclick="mostrarform(true,false)">
                <!--Al hacer click, muestra el formulario-->
				 
                <i class="fa fa-plus ">
                  <!--Muestra el texto marcado con un estilo en cursiva o italica.-->
                </i> Nuevo Registro 
              </button>
            </SPAN>
			
		   <SPAN title="VOLVER" style="float:right" id="btvolver">
              <!--span - abarcar. Es un contenedor en línea. Sirve para aplicar estilo al texto o agrupar elementos en línea.-->
              <button class="btn" style="background: #871F1B; color:white;" id="" onclick="mostrarform(false,true);tabla.ajax.reload();">
                <!--Al hacer click, muestra el formulario-->
                <i class="fa fa-arrow-left">
                  <!--Muestra el texto marcado con un estilo en cursiva o italica.-->
                </i> VOLVER
              </button>
            </SPAN>  
			</div>	
			
		 <div class="x_title  ">
          <h1>PRESUPUESTO FLOTA  <small>REGISTRO</small></h1>
           <div class="clearfix"></div>
          </div>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros" method="POST">
				
				            <div class="row">
							<input type="hidden" name="idPresupuesto" id="idPresupuesto">
							<input type="hidden" name="idproyecto" id="idproyecto">
							
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>AÑO:</label>
                                <select class="form-control" name="anio" id="anio" required>
								<option value="">seleccione..</option>	
								<option value="2025">2025</option>	
								<option value="2026">2026</option>	
								<option value="2027">2027</option>	
								<option value="2028">2028</option>		
								</select>
                              </div>
								
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>MES:</label>
                                <select class="form-control" name="mes" id="mes" required>
								<option value="">Seleccione..</option>	
								<option value="1">ENERO</option>
								<option value="2">FEBRERO</option>
								<option value="3">MARZO</option>
								<option value="4">ABRIL</option>
								<option value="5">MAYO</option>
								<option value="6">JUNIO</option>
								<option value="7">JULIO</option>
								<option value="8">AGOSTO</option>
								<option value="9">SEPTIMBRE</option>
								<option value="10">OCTUBRE</option>
								<option value="11">NOVIEMBRE</option>
								<option value="12">DICIEMBRE</option>	
								</select>
                              </div>	
                             
                            </div>
				
                            <div class="row">
							  	
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>SISTEMA:</label>
                                <select class="form-control" name="tipo" id="tipo" required>
								</select>
                              </div>
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>TIPO MANTENIMIENTO:</label>
                                <select class="form-control" name="tipomantenimiento" id="tipomantenimiento" required>
								<option value="">SELECCIONE...</option>	
								<option value="PREVENTIVO">PREVENTIVO</option>	
								<option value="CORRECTIVO">CORRECTIVO</option>	
								<option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
								<option value="IMPREVISTO">IMPREVISTO</option>		
								</select>
                              </div>	
							
                            </div>
				
				            <div class="row">
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>VEHICULO:</label>
                                <select class="form-control" name="vehiculo" id="vehiculo" required>
								</select>
                              </div>	
							  	
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>VALOR PRESUPUESTO:</label>
                                <input type="text" class="form-control" id="valorp" name="valorp"  onkeypress="Numero(event)" onkeyup="puntostexto(event)" required>
                              </div>	
                             
                            </div>
				           
				          <div class="row">
							  	
                              
							  
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Detalle:</label>
								 <textarea id="detalle" name="detalle" class="form-control"></textarea> 
                              </div>
								 
                             
                            </div>
				
				           <div class="row">
							  	
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>PYOYECTO:</label>
                                <input type="text" class="form-control" id="proyecto" readonly>
                              </div>
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                                <label>SERVICIO:</label>
                                <input type="text" class="form-control" id="servicio" readonly>
                              </div>	
                             
                            </div>
				
				           <div id="Muestra">
							   
							<div class="row">
							  	
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>RQ:</label>
                                <input type="text" class="form-control" id="rq" readonly>
                              </div>
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                                <label>OC:</label>
                                <input type="text" class="form-control" id="oc" readonly>
                              </div>	
                             
                            </div> 
							 
							 <div class="row">
							  	
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>VALOR EJECUTADO:</label>
                                <input type="text" class="form-control" id="valorEj" readonly>
                              </div>	
                             
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
                                <button class="btn" style="background: #871F1B; color:white;" onclick="cancelarform();"  type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar
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
				  <div class="invoice overflow-auto">
				<div id="inicial">
				
				 <div class="row">
                              <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" style="width:20%; margin: 15px auto; text-align: center;">
                                <label>AÑO:</label>
                                 <select class="form-control"  id="anioSe" >
								 <option value="2025">2025</option>
								 <option value="2026">2026</option>	 
								 <option value="2027">2027</option>	 	
								 <option value="2028">2028</option>	 
								 <option value="2029">2029</option>	 	 
								</select>	
                              </div>

                            </div>	 
					<div class="table-responsive">
		          <table border="0" cellspacing="0" cellpadding="0" id="tbmeses" style="width:100%;">
                    <thead>
                        <tr>
							<th>MES</th>
                            <th>AÑO</th>
							<th>VALOR PRESUPUESTO</th>
							<th>VALOR EJECUTADO</th>
							<th style="min-width: 120px;">ACCIÓN</th>
							
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                   
                </table></div> 
					  </div>	</div>
				
				<div id="Gestion"> 
				
              <div class="row" style="width:100%">
                <div style="width:100%">
                  <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist" style="width:100%">
                      <a class="nav-item nav-link active" id="item1" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true" onclick="estado=5;listarGn();">EN PROCESO</a>
					  <a class="nav-item nav-link " id="item1" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true" onclick="estado=4;listarGn();">RQ</a>
					  <a class="nav-item nav-link " id="item1" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true" onclick="estado=3;listarGn();">OC</a>	
					  <a class="nav-item nav-link " id="item1" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true" onclick="estado=2;listarGn();">INVENTARIO</a>		
					<a class="nav-item nav-link " id="item1" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true" onclick="estado=1;listarGn();">EJECUTADA</a>	
						
                      
                    </div>
                  </nav>
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent" style="width:100%">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="x_content">

                   
                     <div class="panel-body" >  
						 
				     <div id="invoice">

   
    <div class="invoice overflow-auto">
        
      
            
		
                 
		
		        
					
					
					
					<div class="row" style="width:30%; margin: auto auto; text-align: center;">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                                <label>AÑO:</label>
								<input type="text" class="form-control" id="anio2" readonly>
						</div>
						 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                                <label>MES:</label>	
							    <input type="text" class="form-control" id="mes2" readonly>
                              </div>
                             
                            </div>
					
					
					<div class="table-responsive">
					<table border="0" cellspacing="0" cellpadding="0" id="tbllistado" style="width:100%;">
                    <thead>
                        <tr>
							<th style="min-width: 200px;">VEHICULO</th>
                            <th style="min-width: 200px;">SERVICIO</th>
							<th style="min-width: 200px;">PROYECTO</th>
							<th>MANTENIMIENTO</th>
							<th>VALOR PRESUPUESTO</th>
							<th>VALOR EJECUTADO</th>
							<th>ENLACE CLOUDFLEET</th>
							<th style="min-width: 120px;">ACCIÓN</th>
							
                        </tr>
                    </thead>
                    <tbody>
                    
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
	  </div>
	 
	 
<!-- Contenido aqui va todo el DIV del contenido.. -->

<!-- /page content -->

<!-- footer content -->

<?php

include('footer.php');

?>

<script type="text/javascript" src="../Ajax/PresupuestoFlotaAjax.js"></script>

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