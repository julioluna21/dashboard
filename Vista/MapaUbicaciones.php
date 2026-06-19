<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M39",$modulosAcceso)){	
include('header.php');
?>
 <div id="page-content-wrapper" style="">
	 
	 
	 
	 
               <div class="x_panel" style="background-color: white;color: black;">
          
          <div class="x_content">
			  
			  
			  
		<div class=" shadow p-3  mb-5 bg-white rounded">
		   
		 <div class="x_title  ">
            <h1>Mapa de Ubicaciones <small>Proyectos</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

            <!-- Inicio Contenedor Mapa -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;">
				 
				
            <div style="margin-bottom: 20px;">
                <div class="row">
                    <div class="col-md-6">
                        <label for="selectConcesion">Seleccionar Concesión:</label>
                        <select id="selectConcesion" class="form-control">
                            <option value="">-- Ver todas las concesiones --</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="selectPeaje">Seleccionar Peaje:</label>
                        <select id="selectPeaje" class="form-control">
                            <option value="">-- Ver todos los peajes --</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div id="mapContainer" style="width: 100%; height: 600px; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);"></div>
            
            <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
                <h5>Información de Peajes</h5>
                <div id="tablaPerajes" >Cargando información...</div>
            </div>
                      
              </div>
			  
		  
			   
            <!-- Fin Contenedor Mapa-->

              
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

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script type="text/javascript" src="../Ajax/arregloMapa.js"></script>
<script type="text/javascript" src="../Ajax/MapaProyectoAjax.js"></script>

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
