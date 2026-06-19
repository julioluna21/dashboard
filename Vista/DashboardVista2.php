<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M1",$modulosAcceso)){	
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

<div class="modal fade" id="modal-novedades" tabindex="-1" role="dialog" aria-labelledby="modal-novedades-label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content" style="width:600px; margin-left: -70px;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modal-preguntas-label">TRAZABILIDAD  NOVEDAD</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
														  
                                                        <div class="modal-body">
                                                          
                                                            <div id="contenidoNovedades" class="notas">	
															</div>
                                                            
                                                        </div>
                                                        <div class="modal-footer" style="width:100%; padding: 7px;	">
							                            <button type="button" class="btn" style="background: #871F1B; color:white;" data-dismiss="modal">Cerrar</button>
															
                                                        </div>
															  
                                                    </div>
                                                </div>
                                            </div>

<?php
include('header.php');
?>
<style>
#semi-container{
    width: 400px;
    height: 150px;
    margin: 15px auto;
	
}
	
#semi-container2,#semi-container3,#semi-container4{
    width: 200px;
    height: 75px;
    margin: 15px auto;
	
	}

.progress-text {
  font-size: 1.8em;
  color: black;
  margin-bottom: 1em;
  font-weight: 600;
}
	
	.titulodash{
    font-weight: 600;		
	margin: 10px auto;
	text-align: center;	
	}	
	
	.stabla tr th td{
		padding: 
	}
	
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
	
	.titulo{
	 text-align: center;
	 font-weight: bold;	
    }
</style>

 <div id="page-content-wrapper" style="">
	 
	 
	 
	 
               <div class="x_panel" style="background-color: white;color: black;">
          
          <div class="x_content">
			  
			  
			  
		<div class=" shadow p-3  mb-5 bg-white rounded">
			
		 <div class="x_title  ">
            <h1>DASHBOARD <small>Listado</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

            <!-- Inicio Formulario -->
          
		  
			   
            <!-- Listado Registros-->

            <!-- centro listado de articulos-->
        
            <!-- end form for validations -->
			  
			<div class="panel-body" style="width:100%" id="listadoregistros">
              <div class="row" style="width:100%">
                <div style="width:100%">
                  <nav>
                     <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                      <?php if(in_array("M19",$modulosAcceso)){ ?><a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" onclick="mostrar(4);">CONTRATO</a><?php }?>
					  <?php if(in_array("M20",$modulosAcceso)){ ?><a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false" onclick="mostrar(5);">FLOTA</a><?php }?> 	 
                      <?php if(in_array("M21",$modulosAcceso)){ ?><a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" onclick="mostrar(3);">PEAJES</a><?php }?>
					  <?php if(in_array("M22",$modulosAcceso)){ ?><a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" onclick="mostrar(2);">PIPELINE</a><?php }?>
					  <?php if(in_array("M38",$modulosAcceso)){ ?><a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" onclick="mostrar(9);">TOLIS</a><?php }?>	 
                       <?php if(in_array("M23",$modulosAcceso)){ ?><a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false" onclick="mostrar(1);">NOVEDADES</a><?php }?>  
					  <?php if(in_array("M25",$modulosAcceso)){ ?><a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false" onclick="mostrar(6);">FINANCIERO</a><?php }?> 
					  <?php if(in_array("M26",$modulosAcceso)){ ?><a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false" onclick="mostrar(7);">GH</a><?php }?> 
					 <?php if(in_array("M31",$modulosAcceso)){ ?><a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false" onclick="mostrar(8);">TI</a><?php }?> 	 
                    </div>
                  </nav>
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent" style="width:100%">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="x_content">
                           
				    <iframe title="Peajes" id="peajes"  width="100%" height="800px" src="
                    https://app.powerbi.com/view?r=eyJrIjoiZDYzNTExNjEtNjZjNy00MjhlLWE2YzUtMWY2NmJmZmZkNzZmIiwidCI6IjYwMjkyY2RlLTIxYTQtNDQ1NS04ZjlmLTY1NTQ0YzI4NzMzMSJ9"
                    frameborder="0" allowFullScreen="true"></iframe>
						
					<iframe title="Dashboard - Financiero" id="finaciero" width="100%" height="800px" src="https://app.powerbi.com/view?r=eyJrIjoiMDMwMzdkNDItZThmMy00ZTJjLTljYTAtNjBkMGMzNDliOWIzIiwidCI6IjYwMjkyY2RlLTIxYTQtNDQ1NS04ZjlmLTY1NTQ0YzI4NzMzMSJ9"
                    frameborder="0" allowFullScreen="true"></iframe>
				    
				    <iframe title="Indicadores GH Total" id="gestionH" width="100%" height="800px" src="
https://app.powerbi.com/view?r=eyJrIjoiMWE4Y2M3NzAtMjU1NC00ZTEyLWJkY2ItM2NkYzkzNWFmNTdmIiwidCI6IjYwMjkyY2RlLTIxYTQtNDQ1NS04ZjlmLTY1NTQ0YzI4NzMzMSJ9&pageName=ReportSection"
frameborder="0" allowFullScreen="true"></iframe>	


                        <iframe title="Pipeline 2026" id="operacion" width="100%" height="800px" 
						 src="https://app.powerbi.com/view?r=eyJrIjoiMDZhMmUzZDQtMDdlNy00N2QyLTkzNzktZDJjYTUxY2ZiOTExIiwidCI6IjYwMjkyY2RlLTIxYTQtNDQ1NS04ZjlmLTY1NTQ0YzI4NzMzMSJ9%22 frameborder="0" allowFullScreen="true"></iframe>
					
								
					<iframe title="Dashboard Tolis" id="tolis" width="100%" height="800px"  src="https://app.powerbi.com/view?r=eyJrIjoiYTFkNThlYjctMjViNC00M2RiLWFkN2QtYjMwNzM2YWJmMDFkIiwidCI6IjYwMjkyY2RlLTIxYTQtNDQ1NS04ZjlmLTY1NTQ0YzI4NzMzMSJ9" frameborder="0" allowFullScreen="true"></iframe>
						
						
						<div class="titulodash shadow p-3  mb-5 bg-white rounded" id="contratodash">
							
						<div style="width:100%">
                  <nav id="segundanav">
                     <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                      <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" onclick="mostrarcontrato(1)">EJECUCIÓN</a>
					  <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false" onclick="mostrarcontrato(2)">EXPERIENCÍA</a> 	                 
                    </div>
                  </nav>
                  <div class="tab-content2 py-3 px-3 px-sm-0" id="nav-tabContent" style="width:100%">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="x_content">
						
						<div id="ejecucion">
							
						<div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12" style="margin: 15px auto;">
                                <label>PROYECTO:</label>
                                 <select class="form-control"  id="proyecto" name="proyecto" >
								</select>	
                              </div>	
							
						<h4 >TIEMPO EJECUTADO<br><small>EN PORCENTAJE</small></h4>
						<div id="semi-container"></div>
							<span id="fechas"></span>
							
							
					   <div id="invoice">

   
    <div class="invoice overflow-auto">		
							
						<table border="0" cellspacing="0" cellpadding="0" id="" style="width:100%;margin: 15px auto;">
                    <thead>
                        <tr>
					   <th style="width:30%"></th>
                       <th tyle="width:70%"></th>
                        </tr>
                    </thead>
                    <tbody id="contenido">
                    	
                    </tbody>
                   
                </table>
						   
						   </div></div>	
						
						</div>
						<div id="experiencia">
							
					    <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12" style="margin: 15px auto;">
                                <label>ENTIDAD CONTRATANTE:</label>
                                 <select class="form-control"  id="cliente" name="cliente" >
								</select>	
                              </div>		
							
						<div class="panel-body table-responsive" >  	
					   <div id="invoice">

   
                    <div class="invoice overflow-auto" style=" text-align: center;">
							
						<table border="0" cellspacing="0" cellpadding="0" id="tbcontratos" style="width:100%;margin: 15px auto;">
                     <thead>
                       <tr>
					   <th>ENTIDAD CONTRATANTE</th>
                       <th style="min-width: 300px;">NUMERO</th>
					   <th>UEN CONTRATO</th>   
					   <th >OBJETO</th>	   
					   <th>DEPARTAMENTO</th>
					   <th>FORMA EJECUCIÓN</th>
					   <th >FECHA INICIAL</th>
                       <th >FECHA FINAL</th>	   
					   <th>VALOR CONTRATO</th>	
					   <th>PLAZO EN DIAS</th>	
					   <th >PLAZO EN MESES</th>		   
                        </tr>
                    </thead>
                    <tbody>
                    	
                    </tbody>
                   
                </table>
						   
						   </div></div></div>	
						
						
						</div>
						</div></div></div></div>	
							
							
						</div>
						
						
						<div class="titulodash shadow p-3  mb-5 bg-white rounded" id="flotadash">
							
						<div style="width:100%">
                  <nav id="segundanav">
                     <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
					  <a class="nav-item nav-link active" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false" onclick="mostrarflota(4)">LISTADO DE FLOTA</a> 		 
                      <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" onclick="mostrarflota(1)">DISPONIBILIDAD</a>
					  <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" onclick="mostrarflota(5)">DISPONIBILIDAD MES</a>	 
					  <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false" onclick="mostrarflota(3)">ANALISIS BI</a> 
					  <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false" onclick="mostrarflota(2)">COMBUSTIBLE</a> 
                        
                    </div>
                  </nav>
                  <div class="tab-content2 py-3 px-3 px-sm-0" id="nav-tabContent" style="width:100%">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="x_content">
						
						<div id="disponibilidad">
						<div class="row" style="width:60%; margin: 15px auto;">	
							
							<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>FECHA INICIAL:</label>
                                <input type="date" class="form-control" name="fechainicio" id="fechainicio" >
							</div>
							
							<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>FECHA FINAL:</label>
                                <input type="date" class="form-control" name="fechafin" id="fechafin" >
							</div>
							
							<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>PROYECTO:</label>
                                 <select class="form-control"  id="proyecto2" name="proyecto" >
								</select>	
							</div>
							
							</div>
						
						
						
						<div class="row" >	
						 
						 <!--<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<h8 >DISPONIBILIDAD TOTAL <BR>DE FLOTA<br><small>EN PORCENTAJE</small></h8>
							 <div id="semi-container2"></div></div>-->
						<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<h8 >DISPONIBILIDAD TOTAL VEHÍCULOS OPERATIVOS<br><small>EN PORCENTAJE</small></h8>
							 <div id="semi-container3"></div></div>
						<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<h8 >DISPONIBILIDAD TOTAL VEHÍCULOS ADMINSITRATIVOS<br><small>EN PORCENTAJE</small></h8>
							<div id="semi-container4"></div></div>
					   </div>
								
						
						
							<div style="border-top:2px solid #871F1D; text-align: center;" id="grftotal">
								<br>
								<div class=" panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="">
								<div class="modal-header">
								<h5 class="modal-title" id="modal-preguntas-label">INOPERATIVIDAD DE FLOTA POR PROYECTO</h5><span title="Agregar Registro" style="float:right"><button class="btn mostrar" style="background: #871F1B; color:white;"  onclick="mostrarpanel('p1')"><i class="fa fa-angle-double-down"></i></button></span></div>	
								<div id="p1"  class="mostrarDiv">
								
								<BR><h8 >INOPERATIVIDAD VEHÍCULOS OPERATIVOS POR PROYECTO</h8>
								<canvas id="canvasIN" height="200" width="500" ></canvas>	
								<BR><h8 >INOPERATIVIDAD VEHÍCULOS ADMINISTRATIVOS POR PROYECTO</h8>
								<canvas id="canvasIN2" height="200" width="500" ></canvas>		
									
								</div>
								</div>	
									
									
							   <div class=" panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="">
								<div class="modal-header">
								<h5 class="modal-title" id="modal-preguntas-label">DISPONIBILIDAD DE FLOTA POR PROYECTO</h5><span title="Agregar Registro" style="float:right"><button class="btn mostrar" style="background: #871F1B; color:white;"  onclick="mostrarpanel('p2')"><i class="fa fa-angle-double-down"></i></button></span></div>	
								<div id="p2"  class="mostrarDiv">		
								<BR><h8 >DISPONIBILIDAD VEHÍCULOS OPERATIVOS POR PROYECTO</h8>
								<canvas id="canvas" height="200" width="500" ></canvas>
							    <BR><h8 >DISPONIBILIDAD VEHÍCULOS ADMINISTRATIVOS POR PROYECTO</h8>
								<canvas id="canvas3" height="200" width="500" ></canvas>
								</div>
								</div>	
									
							    <div class=" panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="">
								<div class="modal-header">
								<h5 class="modal-title" id="modal-preguntas-label">RESUMEN GENERAL DISPONIBILIDAD</h5><span title="Agregar Registro" style="float:right"><button class="btn mostrar" style="background: #871F1B; color:white;"  onclick="mostrarpanel('p3')"><i class="fa fa-angle-double-down"></i></button></span></div>	
								<div id="p3"  class="mostrarDiv">
									
								<div class="panel-body table-responsive" >  	
					   <div id="invoice">

   
                    <div class="invoice overflow-auto" style=" text-align: center;">
							
					   <table border="0" cellspacing="0" cellpadding="0" id="tbgenraldis" style="width:100%;margin: 15px auto;">
                       <thead>
                       <tr>
					   <th style="min-width: 300px;">PROYECTO</th>
                       <th>DISPONIBILIDAD TOTAL</th>
					   <th>BACKUP</th>   
					   <th>ALQUILER</th>	   
					   <th>REDISTRIBUCIÓN</th>
					   <th>NO REQUIERE</th>
					   <th>SIN REEMPLAZO</th>
                       <th>INOPERATIVIDAD TOTAL</th>	   	   
                        </tr>
                    </thead>
                    <tbody id="datosgeneral">
                    	
                    </tbody>
                   
                </table>
						   
						   </div></div></div>		
								
								</div>
								</div>	
									
				 <div class=" panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="">
								<div class="modal-header">
								<h5 class="modal-title" id="modal-preguntas-label">TIPO DE NOVEDADES</h5><span title="Agregar Registro" style="float:right"><button class="btn mostrar" style="background: #871F1B; color:white;"  onclick="mostrarpanel('p4')"><i class="fa fa-angle-double-down"></i></button></span></div>	
								<div id="p4"  class="mostrarDiv">		
								<div style="border-top:2px solid #871F1D; text-align: center;">
								  <BR><h8 >TIPOS DE NOVEDADES</h8>
								<canvas id="canvas2" height="200" width="500" ></canvas>
							   </div>	
								</div>
								</div>						
								
								  		  
							</div>
								
						   	
							
						
							
						<div class="panel-body table-responsive" >  	
					   <div id="invoice">

   
    <div class="invoice overflow-auto" style="border-top:2px solid #871F1D; text-align: center;">
		
		         <BR><h8 >NOVEDADES REGISTRADAS</h8>
							
						<table border="0" cellspacing="0" cellpadding="0" id="tbllistado" style="width:100%;margin: 15px auto;">
                    <thead>
                       <tr>
					   <th>PLACA</th>
                       <th>TIPO VEHÍCULO</th>
					   <th>PROYECTO</th>	   
					   <th>TIPO NOVEDAD</th>
					   <th>SISTEMA</th>	   
					   <th>ESTADO</th>
					   <th>CONTIGENCÍA</th>
                       <th>PLACA CONTIGENCÍA</th>	   
					   <th>FECHA INICIO</th>	
					   <th>FECHA FINAL</th>	
					   <th>TIEMPO</th>		   
					   <th>ESTADO</th>		   
					   <th style="min-width: 200px;">NOVEDAD</th>		   
                        </tr>
                    </thead>
                    <tbody>
                    	
                    </tbody>
                   
                </table>
						   
						   </div></div></div>
						
						</div>
							   
						<div id="DisponibilidadMes">
							
						<div class="row" style="width:60%; margin: 15px auto;">	
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>AÑO:</label>
                                 <select class="form-control" name="ano" id="ano2" required>	
								</select>	
							</div>
								   
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>PROYECTO:</label>
                            <select class="form-control" name="proyecto" id="proyecto3" required>	
							</select>
							</div>
							</div>	
							
							
						    <div style="border-top:2px solid #871F1D; text-align: center;">
								  <BR><h8 >DISPONIBILIDAD OPERATIVA TOTAL POR MES</h8>
								<canvas id="canvasD1" height="200" width="500" ></canvas>
							</div>
								
							<div style="border-top:2px solid #871F1D; text-align: center;">
								  <BR><h8 >DISPONIBILIDAD ADMINISTRATIVA TOTAL POR MES<</h8>
								<canvas id="canvasD2" height="200" width="500" ></canvas>
							</div>	
							
						
					    </div>	   
									  
							   <div id="combustible">
							   <div class="row" style="width:50%; margin: 15px auto;">	
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>AÑO:</label>
                                 <select class="form-control" name="ano" id="ano" required>	
								</select>	
							</div>				
							
							</div>	
								  
							<div class=" panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="">
								<div class="modal-header">
								<h5 class="modal-title" id="modal-preguntas-label">RENDIMIENTO POR PROYECTO</h5><span title="Agregar Registro" style="float:right"><button class="btn mostrar" style="background: #871F1B; color:white;"  onclick="mostrarpanel('C1')"><i class="fa fa-angle-double-down"></i></button></span></div>	
								<div id="C1"  class="mostrarDiv">		
								<div style="border-top:2px solid #871F1D; text-align: center;">
								 <BR><h8 ></h8> 
								<canvas id="canvasC3" height="200" width="500" ></canvas>
							</div>
								</div>
								</div>	
								 
								 <div class=" panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="">
								<div class="modal-header">
								<h5 class="modal-title" id="modal-preguntas-label">DATOS GENERAL POR MES</h5><span title="Agregar Registro" style="float:right"><button class="btn mostrar" style="background: #871F1B; color:white;"  onclick="mostrarpanel('C2')"><i class="fa fa-angle-double-down"></i></button></span></div>	
								<div id="C2"  class="mostrarDiv">		
								<div style="border-top:2px solid #871F1D; text-align: center;">
								<BR><h8 ></h8> 	
								<canvas id="canvasC1" height="200" width="500" ></canvas>
							</div>
								</div>
								</div>
								   
								 <div class=" panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="">
								<div class="modal-header">
								<h5 class="modal-title" id="modal-preguntas-label">RENDIMIENTO POR TIPO DE VEHICULO</h5><span title="Agregar Registro" style="float:right"><button class="btn mostrar" style="background: #871F1B; color:white;"  onclick="mostrarpanel('C3')"><i class="fa fa-angle-double-down"></i></button></span></div>	
								<div id="C3"  class="mostrarDiv">		
								<div style="border-top:2px solid #871F1D; text-align: center;">
								<BR><h8 ></h8> 	
								<canvas id="canvasC2" height="200" width="500" ></canvas>
							    </div>
								</div>
								</div>  
						    	
								
							
								
								
								
								<div class="panel-body table-responsive" >  	
					   <div id="invoice">

   
    <div class="invoice overflow-auto" style="border-top:2px solid #871F1D; text-align: center;">
		
		         <BR><h8 >LISTADO DE REGISTROS</h8>
							
						<table border="0" cellspacing="0" cellpadding="0" id="tbcombustible" style="width:100%;margin: 15px auto;">
                    <thead>
                       <tr>
					   <th>PLACA</th>
                       <th>TIPO VEHÍCULO</th>
					   <th>PROYECTO</th>	   
					   <th>MES</th>
					   <th>GALONES</th>	   
					   <th>KILOMETROS</th>
					   <th>SERVICIOS</th>
                       <th>CANTIDAD PEAJES</th>	   
					   <th>PEAJES</th>	
					   <th>MANTENIMIENTO</th>	
					   <th>COMBUSTIBLE</th>		   
					   <th>RENDIMIENTO</th>		   	   
                        </tr>
                    </thead>
                    <tbody>
                    	
                    </tbody>
                   
                </table>
						   
						   </div></div></div>	
								
								   
							   </div>
                                     
								<div id="presupuesto">  
                                    
                                    <iframe title="Dashboard PPTO 2025" width="100%" height="800px" src="https://app.powerbi.com/view?r=eyJrIjoiZDVhNzgyMTMtNTBlOC00MzgwLTgwZmMtMjdmZDY1ZDFkNDFkIiwidCI6IjYwMjkyY2RlLTIxYTQtNDQ1NS04ZjlmLTY1NTQ0YzI4NzMzMSJ9" frameborder="0" allowFullScreen="true"></iframe>
                
									
							   </div>
								<div id="listadoflota">
									
								
								 <div id="invoice">

   
                    <div class="invoice overflow-auto" style=" text-align: center;">
					
						  <BR><h8 >INVENTARIO FLOTA</h8>
							
					  <table border="0" cellspacing="0" cellpadding="0" id="tbflotagn" style="width:50%;margin: 15px auto;">
                     <thead>
                       <tr>
					   <th style="min-width: 400px;">PROYECTO</th>  
					   <th>ADMINISTRATIVOS</th>	 
					   <th>OPERATIVOS</th>	 	   
					   <th>CANTIDAD VEHÍCULOS</th>		   
                       </tr>
                    </thead>
                    <tbody id="contenflota">
                    	
                    </tbody>
                   
                </table>
						   
						   </div></div>
									 
									 
						<div class="panel-body table-responsive" >  
						 
				     <div id="invoice">

   
    <div class="invoice overflow-auto">
		
		      <BR><h8 >LISTADO DE VEHÍCULOS</h8>
                <table border="0" cellspacing="0" cellpadding="0" id="tbvehiculos" style="width:100%;">
                    <thead>
                        <tr>
							<th>ID</th>
							<th>VEHÍCULO</th>
							<th>ESTADO</th>
							<th>PROYECTO</th>
							<th>TIPO VEHÍCULO</th>
							<th>FECHA VINCULACIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                   
                </table>
               
    </div>
</div> 
                   
                      </div>			 
									 
									
							   </div>	  </div></div></div></div>
							
						
						</div>
								
				
				 <div id="novedades" >
					 
				 <div class="row" style="width:60%; margin: 15px auto;">	
							
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>FECHA INICIAL:</label>
                                <input type="date" class="form-control" name="fechainicio" id="fechainicioN" >
							</div>
							
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>FECHA FINAL:</label>
                                <input type="date" class="form-control" name="fechafin" id="fechafinN" >
							</div>	
							
				</div>	
					 
					 <div style="border-top:2px solid #871F1D; text-align: center;">
					 <br><h8 >NOVEDADES REGISTRADAS POR PROYECTO</h8>
						 <canvas id="canvasNOVEDADES" height="200" width="500" ></canvas>
					 </div>	
					 
					 
					            
					<div class="panel-body table-responsive" >   
				   <div id="invoice">

   
                    <div class="invoice overflow-auto" style=" text-align: center;">
                       <BR><h8 >NOVEDADES REGISTRADAS</h8><br><br>
					  <table border="0" cellspacing="0" cellpadding="0" id="tbNovedades" style="width:100%;">
                     <thead>
                       <tr>
					   <th style="min-width: 400px;">TITULO NOVEDAD</th>  
					   <th>PROYECTO</th>	 
					   <th>UNIDAD DE NEGOCÍO</th>	 	   
					   <th>FECHA REGISTRO</th>
					   <th>FECHA CIERRE</th>   
					   <th>ESTADO</th>	   
					   <th>TRAZABILIDAD NOVEDAD</th>	   
                       </tr>
                    </thead>
                    <tbody id="contenflota">
                    	
                    </tbody>
                   
                </table>
						   
						   </div></div></div>	 
								 
					 
				 </div>	
					 
				<div id="TI" >
					 
				 <div class="row" style="width:30%; margin: 15px auto; text-align: center">	
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>AÑO:</label>
                                <select class="form-control" name="ano" id="ano3" required>	
								</select>
							</div>	
							
				</div>	
					 
					 <div style="border-top:2px solid #871F1D; text-align: center;">
					 <br><h8 >GESTIÓN DE GASTOS TI</h8>
						 <canvas id="canvasTiGastos" height="200" width="500" ></canvas>
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
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.0.1/progressbar.min.js"></script>
	 
<?php

include('footer.php');

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>	 
<script type="text/javascript" src="../Ajax/dashAjaxFIN.js"></script>
<script type="text/javascript" src="../Ajax/DasCombustibleN.js"></script>	
<script type="text/javascript" src="../Ajax/DasDismes.js"></script>	
<script type="text/javascript" src="../Ajax/DasNovedades.js"></script>	
<script type="text/javascript" src="../Ajax/DasTi.js"></script>								


<?php
if(in_array("M19",$modulosAcceso)){
 echo '<script> 
  setTimeout(() => {	 
 mostrar(4);
 }, 1000);
 </script>';	
}else if(in_array("M20",$modulosAcceso)){
	echo '<script> 
setTimeout(() => {	
 mostrar(5);
 }, 1000);
 </script>';
}else if(in_array("M21",$modulosAcceso)){
	echo '<script>
 setTimeout(() => {	
 mostrar(3);
 }, 1000);
 </script>';
	
}else if(in_array("M22",$modulosAcceso)){
     echo '<script>
     setTimeout(() => {	
     mostrar(2);
     }, 1000);	
     </script>';  
	
}else if(in_array("M38",$modulosAcceso)){
     echo '<script>
     setTimeout(() => {	
     mostrar(9);
     }, 1000);	
     </script>';  
	
}else if(in_array("M23",$modulosAcceso)){
	echo '<script> 
 setTimeout(() => {	
 mostrar(1);
 }, 1000);
 </script>';
	
}else if(in_array("M25",$modulosAcceso)){
	echo '<script> 
 setTimeout(() => {	
 mostrar(6);
 }, 1000);
 </script>';
	
}else if(in_array("M26",$modulosAcceso)){
	echo '<script> 
 setTimeout(() => {	
 mostrar(7);
 }, 1000);
 </script>';
	
}else if(in_array("M31",$modulosAcceso)){
	echo '<script> 
 setTimeout(() => {	
 mostrar(8);
 }, 1000);
 </script>';
	
}       
	
	
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