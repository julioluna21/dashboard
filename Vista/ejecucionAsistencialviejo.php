<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M12",$modulosAcceso)){	
include('header.php');
?>

<style>

input[type="file"]#miarchivo{
 width: 0.1px;
 height: 0.1px;
 opacity: 0;
 overflow: hidden;
 position: absolute;
 z-index: -1;
    

 }	
	
	
label[for="miarchivo"]{
 font-size: 12px;
 font-weight: 500;
 color: #000;
 background-color: #E9E8E1;
 display: inline-block;
 transition: all .5s;
 cursor: pointer;
 padding: 10px 20px !important;
 text-transform: uppercase;
 width: fit-content;
 text-align: center;
 margin-top: 6px;	
 }  

 .search-results {
  position: absolute;
  top: 72px;
  width: 90%;
  background: white;
  border-radius: 10px;
  box-shadow: 0 20px 12px rgba(0, 0, 0, 0.15);
  overflow: hidden;
  display: none;
  z-index: 1000;
}

.search-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 15px;
  cursor: pointer;
  transition: background 0.2s;
}

.search-item:hover {
  background-color: #e8f0fe;
}

.search-item svg {
  width: 20px;
  height: 20px;
  fill: #1e5f74;
  flex-shrink: 0;
}
</style>

 <div id="page-content-wrapper" style="">
	 
	 
	 
	 
               <div class="x_panel" style="background-color: white;color: black;">
          
          <div class="x_content">
			  
			  
			  
		<div class=" shadow p-3  mb-5 bg-white rounded">
			
			<div id="btnagregar">
			<!--<SPAN title="Subir SCV" style="float:right">
              <!--span - abarcar. Es un contenedor en línea. Sirve para aplicar estilo al texto o agrupar elementos en línea.-->
              <!--<button class="btn btn-secondary" style="margin-left:5px;" id="" onclick="mostrarform(true,2)">
                <!--Al hacer click, muestra el formulario-->
                <!--<i class="fa fa-plus-square">
                  <!--Muestra el texto marcado con un estilo en cursiva o italica.-->
                <!--</i> Subir CSV
              </button>
            </SPAN>-->
			
		   <SPAN title="Agregar Registro" style="float:right">
              <!--span - abarcar. Es un contenedor en línea. Sirve para aplicar estilo al texto o agrupar elementos en línea.-->
              <button class="btn" style="background: #871F1B; color:white;" id="" onclick="mostrarform(true,1)">
                <!--Al hacer click, muestra el formulario-->
                <i class="fa fa-plus-square">
                  <!--Muestra el texto marcado con un estilo en cursiva o italica.-->
                </i> Nuevo Registro
              </button>
            </SPAN>  
			</div>
			
			
		 <div class="x_title  ">
            <h1>Información asistenciales <small>Registro</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros" method="POST">
                            <div class="row">
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Fecha Servicio:</label>
							  <input type="hidden"  name="idCargue" id="idCargue" >	  
                              <input type="date" class="form-control" name="fechaservicio" id="fechaservicio" required>
                              </div>	
								
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Base Operación:</label>
               
                                <select class="form-control" name="centro" id="centro" required>
								</select>	
                                
                              </div>
                             
                            </div>

				
				            <div class="row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Tipo Evento:</label>
                                
                <select class="form-control" name="TipoEvento" id="TipoEvento" required>
								</select>	
                                
                              </div>	

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>PR:</label>
                               <input type="text" class="form-control" name="Pr" id="Pr" onkeypress="Numero(event)" required >	 
                              </div>    
							
                            </div>
				
				              <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Metros:</label>
                               <input type="text" class="form-control" name="Metros" id="Metros" onkeypress="Numero(event)" required >	 
                              </div>
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>UF:</label>
                               <input type="text" class="form-control" name="Uf" id="Uf" onkeypress="Numero(event)"  required>	 
                              </div>	
                             
                            </div>

                            
                              <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Vehículo Presto Servicio</label>
                               <select class="form-control" name="TipoVehiculo" id="TipoVehiculo" required>
								               </select>	
                              </div>
								
							              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                 <div class="search-section"></div> 
                               <label>Placa Vehículo:</label>
                               <input type="text" class="form-control search-bar" name="Placa" id="Placa" required>	
                               <div class="search-results" id="searchResults"></div> 
                              </div>	
                             
                            </div>

                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Datos Servicio:</label>
                            <select class="form-control" name="Servicio" id="Servicio" required>
                            <option value="">Seleccione</option>
                            <option value="1">Grúa</option>
                            <option value="2">Carro Taller</option>
                            <option value="3">Inspector Vial</option>
                            <option value="4">Accidente</option>
                            <option value="5">Ambulancia</option>
								              </select>
                            </div></div>
                            <br>

                            <div id="General" style="border-top:2px solid #871F1D;" >
                              <br>

                              <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>PR Salida</label>
                               <input type="text" class="form-control" name="Prsalida" id="Prsalida" onkeypress="Numero(event)"  >	
                              </div>
								
							              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>RN:</label>
                               <input type="text" class="form-control" name="RnSalida" id="RnSalida"  >	 
                              </div>	
                             
                            </div>
                             
                            <div id="tipo1">
                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>PR Traslado</label>
                               <input type="text" class="form-control" name="prtraslado" id="prtraslado" onkeypress="Numero(event)"  >	
                              </div>
								
							              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Ruta Traslado:</label>
                               <input type="text" class="form-control" name="rutaTraslado" id="rutaTraslado" >	 
                              </div>	
                             
                            </div></div>

                            <div id="tipo2">
                             <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Tipo Vehículo Atención</label>
                               <select class="form-control" name="TipoVehiculoAtencion" id="TipoVehiculoAtencion" required>
                                <option value="">Seleccione...</option>
                                <option value="MOTOCICLETA">Motocicleta</option>
                                <option value="AUTOMOVIL">Autómovil</option>
                                <option value="CAMIONETA">Camioneta</option>
                                <option value="CAMION">Camión</option>
                                <option value="BUS">Bus</option>
                                <option value="BICICLETA">Bicicleta</option>
                                <option value="TRACTOCAMIÓN">Tractocamión</option>
                                <option value="N/A">No Aplica</option>
								               </select>	
                              </div>
								
							              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Categoría Vehículo:</label>
                               <select class="form-control" name="CategoriaVehiculo" id="CategoriaVehiculo" required>
                                <option value="">Seleccione...</option>
                                <option value="I">I</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="V">V</option>
                                <option value="VI">VI</option>
                                <option value="N/A">No Aplica</option>
								               </select>	
                              </div>	
                             
                            </div> </div>

                            <div id="tipo3">

                              <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Numero Personas Heridas</label>
                              <input type="text" class="form-control" name="Pheridas" id="Pheridas" onkeypress="Numero(event)" required>	
                              </div>
								
							              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Heridos Graves:</label>
                               <input type="text" class="form-control" name="Hgraves" id="Hgraves" onkeypress="Numero(event)"  required>	 
                              </div>	
                             
                            </div>

                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Heridos Leves</label>
                              <input type="text" class="form-control" name="Hleves" id="Hleves" onkeypress="Numero(event)"  required>	
                              </div>
								
							              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Heridos Ilesos:</label>
                               <input type="text" class="form-control" name="Hilesos" id="Hilesos" onkeypress="Numero(event)"  required>	 
                              </div>	
                             
                            </div>

                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Fallecidos</label>
                              <input type="text" class="form-control" name="Fallecidos" id="Fallecidos" onkeypress="Numero(event)"  required>	
                              </div>
                             
                            </div>


                            </div>

                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Hora Reporte</label>
                               <input type="datetime-local" class="form-control" name="HoraReporte" id="HoraReporte" required >	
                              </div>
								
							              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Hora LLegada:</label>
                               <input type="datetime-local" class="form-control" name="HoraLLegada" id="HoraLLegada" required >	 
                              </div>	
                            </div>

                           

                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Hora Inicio Traslado</label>
                               <input type="datetime-local" class="form-control" name="HorainicioT" id="HorainicioT">	
                              </div>
								
							              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Hora Fin Traslado:</label>
                               <input type="datetime-local" class="form-control" name="HoraFinT" id="HoraFinT">	 
                              </div>	
                            </div>

                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Hora Final Servicio</label>
                               <input type="datetime-local" class="form-control" name="Horafinservico" id="Horafinservico">	
                              </div>
								
							              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Hora En Base:</label>
                               <input type="datetime-local" class="form-control" name="Horabase" id="Horabase">	 
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
                                <button class="btn" style="background: #871F1B; color:white;" onclick="cancelarform();limpiartabla();"  type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar
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
                      <a class="nav-item nav-link active" id="item1" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true" onclick="estado=1;listar();">ACTIVOS</a>
					  <a class="nav-item nav-link " id="item2" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" onclick="estado=2;listar();">MODIFICACIÓN</a>	
                      
                    </div>
                  </nav>
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent" style="width:100%">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="x_content">
                        
                        
                     <div class="row">
                              <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Base Operación:</label>
                                 <select class="form-control"  id="centrobus" >
								</select>	
                              </div>
				  
				             <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Fecha Inicial:</label>
                                <input type="date" class="form-control" id="fechainical" >
                              </div>
				  
				             <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Fecha Final:</label>
                                <input type="date" class="form-control" id="fechafinal" >
                              </div>
				              <br> <br> <br>
				              <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" >
                             <SPAN title="Descargar Registros">
                                <button class="btn " style="background: #871F1B; color:white; margin-top:33px" onclick="Reporte();" ><i class="fa fa-file-excel-o"></i>
                                </button>
                              </SPAN>   

                              <SPAN title="Buscar Registro">
                                <button class="btn " style="background: #871F1B; color:white; margin-top:33px" onclick="listar()" ><i class="fa fa-search"></i>
                                </button>
                              </SPAN>       
                            </div>
                             
                            </div>    

                   
                     <div class="panel-body table-responsive" >  
						 
				     <div id="invoice">

   
    <div class="invoice overflow-auto">
        
      
        
                <table border="0" cellspacing="0" cellpadding="0" id="tbllistado" style="width:100%;">
                    <thead>
                        <tr>
							<th>CENTRO OPERATIVO</th>
							<th>FECHA REGISTRO</th>
							<th>TIPO VEHICULO</th>
							<th>VEHICULO</th>
							<th>TIPO EVENTO</th>
              <th>PR</th>
              <th>METROS</th>
              <th>UF</th>
              <th>PR SALIDA</th>
              <th>RN SALIDA</th>
							<th style="min-width: 120px;">ACCIÓN</th>
							
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

<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script type="text/javascript" src="../Ajax/DatosAjaxAsistencia.js"></script>

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