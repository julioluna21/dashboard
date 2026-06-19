<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M40",$modulosAcceso)){	
include('header.php');
?>



 <div id="page-content-wrapper" style="">
	 
	 
	 
	 
        <div class="x_panel" style="background-color: white;color: black;">
          
        <div class="x_content">
			  
		<div class=" shadow p-3  mb-5 bg-white rounded">
			
			
	
			
			
		 <div class="x_title  ">
            <h1>Reporte asistenciales <small>Datos</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

          
			  
			<div class="panel-body" style="width:100%" id="listadoregistros">
              <div class="row" style="width:100%">
                <div style="width:100%">
                  <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist" style="width:100%">
                      <a class="nav-item nav-link active" id="item1" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true" onclick="estado=1;listar();">Reporte</a>  
                    </div>
                  </nav>
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent" style="width:100%">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="x_content">
                        
                        
                     <div class="row">
                              <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Proyecto:</label>
                                 <select class="form-control"  id="proyecto" >
								</select>	
                              </div>
				  
				             <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Fecha Inicial:</label>
                                <input type="date" class="form-control" id="fechaInicio" >
                              </div>
				  
				             <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Fecha Final:</label>
                                <input type="date" class="form-control" id="fechaFin" >
                              </div>
				              <br> <br> <br>
				              <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" >
                             <SPAN title="Descargar Registros">
                                <button class="btn " style="background: #871F1B; color:white; margin-top:33px" onclick="Reporte()" ><i class="fa fa-file-excel-o"></i>
                                </button>
                              </SPAN>   

         
                            </div>
                             
                            </div>    

                   
                     <div class="panel-body table-responsive" >  
						 
				     <div id="invoice">

   
    <div class="invoice overflow-auto">
        

               
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
<script type="text/javascript" src="../Ajax/ReporteAsistencial.js"></script>

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