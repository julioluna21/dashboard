<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M16",$modulosAcceso)){	
include('header.php');
?>


 <div id="page-content-wrapper" style="">
	 
	 
	 
	 
               <div class="x_panel" style="background-color: white;color: black;">
          
          <div class="x_content">
			  
			  
			  
		<div class=" shadow p-3  mb-5 bg-white rounded">
			
			<div id="btnagregar">
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
            <h1>Disponibilidad Flota  <small>Registro</small></h1>
            <div class="clearfix"></div>
			
          </div><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros" method="POST">
                            <div class="row">
							  	
                              <div class="form-group col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                <label>Proyecto:</label>
                                <select class="form-control" name="proyecto" id="proyecto" required>
								</select>	
                                
                              </div>
                             
                            </div>
				
				
				          <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded">
				
				            <div class="row">
                               <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                <label>Vehiculo:</label>
                                
                                <select class="form-control" name="vehiculo" id="vehiculo" >
								</select>	
                                
                              </div>	
								
							  <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">
                              <label>Fecha Inicio Proyecto:</label>  
                              <input type="date" class="form-control" name="fecha" id="fecha" >
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
                                                    <th>Vehiculo</th>
                                                    <th>Fecha Inicio</th>
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
                                <button class="btn" style="background: #871F1B; color:white;" onclick="cancelarform();limpiartabla();"  type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar
                                </button>
                              </SPAN>
                            </div>
                        </div>
                          </form>
				 
				 
				 <form name="formeditar" id="formeditar" method="POST">
                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Proyecto:</label>
                                <input type="hidden"  name="idflota" id="idflota" >
                                <select class="form-control" name="proyecto" id="proyecto2" required>
								</select>	
                                
                              </div>
								
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Vehiculo:</label>
                                <input type="hidden" name="vehiculo" id="vehiculo2">
                                <select class="form-control" name="vehiculom" id="vehiculom" required>
								</select>	
                                
                              </div>	
                             
                            </div>
				            <div class="row">
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Fecha Inicio Proyecto:</label>  
                              <input type="date" class="form-control" name="fecha" id="fecha2" required>                                
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
					  <a class="nav-item nav-link " id="item2" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" onclick="estado=2;listar();">INACTIVOS</a>	
                      
                    </div>
                  </nav>
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent" style="width:100%">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="x_content">

                   
                     <div class="panel-body table-responsive" >  
						 
				     <div id="invoice">

   
    <div class="invoice overflow-auto">
        
      
            
              <div class="row">
                              <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                <label>Proyecto:</label>
                                 <select class="form-control"  id="centrobus" >
								</select>	
                              </div>
				  
				             
				              <br> <br> <br>
				              <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" >
                              <SPAN title="Buscar Registro">
                                <button class="btn " style="background: #871F1B; color:white; margin-top:33px" onclick="listar()" ><i class="fa fa-search"></i>
                                </button>
                              </SPAN>       
                            </div>
                             
                            </div>
                <table border="0" cellspacing="0" cellpadding="0" id="tbllistado" style="width:100%;">
                    <thead>
                        <tr>
							<th>PROYECTO</th>
                            <th>VEHICULO</th>
							<th>TIPO VEHICULO</th>
							<th>FECHA INICIO</th>
							<th>FECHA FIN</th>
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

<script type="text/javascript" src="../Ajax/FlotaAjax.js"></script>

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