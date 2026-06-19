<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M18",$modulosAcceso)){	
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
            <h1>Combustible <small>Registro</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros" method="POST">
				
				
				             <div class="row">
								<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Vehículo:</label>
                                <select class="form-control" name="vehiculo" id="vehiculo" required>
							    </select>
                              </div>
								
								
							   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Proyecto:</label>
                                <input type="hidden"  name="idcombistible" id="idcombistible" >
                                <select class="form-control" name="proyecto" id="proyecto" required>
								</select>
                              </div>
								
                            </div>
				
                            <div class="row">
								
							   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Año Registro:</label>
								<select class="form-control" name="ano" id="ano" required>	
								</select>	
                                
                              </div> 	
                              
                             <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Mes Registro:</label>
								<select class="form-control" name="MES" id="MES" required>
								<option value="">SELECCIONE...</option>
								<option value="ENERO">ENERO</option>
								<option value="FEBRERO">FEBRERO</option>
								<option value="MARZO">MARZO</option>
								<option value="ABRIL">ABRIL</option>
								<option value="MAYO">MAYO</option>
								<option value="JUNIO">JUNIO</option>
								<option value="JULIO">JULIO</option>
								<option value="AGOSTO">AGOSTO</option>
								<option value="SEPTIEMBRE">SEPTIEMBRE</option>
								<option value="OCTUBRE">OCTUBRE</option>
								<option value="NOVIEMBRE">NOVIEMBRE</option>
								<option value="DICIEMBRE">DICIEMBRE</option>
									
								</select>	
                                
                              </div> 
                            </div>
                
              
                            
  
				
				            <div class="row">
								
								<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Galones:</label>
                              <input type="text" class="form-control" name="combustible" id="combustible" min="0"  required="" onkeypress="Numero(event)" onkeyup="puntostexto(event)">
                              </div> 	
                            
							     <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Kilometros Recorridos:</label>
								<input type="text" class="form-control" name="kilometros" id="kilometros" min="0"  required="" onkeypress="Numero(event)" onkeyup="puntostexto(event)" >
                              </div> 
									
                            </div>
				
				            <div class="row">
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Servicios Atendidos:</label>
                              <input type="text" class="form-control" name="servicios" id="servicios" min="0"  required="" onkeypress="Numero(event)" onkeyup="puntostexto(event)" >
                              </div> 
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Peaje:</label>
							  <input type="text" class="form-control" name="peajes" id="peajes" min="0"  required="" onkeypress="Numero(event)" onkeyup="puntostexto(event)" >
                              </div> 
                            </div>
				
				            <div class="row">
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Mantenimiento:</label>
							  <input type="text" class="form-control" name="mantenimiento" id="mantenimiento" min="0"  required="" onkeypress="Numero(event)" onkeyup="puntostexto(event)" >
                              </div>
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Combustible:</label>
							  <input type="text" class="form-control" name="plataConbustible" id="plataConbustible" min="0"  required="" onkeypress="Numero(event)" onkeyup="puntostexto(event)" >
                              </div>	
                            </div>
				
				            <div class="row">
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Pasos:</label>
							  <input type="text" class="form-control" name="pasos" id="pasos" min="0"  required="" onkeypress="Numero(event)" onkeyup="puntostexto(event)" >
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
							<th >VEHÍCULO</th>
                            <th >PROYECTO</th>
                            <th >PERIODO</th>
                            <th >GALONES</th>
							<th >KILOMETROS</th>
							<th >SERVICIOS</th>
						    <th >PEAJE</th>
							<th >PASOS</th>	
						    <th >MANTENIMIENTO</th>	
						    <th >COMBUSTIBLE</th>		
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

<script type="text/javascript" src="../Ajax/CombustibleAjaxN.js"></script>

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