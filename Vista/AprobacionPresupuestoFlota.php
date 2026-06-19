<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M35",$modulosAcceso ) or in_array("M36",$modulosAcceso)){	
include('header.php');
?>

 <div id="page-content-wrapper" style="">
	 
	 
	 
	 
               <div class="x_panel" style="background-color: white;color: black;">
          
          <div class="x_content">
			  
			  
			  
		<div class=" shadow p-3  mb-5 bg-white rounded">
		   
		 <div class="x_title  ">
            <h1>Aprobación Presupuesto <small>Registro</small></h1>
            <div class="clearfix"></div>
          </div><br><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">     
              </div>
			  
		  
			   
            <!-- Listado Registros-->

            <!-- centro listado de articulos-->
        
            <!-- end form for validations -->
			  
			<div class="panel-body" style="width:100%" id="listadoregistros">
              <div class="row" style="width:100%">
                <div style="width:100%">
                  <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist" style="width:100%">
					<?php if(in_array("M35",$modulosAcceso)){ ?>	
                      <a class="nav-item nav-link active" id="item1" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true" onclick="listar(4,0)">RQ</a><?php }?>
					 <?php if(in_array("M36",$modulosAcceso)){?>		
                      <a class="nav-item nav-link " id="item2" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" onclick="listar(3,1)">OC</a><?php } ?>
                     
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
							<th class="">VEHICULO</th>
                            <th class="" style="min-width: 200px;">TIPO MANTENIMIENTO</th>
							<th class="" style="min-width: 200px;">SERVICIO</th>
							<th class="" style="min-width: 200px;">PROYECTO</th>
							<th class="" style="min-width: 150px;">VALOR PRESUPUESTADO</th>
							<th class="" style="min-width: 150px;">NUMERO APROBACIÓN</th>
							<th style="min-width: 150px;">ENLACE CLOUDFLEET</th>
							<th class="" style="min-width: 150px;">ACCIÓN</th>
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

<script type="text/javascript" src="../Ajax/AprobacionFlotaAjax.js"></script>

<?php
if(in_array("M35",$modulosAcceso)){
 echo '<script> 
  setTimeout(() => {	 
 listar(4,0);
 }, 1000);
 </script>';	
}else{
 echo '<script> 
  setTimeout(() => {	 
 listar(3,1);
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