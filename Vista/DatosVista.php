<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M2",$modulosAcceso)){	
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
</style>

 <div id="page-content-wrapper" style="">
	 
	 
	 
	 
               <div class="x_panel" style="background-color: white;color: black;">
          
          <div class="x_content">
			  
			  
			  
		<div class=" shadow p-3  mb-5 bg-white rounded">
			
			<div id="btnagregar">
			<SPAN title="Subir SCV" style="float:right">
              <!--span - abarcar. Es un contenedor en línea. Sirve para aplicar estilo al texto o agrupar elementos en línea.-->
              <button class="btn btn-secondary" style="margin-left:5px;" id="" onclick="mostrarform(true,false)">
                <!--Al hacer click, muestra el formulario-->
                <i class="fa fa-plus-square">
                  <!--Muestra el texto marcado con un estilo en cursiva o italica.-->
                </i> Subir CSV
              </button>
            </SPAN>
			
		   <SPAN title="Agregar Registro" style="float:right">
              <!--span - abarcar. Es un contenedor en línea. Sirve para aplicar estilo al texto o agrupar elementos en línea.-->
              <button class="btn" style="background: #871F1B; color:white;" id="" onclick="mostrarform(true,true)">
                <!--Al hacer click, muestra el formulario-->
                <i class="fa fa-plus-square">
                  <!--Muestra el texto marcado con un estilo en cursiva o italica.-->
                </i> Nuevo Registro
              </button>
            </SPAN>  
			</div>
			
			
		 <div class="x_title  ">
            <h1>Información Peajes <small>Registro</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros" method="POST">
                            <div class="row">
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Fecha Registro:</label>
							  <input type="hidden"  name="idCargue" id="idCargue" >	  
                              <input type="date" class="form-control" name="fecha" id="fecha" required>
                              </div>	
								
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Centro Operción:</label>
               
                                <select class="form-control" name="centro" id="centro" required>
								</select>	
                                
                              </div>
                             
                            </div>
				
				            <div class="row">
                               <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Categoría:</label>
                                
                                <select class="form-control" name="categoria" id="categoria" required>
								</select>	
                                
                              </div>	
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Total trafico:</label>
                                <input type="text" class="form-control" name="totaltrafico" id="totaltrafico" onkeypress="Numero(event)" onkeyup="puntostexto(event)" required>	
                                
                              </div>	
                             
                            </div>
				
				              <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Total Recudo:</label>
                               <input type="text" class="form-control" name="totalrecudo" id="totalrecudo" onkeypress="Numero(event)" onkeyup="puntostexto(event)" required>	 
                              </div>
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Total Trafico Exclusivo:</label>
                               <input type="text" class="form-control" name="traficoex" id="traficoex" onkeypress="Numero(event)" onkeyup="puntostexto(event)" required>	 
                              </div>	
                             
                            </div>
                
                             <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Total Recudo Exclusivo:</label>
                               <input type="text" class="form-control" name="recudoex" id="recudoex" onkeypress="Numero(event)" onkeyup="puntostexto(event)" required>	 
                              </div>
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Trafico Exentos De Ley:</label>
                               <input type="text" class="form-control" name="traficoexentos" id="traficoexentos" onkeypress="Numero(event)" onkeyup="puntostexto(event)" required>	 
                              </div>	
                             
                            </div>
				
				            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <label>Trafico Exento Conseción:</label>
                               <input type="text" class="form-control" name="traficoConsecion" id="traficoConsecion" required>	 
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
				 
				 
				 
				 
				         <form name="formrcsv" id="formrcsv" enctype="multipart/form-data" method="POST">
                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Centro Operción:</label>
               
                                <select class="form-control" name="centro" id="centro2" required>
								</select>	
                                
                              </div>
								
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">	
							  <br><SPAN class="miarchivo" title="RUT">  
                                 
                                 <input type="file" class="btn btn-secondary" name="miarchivo" id="miarchivo"  >
                                 
                             </SPAN>
                             <label for="miarchivo" ><SPAN >Archivo CSV <i class="fa fa-cloud-upload  "></i></SPAN></label> 	
                             
                            </div></div>
				
				         
                          
                
                             <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="botones2">
                              <SPAN title="Guardar Registro">
                                <button class="btn btn-secondary" type="submit" id="btnGuardar2"><i id="btnguard" class="fa fa-save"></i> Guardar
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
					  <a class="nav-item nav-link " id="item2" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" onclick="estado=2;listar();">MODIFICACIÓN</a>	
                      
                    </div>
                  </nav>
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent" style="width:100%">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="x_content">

                   
                     <div class="panel-body table-responsive" >  
						 
				     <div id="invoice">

   
    <div class="invoice overflow-auto">
        
      
            
              <div class="row">
                              <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Centro Opertivo:</label>
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
                              <SPAN title="Buscar Registro">
                                <button class="btn " style="background: #871F1B; color:white; margin-top:33px" onclick="listar()" ><i class="fa fa-search"></i>
                                </button>
                              </SPAN>       
                            </div>
                             
                            </div>
                <table border="0" cellspacing="0" cellpadding="0" id="tbllistado" style="width:100%;">
                    <thead>
                        <tr>
							<th>CENTRO OPERATIVO</th>
                            <th>CATEGORÍA</th>
							<th>FECHA REGISTRO</th>
							<th>TOTAL TRÁICO</th>
							<th>TOTAL RECUDO</th>
							<th>TOTAL TRÁFICO EXCLISIVOS</th>
							<th>TOTAL RECUDO EXCLUSIVOS</th>
							<th>TRÁFICO EXENTOS DE LEY</th>
							<th>TRÁFICO EXENTOS DE CONSECIÓN</th>
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

<script type="text/javascript" src="../Ajax/DatosAjaxn.js"></script>

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