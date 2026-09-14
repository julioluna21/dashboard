<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>                                                                                                                          	
 <title></title>    
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../public/img/consicon.ico" type="image/ico">



  <!--Alertify Style -->

  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
 <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>   
 <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	
 
   <!--Alertify Style -->
  <link href="../vendors/alertify/alertify.bootstrap.css" rel="stylesheet">
  <link href="../vendors/alertify/alertify.core.css" rel="stylesheet">
  <link href="../vendors/alertify/alertify.default.css" rel="stylesheet">
  <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">	
	
	
  <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">

    
    

</head>
	  
 
<body  style="background: #5C5D5D;">  
	

<div class="modal fade" id="modal-clave" tabindex="-1" role="dialog" aria-labelledby="modal-clave-label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modal-preguntas-label">CAMBIAR CONTRASEÑA</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
														  <form style="margin-left: 5%; width:90%" id="formclave" method="POST">
                                                        <div class="modal-body">
                                                          
                                                                <div class="form-group">
																	<input type="hidden" class="form-control" name="idcolaborador" id="iduser" />
                                                                    <label for="pregunta">Contraseña</label>
                                                                    <input type="password" class="form-control" id="clave" name="clave"  required autofocus />
                                                                </div>
                                                                <div class="form-group">
                                                                  <label for="pregunta">Confirme Contraseña</label>
                                                                    <input type="password" class="form-control" id="clave2" name="clave2" required />  
                                                                </div>
                                                            
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn" style="background: #871F1B; color:white;" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-secondary" id="btn-agregar-pregunta">Guardar</button>
                                                        </div>
													</form>		  
                                                    </div>
                                                </div>
                                            </div>	
    
<nav  class="navbar navbar-expand navbar-dark men" style="background: #871F1B;" > <a href="#menu-toggle" id="menu-toggle" class="navbar-brand "><span class="navbar-toggler-icon"></span></a> <button id="btnmenu" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
	        
            <div class="collapse navbar-collapse nav" id="navbarsExample02">
				
                <ul class="navbar-nav mr-auto">
					
				
					
                    <li class="nav-item"> </li>
					<li class="nav-item">
					<div class=" input-group-prepend dropdown">
                            <span class="user"  data-toggle="dropdown"  aria-expanded="false" >
                             <i class="fa fa-angle-down" aria-hidden="true"></i>  <?php echo $_SESSION['nombrecolab']; ?>
                            </span>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-sm" aria-labelledby="Button" >
                                <a class="dropdown-item" data-toggle="modal" data-target="#modal-clave" onclick="clave(<?php echo $_SESSION['Idcolaborador'];?>)"> <i class="fa fa-key" aria-hidden="true"></i> Cambiar contraseña </a>
                                <a class="dropdown-item" for="miarchivo" style="cursor: pointer;" href="../Control/ColaboradorControl.php?op=salir"><i class="fa fa-sign-out" aria-hidden="true"></i> Cerrar sesión</a>
                            </div>
                            </div></li>
                    <li class="nav-item" ></li>
					<li class="nav-item"><img src="../public/img/logoblanco.png"  style="width: 72px; margin-left: 10px;">   </li>
					
					
                </ul>
				
				 
                               
                <form class="form-inline my-2 my-md-0"> </form>
            </div>
        </nav>
	
	
	
	
  	
        <div id="wrapper" class="toggled men" style="height: 100%; background-color: white;">
            <!-- Sidebar -->
            <div id="sidebar-wrapper">
                <ul class="sidebar-nav">
					
		 <li> <a href="inicio.php" >Inicio <i class="fa fa-home" aria-hidden="true" ></i></a> </li>  
		 <?php if(in_array("M1",$modulosAcceso)){ ?>					
         <li> <a href="DashboardVista.php" >Dashboard<i class="fa fa-industry " aria-hidden="true" ></i></a> </li><?php }?>	
					
		<?php if(in_array("M2",$modulosAcceso)){ ?>			
           <li> <a href="DatosVista.php" >Ejecución Peajes<i class="fa fa-train" aria-hidden="true" ></i></a> </li><?php }?>	
		<?php if(in_array("M12",$modulosAcceso)){ ?>			
           <li> <a href="ejecucionAsistencial.php" >Ejecución Asistenciales<i class="fa fa-car" aria-hidden="true" ></i></a> </li><?php }?>			
		<?php if(in_array("M24",$modulosAcceso)){ ?>			
           <li> <a href="NovedadesVista.php" >Novedades<i class="fa fa-sticky-note" aria-hidden="true" ></i></a> </li><?php }?>
        <?php if(in_array("M39",$modulosAcceso)){ ?>			
           <li> <a href="MapaUbicaciones.php" >Mapa Tolis<i class="fa fa-map-marker" aria-hidden="true" ></i></a> </li><?php }?>
        <?php if(in_array("M41",$modulosAcceso)){ ?>
           <li> <a href="MovimientosContablesVista.php" >Movimientos Contables<i class="fa fa-book" aria-hidden="true" ></i></a> </li><?php }?>
        <?php if(in_array("M42",$modulosAcceso)){ ?>
           <li> <a href="GasolinaRawVista.php" >Gasolina<i class="fa fa-tint" aria-hidden="true" ></i></a> </li><?php }?>

        <?php if(in_array("M3",$modulosAcceso) or in_array("M4",$modulosAcceso) or in_array("M5",$modulosAcceso) or in_array("M6",$modulosAcceso) or in_array("M32",$modulosAcceso)){ ?><li>
        <a href="#" class="toggle-submenu"> Configuración <i class="fa fa-cog"></i></a>
        <ul class="submenu">
		  <?php if(in_array("M3",$modulosAcceso)){ ?>		
          <li><a href="Colaboradores.php">Usuarios</a></li><?php }?>	
		   <?php if(in_array("M4",$modulosAcceso)){ ?>		
          <li><a href="CategoriasVista.php">Categorías</a></li><?php }?>	
		   <?php if(in_array("M5",$modulosAcceso)){ ?>		
          <li><a href="CentroOperativoVista.php">Centro De Operación </a></li><?php }?>	
		   <?php if(in_array("M6",$modulosAcceso)){ ?>		
		  <li><a href="UenVista.php">Unidad De Negocio</a></li><?php }?>
		   <?php if(in_array("M32",$modulosAcceso)){ ?>		
		  <li><a href="ProveedorVista.php">Proveedores</a></li><?php }?>	
           <?php if(in_array("M32",$modulosAcceso)){ ?>		
		  <li><a href="TipoeventoAsistencial.php">Tipo Evento Asistencial</a></li><?php }?>	
			
        </ul>
      </li><?php }?>
					
		<?php if(in_array("M7",$modulosAcceso) or in_array("M8",$modulosAcceso) or in_array("M9",$modulosAcceso) or in_array("M10",$modulosAcceso) or in_array("M11",$modulosAcceso) or in_array("M13",$modulosAcceso)){ ?><li>
        <a href="#" class="toggle-submenu"> Configuración Contrato<i class="fa fa-cog"></i></a>
        <ul class="submenu">
           <?php if(in_array("M7",$modulosAcceso)){ ?>	
		   <li><a href="ContratoVista.php">Contrato</a></li><?php }?>
			<?php if(in_array("M8",$modulosAcceso)){ ?>	
		   <li><a href="ProyectoVista.php">Proyectos</a></li><?php }?>
			<?php if(in_array("M9",$modulosAcceso)){ ?>	
			 <li><a href="EjecucionContratoVista.php"> Contrato Peajes</a></li><?php }?>
			<?php if(in_array("M13",$modulosAcceso)){ ?>	
			 <li><a href="EjecucionAsistencialesVista.php"> Contrato Asistenciales</a></li><?php }?>
			<?php if(in_array("M10",$modulosAcceso)){ ?>	
			<li><a href="ClienteVista.php">Cliente</a></li><?php }?>
			<?php if(in_array("M11",$modulosAcceso)){ ?>	
		    <li><a href="SocioVista.php">Socios</a></li><?php }?>	
			
        </ul>
      </li><?php }?>	
					
	  <?php if(in_array("M14",$modulosAcceso) or in_array("M15",$modulosAcceso) or in_array("M16",$modulosAcceso) or in_array("M17",$modulosAcceso) or in_array("M18",$modulosAcceso) or in_array("M27",$modulosAcceso) or in_array("M28",$modulosAcceso)){ ?><li>
        <a href="#" class="toggle-submenu"> ...Configuración Asisten<i class="fa fa-cog"></i></a>
        <ul class="submenu">
		  <?php if(in_array("M14",$modulosAcceso)){ ?>		
          <li><a href="TipoVehiculoVista.php">Tipo Vehiculo</a></li><?php }?>
		  <?php if(in_array("M14S",$modulosAcceso)){ ?>		
          <li><a href="SistemaAfectadoVista.php">Sistema Afectado</a></li><?php }?>
		   <?php if(in_array("M15",$modulosAcceso)){ ?>		
          <li><a href="VehiculoVista.php">Vehiculos</a></li><?php }?>
		  <?php if(in_array("M16",$modulosAcceso)){ ?>		
          <li><a href="DisponibilidadFolota.php">Disponibilidad Flota</a></li><?php }?>
		  <?php if(in_array("M17",$modulosAcceso)){ ?>		
          <li><a href="NovedadesFlota.php">Novedades Flota</a></li><?php }?>	
		  <?php if(in_array("M18",$modulosAcceso)){ ?>		
          <li><a href="CombustibleVista.php">Combustible</a></li><?php }?>	
		  <?php if(in_array("M27",$modulosAcceso)){ ?>		
          <li><a href="ElementoFlota.php">Elementos viales</a></li><?php }?>	
		  <?php if(in_array("M28",$modulosAcceso)){ ?>		
          <li><a href="inventarioVista.php">Inventario</a></li><?php }?>		
        </ul>
      </li><?php }?>
					
		<?php if(in_array("M33",$modulosAcceso) or in_array("M34",$modulosAcceso) or in_array("M35",$modulosAcceso) or in_array("M36",$modulosAcceso)){ ?><li>
        <a href="#" class="toggle-submenu">Presupuesto Flota<i class="fa fa-cog"></i></a>
        <ul class="submenu">
		   <?php if(in_array("M33",$modulosAcceso)){ ?>	
		   <li><a href="TipoMantenimientoVista.php">Sistema</a></li><?php }?>
           <?php if(in_array("M34",$modulosAcceso)){ ?>	
		   <li><a href="PresupuestoFlota.php">Presupuesto</a></li><?php }?>
			<?php if(in_array("M35",$modulosAcceso) or in_array("M36",$modulosAcceso)){ ?>	
		   <li><a href="AprobacionPresupuestoFlota.php">Aprobación</a></li><?php }?>	
		 </ul>
      </li><?php }?>		
					
	   <?php if(in_array("M29",$modulosAcceso) or in_array("M30",$modulosAcceso) or in_array("M37",$modulosAcceso) or in_array("MI1",$modulosAcceso) or in_array("M40",$modulosAcceso)){ ?><li>
        <a href="#" class="toggle-submenu"> Gestión TI<i class="fa fa-cog"></i></a>
        <ul class="submenu">
		   <?php if(in_array("MI1",$modulosAcceso)){ ?>	
		   <li><a href="ItemsVista.php">Item Presupuesto</a></li><?php }?>
           <?php if(in_array("M29",$modulosAcceso)){ ?>	
		   <li><a href="PresupuestoVista.php">Presupuesto</a></li><?php }?>
		   <?php if(in_array("M37",$modulosAcceso)){ ?>	
		   <li><a href="ComprasVista.php">Compras</a></li><?php }?>	
			<?php if(in_array("M30",$modulosAcceso)){ ?>	
		   <li><a href="EjecucionPresupuesto.php">Gestión Gasto</a></li><?php }?>
           <?php if(in_array("M40",$modulosAcceso)){ ?>	
		   <li><a href="ReporteAsistencial.php">Reporte Asistencial</a></li><?php }?>
			
        </ul>
      </li><?php }?>				
		
				
				
                  
                </ul>
            </div> <!-- /#sidebar-wrapper -->    
 
               
        
        
      <!-- /top navigation -->