<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
 <title>Consecutivos</title>    
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../public/img/consicon.ico" type="image/ico">

  <!-- Bootstrap -->   
  <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- bootstrap-wysiwyg -->
  <link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
  <!-- Select2 -->
  <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <!-- Switchery -->
  <link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
  <!-- starrr -->
  <link href="../vendors/starrr/dist/starrr.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="../build/css/custom.min.css" rel="stylesheet">

  <!--Alertify Style -->
  <link href="../vendors/alertify/alertify.bootstrap.css" rel="stylesheet">
  <link href="../vendors/alertify/alertify.core.css" rel="stylesheet">
  <link href="../vendors/alertify/alertify.default.css" rel="stylesheet">
    

    
  <!-- DATATABLES >
  <link rel="stylesheet" type="text/css" href="../js/datatables/jquery.dataTables.min.css">
    <link href="../js/datatables/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="../js/datatables/responsive.dataTables.min.css" rel="stylesheet"/-->
    
    

</head>
	
<?php 
            header("Content-Type: text/html;charset=utf-8");
              setlocale(LC_ALL, 'es_Es');
              date_default_timezone_set("America/Lima");
       
      ?>    
 
<body class="nav-md" style="background-color:#ECF0F1 ">
 <main>   
  
    </main> 
  <div class="container body">
      
      
      
      
    <div class="main_container" style="background-color:#ECF0F1">
      <div class="col-md-3 left_col"  style="background-color:#ECF0F1">
        <div class="left_col scroll-view"  style="background-color:#ECF0F1">
          

          <div class="clearfix"></div>

         <br><br>
<!-- Logo -->
 <div class="profile clearfix">
            <div class="profile_pic">
              <img src="../public/img/REGENCYL.png" class="img-rounded" alt="Cinque Terre" style=" display: block;
              margin-left: auto;
             margin-right: auto;
            width: 265%">
            </div>
          </div>
          <!-- /Logo -->

         
          <!-- /menu profile quick info -->

          <br /><br />
          
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              

<div class="menu_section">

 

  <ul class="nav side-menu  ">
      
     <?php 
         if (isset($_SESSION['tipo']) and $_SESSION['tipo']==1) {
           ?>
    <li><a><i class="fa fa-cog" style="color:#C0281B;"></i>Configuración <span style="color:#C0281B;" class="fa fa-chevron-down"></span></a>
      <ul class="nav child_menu">
           <!--
          entrada_epp -- pagina no está
          historial -- pagina no está
        -->
        <li ><a href="Colaboradores.php" style="color:#130202;">Colaborador</a></li>
        <li ><a href="usuarios.php" style="color:#130202;">Usuario</a></li>
        <li ><a href="Aprobacion.php" style="color:#130202;">Aprobación ingreso</a></li>
        <li ><a href="TipoDocumentoVista.php" style="color:#130202;">Categoría Docuento</a></li>  
        <li ><a href="UenVista.php" style="color:#130202;">UEN</a></li>    
      </ul>
    </li><?php } ?>  
      
      <?php if (isset($_SESSION['tipo']) and ($_SESSION['tipo']==1 or $_SESSION['tipo']==4)) {
           ?>
    <li><a><i class="fa fa-barcode" style="color:#C0281B;"></i>Radicados <span style="color:#C0281B;" class="fa fa-chevron-down"></span></a>
      <ul class="nav child_menu">
           <!--
          entrada_epp -- pagina no está
          historial -- pagina no está
        -->
         <li ><a href="Responsable.php" style="color:#130202;">Responsable</a></li>
         <li ><a href="radicado.php" style="color:#130202;">Radicado</a></li>  
         <li ><a href="Conteo.php" style="color:#130202;">Novedades</a></li>    
        
      </ul>
    </li><?php } ?> 
     

    <li><a href="Consecutivo.php"><i class="fa fa-file-text " style="color:#C0281B;"></i>Consecutivo</a>
    </li>
      
         
      

     
      

    <!--<li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> Graficas <span
          class="label label-success pull-right">Muy pronto...</span></a>
    </li>-->

  </ul>
    
    

</div>


<!--<div class="menu_section">

  <h3>Configuración</h3>

  <ul class="nav side-menu">


    <li><a><i class="fa fa-windows"></i> Menú 1 <span class="fa fa-chevron-down"></span></a>
      <ul class="nav child_menu">
        <li><a href="#">Item 1</a></li>
        <li><a href="#">Item 2</a></li>
      </ul>
    </li>

  </ul>

</div>-->

</div>
</div>
      </div>


      <!-- top navigation -->
      <div class="top_nav">
        <div class="nav_menu">
          <nav>
            <div class="nav toggle">
              <a id="menu_toggle" style="color:#9D271D"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">

              <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                  aria-expanded="false">
                  <?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ""; ?>
                   <span style="color:#C0281B;" class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                  <li><a href="usuarios.php?op=canvu"><i class="fa fa-key  pull-right"></i> Cambiar Contraseña </a></li>    
                  <li><a href="../Control/usuarioControl.php?op=salir"><i class="fa fa-sign-out pull-right"></i> Cerrar Sesión </a></li>    
                </ul>
              </li>
            </ul>
          </nav>
        </div>
      </div>
        
        
        
        
        
        
      <!-- /top navigation -->