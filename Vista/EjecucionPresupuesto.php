<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M30",$modulosAcceso)){	
include('header.php');
?>


 <div id="page-content-wrapper" style="">
	 
	 
	 
	 
               <div class="x_panel" style="background-color: white;color: black;">
          
          <div class="x_content">
			  
			  
			  
		<div class=" shadow p-3  mb-5 bg-white rounded">
			
			
			
		 <div class="x_title  ">
            <h1>GESTIÓN DEL GASTO  <small>REGISTRO</small></h1>
            <div class="clearfix"></div>
			
          </div><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros" method="POST">
				
                            <div class="row">
							  <input type="hidden" name="idPresupuesto" id="idPresupuesto">
							   <input type="hidden" name="idGestiongasto" id="idGestiongasto">
							  <input type="hidden"  id="mesP">
							  <input type="hidden"  id="anioP">	
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Proveedor:</label>
                                <input type="text" class="form-control" id="proveedor" readonly>
                              </div>
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Unidad Negocio:</label>
                                <input type="text" class="form-control" id="unidad" readonly>
                              </div>	
                             
                            </div>
				
				            <div class="row">
							  	
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Empresa:</label>
                                <input type="text" class="form-control" id="empresa" readonly>
                              </div>
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                                <label>Tiempo Cobro:</label>
                                <input type="text" class="form-control" id="tiempocobro" readonly>
                              </div>	
                             
                            </div>
				           
				          <div class="row">
							  	
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Tipo Pago:</label>
                                <input type="text" class="form-control" id="tipoCobro" readonly>
                              </div>
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Valor Presupuesto:</label>
                                <input type="text" class="form-control"  id="valorPr" readonly>
                              </div>	
                             
                            </div>
				
				            <div class="row">
							  	
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Detalle:</label>
								 <textarea id="detalle" name="detalle" class="form-control"></textarea> 
                              </div>
								
								<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" id="contrato">
                                <label>No Contrato:</label>
								 <input type="text"  id="nContrato" name="nContrato" class="form-control" readonly> 
                              </div>
								
							  
                            </div>
				
				
				
				            <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded">
								
							<div class="row">
                               <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Centro Operativo:</label>
                                <select class="form-control" name="Centroop" id="Centroop" >		
								</select>
                              </div>	
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Item:</label>  
                              <select class="form-control" name="Item" id="Item" >  		
							  </select>
                              </div>
							 </div>	
				
				            <div class="row">	
								
							  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Cantidad:</label>  
                              <input type="text" class="form-control" id="cantidad" onkeypress="Numero(event)" onkeyup="puntostexto(event)" >
                              </div>
								
							  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Valor Unitario:</label>
                                <input type="text" class="form-control" id="valorU" onkeypress="Numero(event)" onkeyup="puntostexto(event)">
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
													<th>Centro Operativo</th>
                                                    <th>Item</th>
                                                    <th>Cantidad</th>
													<th>Valor Unitario</th>
													<th>Valor Total</th>
													<th>Valor Ejecutado</th>
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
				 
                 
                 
              </div>
			  
		  
			   
            <!-- Listado Registros-->

            <!-- centro listado de articulos-->
        
            <!-- end form for validations -->
			  
			<div class="panel-body" style="width:100%" id="listadoregistros">
              <div class="row" style="width:100%">
                <div style="width:100%">
                  <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist" style="width:100%">
                      <a class="nav-item nav-link active" id="item1" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true" onclick="estado=1;listar();">LISTADO DE REGISTROS</a>
						
                      
                    </div>
                  </nav>
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent" style="width:100%">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="x_content">

                   
                     <div class="panel-body" >  
						 
				     <div id="invoice">

   
    <div class="invoice overflow-auto">
        
      
            
		
                 <div id="inicio">
				
				 <div class="row">
                              <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" style="width:20%; margin: 15px auto; text-align: center;">
                                <label>AÑO:</label>
                                 <select class="form-control"  id="anioSe" >
								 <option value="2025">2025</option>
								 <option value="2026">2026</option>	 
								 <option value="2027">2027</option>	 	
								 <option value="2028">2028</option>	 
								 <option value="2029">2029</option>	 	 
								</select>	
                              </div>

                            </div>	 
					<div class="table-responsive">
		          <table border="0" cellspacing="0" cellpadding="0" id="tbmeses" style="width:100%;">
                    <thead>
                        <tr>
							<th>MES</th>
                            <th>AÑO</th>
							<th>VALOR PRESUPUESTO</th>
							<th>VALOR TOTAL</th>
							<th>VALOR COMPRAS</th>
							<th>ESTADO</th>
							<th style="min-width: 120px;">ACCIÓN</th>
							
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                   
                </table></div> 
		         </div>	
		
		        <div id="Gestion"> 
					
					
					
					<div class="row" style="width:30%; margin: auto auto; text-align: center;">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                                <label>AÑO:</label>
								<input type="text" class="form-control" id="anio" readonly>
						</div>
						 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                                <label>MES:</label>	
							    <input type="text" class="form-control" id="mes" readonly>
                              </div>
                             
                            </div>
					
					<div>
		   <SPAN title="VOLVER" style="float:right">
              <!--span - abarcar. Es un contenedor en línea. Sirve para aplicar estilo al texto o agrupar elementos en línea.-->
              <button class="btn" style="background: #871F1B; color:white;" id="" onclick="mostrarform(false,true);tabla.ajax.reload();">
                <!--Al hacer click, muestra el formulario-->
                <i class="fa fa-arrow-left">
                  <!--Muestra el texto marcado con un estilo en cursiva o italica.-->
                </i> VOLVER
              </button>
            </SPAN>  
			</div><br>
					<div class="table-responsive">
					<table border="0" cellspacing="0" cellpadding="0" id="tbllistado" style="width:100%;">
                    <thead>
                        <tr>
							<th style="min-width: 200px;">PROVEEDOR</th>
                            <th style="min-width: 200px;">UEN</th>
							<th style="min-width: 200px;">EMPRESA</th>
							<th>PRESUPUESTO</th>
							<th>VALOR EJECUTADO</th>
							<th>TIEMPO COBRO</th>
							<th>TIPO PAGO</th>
							<th>ESTADO</th>
							<th style="min-width: 120px;">ACCIÓN</th>
							
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                   
						</table></div>
					
			<br>
					
			<div class="row" style="width:30%; margin: auto auto; text-align: center;">
                           <h5>LISTADO DE COMPRAS</h5>
                             
                            </div>	
					
				<div class="panel-body table-responsive" >  
						 
				     <div id="invoice">

   
    <div class="invoice overflow-auto">
        
      
            
              
                <table border="0" cellspacing="0" cellpadding="0" id="tbllistadoGastos" style="width:100%;">
                    <thead>
                        <tr>
                            <th >PROVEEDOR</th>
                            <th >UNIDAD DE NEGOCIO</th>
							<th >EMPRESA</th>
							<th >FECHA COMPRA</th>
						    <th >VALOR</th>	
							<th style="min-width: 150px;">DETALLE</th>
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

<script type="text/javascript" src="../Ajax/GestionGasto.js"></script>

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