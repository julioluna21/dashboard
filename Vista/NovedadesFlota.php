<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M17",$modulosAcceso)){	

?>

<div class="modal fade" id="modal-nota" tabindex="-1" role="dialog" aria-labelledby="modal-nota-label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modal-preguntas-label">NOVEDAD Y NOTAS</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
														  
                                                        <div class="modal-body">
                                                          
                                                            <div id="contenidoNota" class="notas">	
															</div>
                                                            
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn" style="background: #871F1B; color:white;" data-dismiss="modal">Cerrar</button>
                                                          
                                                        </div>
															  
                                                    </div>
                                                </div>
                                            </div>


<?php
include('header.php');
?>

<style>
   .notas{
     overflow: scroll; 
	 max-height:400px; 
	 text-align: center;  
    
    }
	
	.ntexto{
	 padding: 8px;	
	 padding-bottom: 20px;	
     width: 400px;
	 display: block;
     margin-left: auto;
     margin-right: auto;
	 background: #5C5D5D; color:white;	
	 border-radius: 7px;
	 font-size: 12px;
	text-align:left;	
	margin-top: 20px;	
    
    }
  
</style>
	

 <div id="page-content-wrapper" style="">
	 
	 
	 
	 
               <div class="x_panel" style="background-color: white;color: black;">
          
          <div class="x_content" >
			  
			  
			  
		<div class=" shadow p-3  mb-5 bg-white rounded">
		   <SPAN title="Agregar Registro" style="float:right">
              <!--span - abarcar. Es un contenedor en línea. Sirve para aplicar estilo al texto o agrupar elementos en línea.-->
              <button class="btn" style="background: #871F1B; color:white;" id="btnagregar" onclick="mostrarform(true);mostrarformu(1);">
                <!--Al hacer click, muestra el formulario-->
                <i class="fa fa-plus-square">
                  <!--Muestra el texto marcado con un estilo en cursiva o italica.-->
                </i> Nuevo Registro
              </button>
            </SPAN>	
		 <div class="x_title  ">
            <h1>Novedades Flota <small>Registro</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros" method="POST">
                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Vehiculo:</label>
                               <input type="hidden"  name="idnovedad" id="idnovedad">
                               <select class="form-control" name="vehiculo" id="vehiculo" required>
							   </select>
                              </div>
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Tipo Novedad:</label>
                               <select class="form-control" name="tiponovedad" id="tiponovedad" required>
								<option value="">SELECCIONE...</option>
								<option value="SINIESTRO">SINIESTRO</option>
								<option value="PREVENTIVO">PREVENTIVO</option>
								<option value="CORRECTIVO">CORRECTIVO</option>   
							   </select>
                              </div>
									
                            </div>
				
				            <div class="row" id="fechasdato">	
                             <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Fecha Hora Inicio:</label>
								<input type="datetime-local" class="form-control" name="fechainicio" id="fechainicio"  required="" >
                              </div> 
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                             <label>Fecha Hora Final:</label>
			                 <input type="datetime-local" class="form-control" name="fechafinal" id="fechafinal"  required="" >	
                             </div> 	
                            </div>
				
				            <div class="row">
							    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Estado Operatividad:</label>
								 <select class="form-control" name="operatividad" id="operatividad" required>
								<option value="">SELECCIONE...</option>
								<option value="OPERATIVO">OPERATIVO</option>
							    <option value="INOPERATIVO">INOPERATIVO</option>  
							   </select>	
                                
                              </div>
							
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" id="dvcontigngencia">
                                <label>Reemplazo Contigencia:</label>
								 <select class="form-control" name="contingencia" id="contingencia" >
								<option value="SIN REEMPLAZO">SELECCIONE...</option>
								<option value="BACKUP">BACKUP</option>
							    <option value="VEHICULO ALQUILADO">VEHICULO ALQUILADO</option>
								<option value="NO REQUIERE">NO REQUIERE</option>	 
								<option value="REDISTRIBUCIÓN">REDISTRIBUCIÓN</option>	 	 
							   </select>	
                                
                              </div>		
								
                            </div>
				
				            <div class="row" id="dvplaca">	
                             <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Placa Vehiculo:</label>
								<input type="text" class="form-control" name="palacacont" id="palacacont" >
                              </div> 
				
                            </div>

                            <div class="row">
								 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Sistema:</label>
								<select class="form-control" name="Sistema" id="Sistema" >
								<option value="SIN REEMPLAZO">SELECCIONE...</option>
								<option value="MOTOR">MOTOR</option>
							    <option value="TRANSMISIÓN">TRANSMISIÓN</option>
								<option value="SUSPENSIÓN">SUSPENSIÓN</option>	 
								<option value="DIRECCIÓN">DIRECCIÓN</option>	
								<option value="FRENOS">FRENOS</option>
								<option value="ELÉCTRICO">ELÉCTRICO</option>
								<option value="ENFRIAMIENTO">ENFRIAMIENTO</option>
								<option value="COMBUSTIBLE">COMBUSTIBLE</option>
								<option value="ESCAPE">ESCAPE</option>
								<option value="CLIMATIZACIÓN">CLIMATIZACIÓN</option>
								<option value="SEGURIDAD">SEGURIDAD</option>	
								<option value="ENTRETENIMIENTO">ENTRETENIMIENTO</option>
								<option value="ILUMINACIÓN Y SEÑALIZACIÓN">ILUMINACIÓN Y SEÑALIZACIÓN</option>
								<option value="LIMPIEZA">LIMPIEZA</option>	
								<option value="HIDRÁULICO">HIDRÁULICO</option>	
								<option value="LLANTAS">LLANTAS</option>	
								<option value="CARROCERÍA">CARROCERÍA</option>
								<option value="GPS">GPS</option>
								<option value="RTM">RTM</option>
								<option value="EMBRAGUE">EMBRAGUE</option>	
							   </select>	 
                              </div> 
								
							    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Novedad:</label>
								<textarea class="form-control" name="novedad" id="novedad" required></textarea>	
                                
                              </div>
							
							 	
                            </div>
                
                             <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="botones2">
                              <SPAN title="Guardar Registro">
                                <button class="btn btn-secondary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar
                                </button>
                              </SPAN>  
								
							 <SPAN title="Cambiar fechas">
                                <button class="btn btn-secondary" type="button" id="btnfechas" onclick="mostrarfechas();mostrarformu(3);"><i class="fa fa-calendar"></i> Cambiar fechas
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
				 
				      <form name="formrnotas" id="formrnotas" method="POST">
                            <div class="row">
							    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								 <input type="hidden"  name="idnovedad" id="idnovedad2">	
                                <label>Nota Novedad:</label>
								<textarea class="form-control" name="novedad" id="novedad2" required></textarea>	
                                
                              </div>	
                            </div>
                
                             <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="botones2">
                              <SPAN title="Guardar Registro">
                                <button class="btn btn-secondary" type="submit" id="btnGuardar2"><i class="fa fa-save"></i> Guardar
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
				 
				 
				  <form name="formrfecha" id="formrfecha" method="POST">
					  
					        <div class="row">	
                             <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Fecha Hora Inicio:</label>
								<input type="hidden"  name="idnovedad" id="idnovedad3">	 
								<input type="datetime-local" class="form-control" name="fechainicio" id="fechainicio2"  required="" >
                              </div> 
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                             <label>Fecha Hora Final:</label>
			                 <input type="datetime-local" class="form-control" name="fechafinal" id="fechafinal2"  required="" >	
                             </div> 	
                            </div>
                            <div class="row">
							    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								 
                                <label>Motivo de cambio:</label>
								<textarea class="form-control" name="novedad" id="novedad3" required></textarea>	
                                
                              </div>	
                            </div>
                
                             <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="botones2">
                              <SPAN title="Guardar Registro">
                                <button class="btn btn-secondary" type="submit" id="btnGuardar3"><i class="fa fa-save"></i> Guardar
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
                            <th >PLACA</th>
							<th >TIPO NOVEDAD</th>
							<th >SISTEMA</th>
							<th >OPERATIVIDAD</th>
							<th >CONTIGENCÍA</th>
                            <th >PLACA CONTIGENCÍA</th>
							<th >FECHA INICIAL</th>
                            <th >FECHA FINAL</th>
						    <th >HORAS TRANSCURRIDOS</th>
						    <th>NOVEDAD</th>	
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

<script type="text/javascript" src="../Ajax/NovedadFlotaAjaxNuevo.js"></script>

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