<?php
	//importamos las clases necesarias
	require_once '.'.DIRECTORY_SEPARATOR.'menu.php';
	require_once '.'.DIRECTORY_SEPARATOR.'header.php';
	require_once '.'.DIRECTORY_SEPARATOR.'head.php';
	require_once '.'.DIRECTORY_SEPARATOR.'modulos'.DIRECTORY_SEPARATOR.'home'.DIRECTORY_SEPARATOR.'home.php';
	require_once '.'.DIRECTORY_SEPARATOR.'modulos'.DIRECTORY_SEPARATOR.'cobranza'.DIRECTORY_SEPARATOR.'cobranza.php';
	include_once '.'.DIRECTORY_SEPARATOR.'modulos'.DIRECTORY_SEPARATOR.'perfil'.DIRECTORY_SEPARATOR.'perfil.php';


	class viewAdmin{

		function cargaVista(){
			if(isset($_SESSION['user'])){
				//creamos los objetos
				$menu = new menu();
				$header = new header();
				$head = new head();

				$menuView = $menu->muestraMenu($_SESSION['user']['perfil']);
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
							';
							$cont .= $headerView;
							$cont .= $menuView;
							
							$modulo ='';
							if(isset($_GET['page'])){
								switch($_GET['page']){
									case 'home':
										$modulo = new viewHome();
										break;
									case 'ventas':
										$modulo = new viewCobranza();
										break;
									case 'cobranza':
										$modulo = new viewCobranza();
										break;
									case 'perfil':
										$modulo = new viewPerfil();
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

						<script src="./motor/charpierre_funciones.js"></script>
					
						<!-- Required Js -->
						<script src="assets/js/vendor-all.min.js"></script>
						<script src="assets/js/plugins/bootstrap.min.js"></script>
						<script src="assets/js/ripple.js"></script>
						<script src="assets/js/pcoded.min.js"></script>
						<script src="motor/closeSession.js"></script>
					</body>
				</html>';
				return $cont;
			}
			else{
				header("Location: ./index.php");
			}
		}
	}
?>