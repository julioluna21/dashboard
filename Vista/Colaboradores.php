<?php
session_start();
if(isset($_SESSION['IdUsuarios'])){
$modulosAcceso=explode(",",$_SESSION['perfil']);	
if(in_array("M3",$modulosAcceso)){	
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
            <h1>Colaboradores <small>Registro</small></h1>
            <div class="clearfix"></div>
			
          </div><br><br>
			
			

            <!-- Inicio Formulario -->
             <div class="panel-body shadow-lg  p-3 mb-5 bg-white rounded" style="align-content: center;" id="formularioregistros">
				 
				
            <form name="formregistros" id="formregistros" method="POST">
                            <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Cédula Colaborador:</label>
                                <input type="hidden" class="form-control" name="idcolaborador" id="idcolaborador" >
                                <input type="text" class="form-control" name="cedula" id="cedula" min="0"  required="" autofocus>
                              </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Nombre Colaborador:</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" min="0"  required="" autofocus>
                              </div>   
                            </div>
                
              
                            
                            <div class="row">
                                   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Correo Colaborador:</label>
                                <input type="email" class="form-control" name="correo" id="correo" >
                              </div>
							
						
                            </div>
				
				
				              <div class="row">
                                 
								
							<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" >
                              <label>ACCESO:</label><br>  
                         <label>EJECUCIÓN PEJAES:</label>
                         <SPAN title="EJECUCIÓN PEJAES" style="float:right">
                             <input type="checkbox" class="" name="permiso[]" id="M2" style=""  value="M2"  /></SPAN><br>
						<label>EJECUCIÓN ASISTENCIALES:</label>
                           <SPAN title="SOCIOS" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M12"   value="M12"  /> </SPAN><br>		
                        <label>USUARIOS:</label>
                           <SPAN title="USUARIOS" style="float:right">
                               <input type="checkbox" class="" name="permiso[]" id="M3"   value="M3"  /> </SPAN><br>
                            <label>CATEGORIA:</label>
                           <SPAN title="CATEGORIA" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M4"   value="M4"  /> </SPAN><br>     
                           <label>CENTRO OPERATIVO:</label>
                           <SPAN title="CENTRO OPERATIVO" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M5"   value="M5"  /> </SPAN><br>
							<label>UNIDAD DE NEGOCIO:</label>
                           <SPAN title="UNIDAD DE NEGOCIO" style="float:right">
                           <input type="checkbox" class="" name="permiso[]" id="M6"   value="M6"  /> </SPAN><br>
						   <label>CONTRATO:</label>
                           <SPAN title="CONTRATO" style="float:right">
                           <input type="checkbox" class="" name="permiso[]" id="M7" value="M7"  /></SPAN><br>	
						   <label>PROYECTOS:</label>
                           <SPAN title="PROYECTOS" style="float:right">
                             <input type="checkbox" class="" name="permiso[]" id="M8" style=""  value="M8"  /></SPAN><br>
							<label>REGISTRO NOVEDADES:</label>
                            <SPAN title="Registro Novedades" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M24"   value="M24"  /> </SPAN><br>
							 <label>EJECUCIÓN CONTRATO PEAJES:</label>
                           <SPAN title="EJECUCIÓN CONTRATO PEAJES" style="float:right">
                               <input type="checkbox" class="" name="permiso[]" id="M9"   value="M9"  /> </SPAN><br>
							<label>EJECIÓN CONTRATO ASISTENCIALES:</label>
                           <SPAN title="EJECIÓN CONTRATO ASISTENCIALES" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M13"   value="M13"  /> </SPAN><br>
							<label>PROVEEDORES:</label>
                           <SPAN title="PROVEEDORES" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M32"   value="M32"  /> </SPAN><br>	
							<label>CLIENTES:</label>
                           <SPAN title="CLIENTES" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M10"   value="M10"  /> </SPAN><br> 
							<label>SOCIOS:</label>
                           <SPAN title="SOCIOS" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M11"   value="M11"  /> </SPAN><br>	
                            </div>  	
								  
							<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" >
                              <br>
				            <label>TIPO VEHICULO:</label>
                           <SPAN title="TIPO VEHICULO" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M14"   value="M14"  /> </SPAN><br>
							<label>SISTEMA AFECTADO:</label>
                           <SPAN title="SISTEMA AFECTADO" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M14S"   value="M14S"  /> </SPAN><br>	
							<label>VEHICULOS:</label>
                           <SPAN title="VEHICULOS" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M15"   value="M15"  /> </SPAN><br>	
							<label>DISPONIBILIDAD FLOTA:</label>
                           <SPAN title="Disponibilidad Flota" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M16"   value="M16"  /> </SPAN><br>	
							<label>NOVEDADES FLOTA:</label>
                           <SPAN title="Novedades Flota" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M17"   value="M17"  /> </SPAN><br>	
						    <label>COMBUSTIBLE:</label>
                            <SPAN title="Combustible" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M18"   value="M18"  /> </SPAN><br>	
							 <label>ELEMENTOS VIALES:</label>
                           <SPAN title="ELEMENTOS VIALES" style="float:right">
                               <input type="checkbox" class="" name="permiso[]" id="M27"   value="M27"  /> </SPAN><br>
							<label>INVENTARIO ELEMENTOS:</label>
                           <SPAN title="INVENTARIO ELEMENTOS" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M28"   value="M28"  /> </SPAN><br>	
							<label>PRESUPUESTO TI:</label>
                           <SPAN title="PRESUPUESTO TI" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M29"   value="M29"  /> </SPAN><br>	
							<label>GESTION PRESUPUESTO TI:</label>
                           <SPAN title="GESTION PRESUPUESTO TI" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M30"   value="M30"  /> </SPAN><br>	
							<label>COMPRAS:</label>
                           <SPAN title="COMPRAS" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M37"   value="M37"  /> </SPAN><br>	
							<label>ITEMS:</label>
                           <SPAN title="ITEMS" style="float:right">
                           <input type="checkbox" class="" name="permiso[]" id="MI1"   value="MI1"  /> </SPAN><br>		
								
							<label>TIPO MANTENIMIENTO:</label>
                           <SPAN title="MANTENIMIENTO" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M33"   value="M33"  /> </SPAN><br>		
							<label>PRESUPUESTO FLOTA:</label>
                           <SPAN title="PRESUPUESTO FLOTA" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M34"   value="M34"  /> </SPAN><br>	
							<label>APROBAR FLOTA RQ:</label>
                           <SPAN title="APROBAR RQ" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M35"   value="M35"  /> </SPAN><br>	
							<label>APROBAR FLOTA OC:</label>
                           <SPAN title="APROBAR OC" style="float:right">
                            <input type="checkbox" class="" name="permiso[]" id="M36"   value="M36"  /> </SPAN><br>		
                            </div> 
								  
					<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" >
					<br>	
                    <label>DASHBOARD:</label>
                    <SPAN title="DASHBOARD" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M1" value="M1"  /></SPAN><br>
						
				    <label>CONTRATO:</label>
                    <SPAN title="CONTRATO" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M19" value="M19"  /></SPAN><br>
						
					<label>FLOTA:</label>
                    <SPAN title="FLOTA" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M20" value="M20"  /></SPAN><br>
					
					<label>PEAJES:</label>
                    <SPAN title="PEAJES" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M21" value="M21"  /></SPAN><br>
					
					<label>PIPELINE:</label>
                    <SPAN title="PIPELINE" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M22" value="M22"  /></SPAN><br>
					
					<label>OPERACIÓN:</label>
                    <SPAN title="OPERACIÓN" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M23" value="M23"  /></SPAN><br>
					<label>FINANCIERO:</label>
                    <SPAN title="FINANCIERO" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M25" value="M25"  /></SPAN><br>
					<label>GESTIÓN HUMANA:</label>
                    <SPAN title="FINANCIERO" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M26" value="M26"  /></SPAN><br>
					<label>TECNOLOGÍA:</label>
                    <SPAN title="TECNOLOGÍA" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M31" value="M31"  /></SPAN><br>	
                    <label>TOLIS:</label>
                    <SPAN title="TOLIS" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M38" value="M38"  /></SPAN><br>
                     <label>MAPAS TOLIS:</label>
                    <SPAN title="MAPAS TOLIS" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M39" value="M39"  /></SPAN><br>	
                     <label>REPORTE ASISTENCIAL:</label>
                    <SPAN title="REPORTE ASISTENCIAL" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M40" value="M40"  /></SPAN><br>
                     <label>MOVIMIENTOS CONTABLES:</label>
                    <SPAN title="MOVIMIENTOS CONTABLES" style="float:right">
                    <input type="checkbox" class="" name="permiso[]" id="M41" value="M41"  /></SPAN><br>	    
                        	
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
        
      
            
              
                <table border="0" cellspacing="0" cellpadding="0" id="tbllistado">
                    <thead>
                        <tr>
                            <th >CÉDULA</th>
                            <th >NOMBRE</th>
                            <th >CORREO</th>
							<th >ESTADO</th>
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

<script type="text/javascript" src="../Ajax/ColaboradoresAjax.js"></script>

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