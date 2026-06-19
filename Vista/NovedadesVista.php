<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M24",$modulosAcceso)){	

?>

<div class="modal fade" id="modal-nota" tabindex="-1" role="dialog" aria-labelledby="modal-nota-label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content" style="width:600px; margin-left: -70px;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modal-preguntas-label">REGISTRO NOVEDAD</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
														  
                                                        <div class="modal-body">
                                                          
                                                            <div id="contenidoNota" class="notas">	
															</div>
                                                            
                                                        </div>
                                                        <div class="modal-footer" style="width:100%; padding: 7px;	">
															
							<form name="formdetalle" id="formdetalle" method="POST" style="width:100%;">
 							<div class="row">								 
																 
							<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12" style="text-align: center;">
							<label>Anotación novedad:</label>
							<input type="hidden" name="idnovedad" id="idnovedad">
							<textarea  class="form-control" id="novedad2" name="novedad" required>
							</textarea>	
                            </div>									 
                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" >
							<br><br>	
                              <SPAN title="Guardar">
                                <button class="btn btn-secondary" type="submit" id="btnGuardar2">Guardar
                                </button>
                              </SPAN>       
<!--ejecuta cancelar formulario-->
                              <SPAN title="Cancelar Registro">
                                <button class="btn" style="background: #871F1B; color:white;" onclick="cancelarform()"  type="button" data-dismiss="modal">Cancelar
                                </button>
                              </SPAN>
                            </div>
                        </div>	
															</form>
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
	 max-height:300px; 
	 text-align: center;  
    
    }
	
	.ntexto{
	 padding: 8px;	
	 padding-bottom: 20px;	
     width: 500px;
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
          
          <div class="x_content" >
			  
			  
			  
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
            <h1>Novedades <small>Registro</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros" method="POST">
                            <div class="row">
 								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Proyecto:</label>
                               <select class="form-control" name="Proyecto" id="Proyecto" required>
							   </select>
                              </div>
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Unidad de negocio:</label>
                               <select class="form-control" name="uen" id="uen" required>
							   </select>
                              </div>	
									
                            </div>
				
				            <div class="row">	
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Titulo Novedad:</label>
                               <input type="text" class="form-control" name="titulo" id="titulo"  required >	
                              </div>	
								
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                             <label>Novedad:</label>	  
							 <textarea  class="form-control" id="novedad" name="novedad">
							 </textarea>	  	
                             </div>	
                            </div>
				
				            <div class="row">
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                             <label>Fecha Novedad:</label>
			                 <input type="date" class="form-control" name="fecha" id="fecha"  required>	
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
                      <a class="nav-item nav-link active" id="item1" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true" onclick="listar(1)">ABIERTAS</a>
                      <a class="nav-item nav-link " id="item2" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" onclick="listar(0)">CERRADAS</a>
                     
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
                            <th >TITULO NOVEDAD</th>
							<th >PROYECTO</th>
							<th >UNIDAD DE NEGOCIO</th>
							<th >FECHA REGISTRO</th>
							<th >FECHA CIERRE</th>
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

<script type="text/javascript" src="../Ajax/NovedadesAjax.js"></script>

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