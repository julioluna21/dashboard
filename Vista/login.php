<?php  
session_start();
if(!isset($_SESSION['IdUsuarios'])){ 
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
   <!--Made with love by Mutiullah Samim -->
   
	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="../public/css/login.css">
	<link rel="icon" href="../public/img/consicon.ico" type="image/ico">
</head>
<body>
	
<div class="contenedor">
	
	
<div class="modal fade" id="modal-clave" tabindex="-1" role="dialog" aria-labelledby="modal-clave-label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modal-preguntas-label">RECUPERAR CONTRASEÑA</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
														  <form style="margin-left: 5%; width:90%" id="frmrecuperar" method="POST">
                                                        <div class="modal-body">
                                                          
                                                                <div class="form-group">
																	<input type="hidden" class="form-control" name="idcolaborador" id="iduser" />
                                                                    <label for="pregunta">Nombre de usuario</label>
                                                                    <input type="text" class="form-control" id="LoginUsuariosr" name="LoginUsuarios" placeholder="Ingrese nombre de usaurio"  required autofocus />
                                                                </div>
                                                               
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn" style="background: #871F1B; color:white;" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-secondary" id="btrecuperar">Recuperar</button>
                                                        </div>
													</form>		  
                                                    </div>
                                                </div>
                                            </div>		
	
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				
				<img src="../public/img/logoblanco.png"  style="width: 150px;"> 
				
				
			</div>
			<div class="card-body">
				<form name="frmAcceso" id="frmAcceso">
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" class="form-control" placeholder="Ingrese Usuario" name="LoginUsuarios" id="LoginUsuarios"  required>
						
					</div>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" class="form-control" placeholder="Ingrese Contraseña" name="ClaveUsuarios" id="ClaveUsuarios" required>
					</div>
					
					<div class="form-group">
						<input type="submit" value="Iniciar sesión" class="btn  login_btn">
					</div>
					<div class="card-footer">
				<div class="d-flex justify-content-center">
					<a   href="" data-toggle="modal" data-target="#modal-clave">Olvidaste tu clave?</a>
				</div>
			</div>
				</form>
			</div>
			
		</div>
	</div>
</div>
</div>	
  <script src="../vendors/jquery/dist/jquery.min.js"></script>	
<script type="text/javascript" src="../Ajax/loginAjax.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php 
}else{
  echo "<script> 
  window.history.go(-1)
  </script>";  
}
?>