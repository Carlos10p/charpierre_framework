<?php
class viewLogin{
	function cargarVista($error=false){
		$cont='';
		
		$cont .= '
		<!DOCTYPE html>
		<html lang="es">
			<head>
				<title>DAKRON MANAGER</title>
				<!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
				<!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
				<!--[if lt IE 11]>
					<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
					<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
					<![endif]-->
				<!-- Meta -->
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
				<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				<meta name="description" content="" />
				<meta name="keywords" content="">
				<meta name="author" content="Phoenixcoded" />
				<!-- Favicon icon -->
				<link rel="icon" href="assets/images/favicon.png" type="image/x-icon">
				
				<!-- vendor css -->
				<link rel="stylesheet" href="assets/css/style.css">
			</head>
			<body>
				<!-- [ auth-signin ] start -->
				<div class="auth-wrapper">
					<div class="auth-content">
						<div class="card">
							<div class="row align-items-center text-center">
								<div class="col-md-12">
									<div class="card-body">
										<img src="assets/images/logo-dark.png" alt="" class="img-fluid mb-4">';
										if($error==TRUE){
											$cont .= '<div class="form-group mb-3">
														<div class="alert alert-danger" role="alert">
															Contraseña o usuario invalidos, intenta de nuevo.
														</div>
													</div>';
										}
										$cont .= '
										<img src="./assets/images/auth/auth-logo.png" style="width:200px; margin-bottom: 20px;">
										
										<h4 class="mb-3 f-w-400">Ingresar</h4>
										<form action="" method="post">
											<div class="form-group mb-3">
												<label class="floating-label" for="User">Usuario</label>
												<input type="text" name="usuario" class="form-control" id="User" placeholder="">
											</div>
											<div class="form-group mb-4">
												<label class="floating-label" for="Password">Password</label>
												<input type="password" name="contrasena" class="form-control" id="Password" placeholder="">
											</div>
											<input type="submit" name="pass" class="btn btn-block btn-primary mb-4" value="Iniciar sesión"></input>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- [ auth-signin ] end -->
				
				<!-- Required Js -->
				<script src="assets/js/vendor-all.min.js"></script>
				<script src="assets/js/plugins/bootstrap.min.js"></script>
				<script src="assets/js/ripple.js"></script>
				<script src="assets/js/pcoded.min.js"></script>
			</body>
		</html>';

		return $cont;
	}
}

?>