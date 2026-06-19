<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M7",$modulosAcceso)){	
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
            <h1>Contratos <small>Registro</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros" method="POST">
                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Nombre Contrato:</label>
                                <input type="hidden"  name="Idcontrato" id="Idcontrato" >
                                <input type="text" class="form-control" name="nombre" id="nombre" min="0"  required="" autofocus>
                              </div>
                             <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>UEN:</label>
								<select class="form-control" name="uen" id="uen" required>
								
								</select>	
                                
                              </div> 
                            </div>
                
              
				
				              <div class="row">
							   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Empresa:</label>
								<select class="form-control" name="empresa" id="empresa" required>
								
								</select>	
                                
                              </div> 
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Cliente:</label>
								<select class="form-control" name="Cliente" id="Cliente" required>
								
								</select>	
                                
                              </div> 	
                            </div>
				
				             <div class="row">
							   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>No OS:</label>
								<input type="text" class="form-control" name="nos" id="nos"  required="" autofocus>
                              </div> 
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Tarifa:</label>
								<select class="form-control" name="tarifa" id="tarifa" required>
								<option value="">Seleccione...</option>
								<option value="FIJA">FIJA</option>
								<option value="VARIABLE">VARIABLE</option>	
								</select>	
                                
                              </div> 	
                            </div>
				
				            <div class="row">
							   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Fecha Inicial:</label>
								<input type="date" class="form-control" name="fechainicio" id="fechainicio"  required="" autofocus>
                              </div> 
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Fecha Final:</label>
								<input type="date" class="form-control" name="fechafinal" id="fechafinal"  required="" >
                              </div> 	
                            </div>
				
				           <div class="row">
							   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Valor Mensual:</label>
								<input type="text" class="form-control" name="valorm" id="valorm"  required="" onkeypress="Numero(event)" onkeyup="puntostexto(event)" >
                              </div> 
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Valor Total:</label>
								<input type="text" class="form-control" name="valort" id="valort"  required="" onkeypress="Numero(event)" onkeyup="puntostexto(event)">
                              </div> 	
                            </div>
				
				             <div class="row">
							   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Estado Contrato:</label>
								<select class="form-control" name="EstadoContrato" id="EstadoContrato" required>
								
								</select>	
                                	
								</div>
				
				                 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Objeto Contrato:</label>
								<textarea class="form-control" name="objeto" id="objeto"></textarea>	
                                	
								</div></div>
                
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
                            <th >NOMBRE CONTRATO</th>
                            <th >UEN</th>
                            <th >EMPRESA</th>
							<th >CLIENTE</th>
							<th >ESTADO CONTRATO</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.0.1/progressbar.min.js"></script>
<?php

include('footer.php');

?>

<script type="text/javascript" src="../Ajax/ContratoAjax.js"></script>

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