<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M32",$modulosAcceso)){	
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
            <h1>PROVEEDOR <small>Registro</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros" method="POST">
                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Nit:</label>
                                <input type="hidden"  name="idproveedor" id="idproveedor" >
                                <input type="text" class="form-control" name="nit" id="nit" min="0"  required=""  onkeypress="Numero(event)"  autofocus>
                              </div>
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Razon social:</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" min="0"  required="" autofocus>
                              </div>	
                            
                            </div>
				
				            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Telefono:</label>
                                <input type="text" class="form-control" name="tel" id="tel" min="0" autofocus>
                              </div>
								
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Correo:</label>
                                <input type="email" class="form-control" name="correo" id="correo" min="0"  autofocus>
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
                            <th >NIT</th>
                            <th >RAZON SOCIAL</th>
							<th >TELEFONO</th>
							<th >CORREO</th>
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

<script type="text/javascript" src="../Ajax/ProveedorAjax.js"></script>

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