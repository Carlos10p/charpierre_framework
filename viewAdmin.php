<?php
	//importamos las clases necesarias
	require_once '.'.DIRECTORY_SEPARATOR.'menu.php';
	require_once '.'.DIRECTORY_SEPARATOR.'header.php';
	require_once '.'.DIRECTORY_SEPARATOR.'head.php';
	require_once '.'.DIRECTORY_SEPARATOR.'modulos'.DIRECTORY_SEPARATOR.'home'.DIRECTORY_SEPARATOR.'home.php';
	require_once '.'.DIRECTORY_SEPARATOR.'modulos'.DIRECTORY_SEPARATOR.'cobranza'.DIRECTORY_SEPARATOR.'cobranza.php';


	class viewAdmin{

		function cargaVista(){
			if(isset($_SESSION['user'])){
				//creamos los objetos
				$menu = new menu();
				$header = new header();
				$head = new head();

				$menuView = $menu->muestraMenu();
				$headerView = $header->mostrar();
				$headView = $head->mostrar();

				$cont ='';
				$cont .= '
				<!DOCTYPE html>
				<html lang="es">
					<head>
						'.$headView.'
					</head>
					<body class="">
						<div id ="alerta" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">Sesión Expiro</h5>
								</div>
								<div class="modal-body">
									<p>La sesión caduco por inactividad, vuelve a iniciar de nuevo.</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-danger" data-dismiss="modal"id="closeSession">Cerrar</button>
								</div>
								</div>
							</div>
						</div>
							<!-- [ Pre-loader ] start -->
							<div class="loader-bg">
								<div class="loader-track">
									<div class="loader-fill"></div>
								</div>
							</div>
							<!-- [ Pre-loader ] End -->
							<!-- [ Header ] start -->
							<header class="navbar pcoded-header navbar-expand-lg navbar-light header-blue">
								<div class="m-header">
									<a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
									<a href="#!" class="b-brand">
										<!-- ========   change your logo hear   ============ -->
										<img src="assets/images/logo.png" alt="" class="logo">
										<img src="assets/images/logo-icon.png" alt="" class="logo-thumb">
									</a>
									<a href="#!" class="mob-toggler">
										<i class="feather icon-more-vertical"></i>
									</a>
								</div>
								<div class="collapse navbar-collapse">
									<ul class="navbar-nav ml-auto">						
										<li>
											<div class="dropdown drp-user">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown">
													<i class="feather icon-user"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-right profile-notification">
													<div class="pro-head">
														<img src="assets/images/user/avatar.png" class="img-radius" alt="User-Profile-Image">
														<span>'.$_SESSION['user']['nombre'].'</span>
													</div>
													<ul class="pro-body">
														<li><a href="user-profile.html" class="dropdown-item"><i class="feather icon-user"></i> Mi Cuenta</a></li>
														<li><a href="?request=logOff" class="dropdown-item"><i class="feather icon-log-out"></i> Salir</a></li>
													</ul>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</header>
							<!-- [ Header ] end -->
							';
							$cont .= $menuView;
							
							$modulo ='';
							if(isset($_GET['page'])){
								switch($_GET['page']){
									case 'home':
										$modulo = new viewHome();
										break;
									case 'cobranza':
										$modulo = new viewCobranza();
										break;
									default:
										$modulo = new viewHome();
									break;
								}
							}
							else{
								$modulo = new viewHome();
							}
							$cont.= $modulo->mostrar();
				$cont .='=<!-- [ Main Content ] end -->
					
						<!-- Required Js -->
						<script src="assets/js/vendor-all.min.js"></script>
						<script src="assets/js/plugins/bootstrap.min.js"></script>
						<script src="assets/js/ripple.js"></script>
						<script src="assets/js/pcoded.min.js"></script>
						<script src="./motor/closeSession.js"></script>
					</body>
				</html>';
				return $cont;
			}
			else{
				header("Location: ./");
			}
		}
	}
?>