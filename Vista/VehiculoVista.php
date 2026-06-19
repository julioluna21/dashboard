<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M15",$modulosAcceso)){	
include('header.php');
?>

<style>
input[type="file"]{
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
 border-radius: 2px;
 }
</style>

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
            <h1>Vehiculos <small>Registro</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros"  enctype="multipart/form-data" method="POST">
                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Placa:</label>
                                <input type="hidden"  name="idvehiculo" id="idvehiculo" >
                                <input type="text" class="form-control" name="placa" id="placa" min="0"  required="" autofocus>
                              </div>
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Marca:</label>
                                <input type="text" class="form-control" name="marca" id="marca" min="0"  required="" >
                              </div>	
                            
                            </div>
				
				             <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Modelo:</label>
                                <input type="text" class="form-control" name="modelo" id="modelo" min="0"  required="" >
                              </div>
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Tipo Vehiculo:</label>
                                <select class="form-control" name="tipovh" id="tipovh" required>
								
								</select>	
                              </div>	
                            
                            </div>
				
				
				              <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Propietario:</label>
                                <input type="text" class="form-control" name="propietario" id="propietario" min="0"  required="" >
                              </div>
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Comodato:</label>
                                <select class="form-control" name="comodato" id="comodato" required>
								<option value="">Seleccione..</option>
								<option value="1">PROPIO</option>
								<option value="2">TERCERO</option>
								<option value="3">VINCULADO</option>	
								</select>	
                              </div>	
                            
                            </div>
				
				
				            <div class="row">
								
								<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                                <SPAN class="miarchivo" title="Imagen">  
                                 
                                <input type="file" class="btn btn-secondary" name="miarchivo" id="miarchivo"  onchange="previewImage(event, '#imagen')">
                                 
                               </SPAN><br>
                               <label for="miarchivo" ><SPAN >Imagen <i class="fa fa-picture-o  "></i></SPAN></label> 
                              </div>  
								
								
                              
                            </div>
				
				             <div class="row">
			                 <img src="" id="imagen" class="img-rounded"  style=" display: block;
              margin-left: auto;
             margin-right: auto;
																												 width: 30%;border-radius: 15px;"></div>
                
              
                            
                          
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
                            <th >PLACA</th>
                            <th >MARCA</th>
							<th >MODELO</th>
							<th >TIPO VEHICULO</th>
							<th >PROPIETARIO</th>
							<th >COMODATO</th>
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

<script type="text/javascript" src="../Ajax/vehiculoAjax.js"></script>

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