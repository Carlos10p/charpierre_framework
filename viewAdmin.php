<?php
	
	if(isset($_SESSION['user'])){
?>
	<!DOCTYPE html>
	<html lang="es">
		<?php
			//CARGAR EL ARCHIVO HEADER PARA EL INICIO DEL FRAMEWORK
			require_once '.'.DIRECTORY_SEPARATOR.'head.php';
		?>
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

		
			<?php
				//CARGAR EL ARCHIVO CON EL MENU
				require_once '.'.DIRECTORY_SEPARATOR.'menu.php';
				//CARGA EL HEADER DEL MENU PRINCIPAL
				require_once '.'.DIRECTORY_SEPARATOR.'header.php';
				
				if(isset($_GET['page'])){
					switch($_GET['page']){
						case 'home':
							require_once './modulos/home/home.php';
							break;
						case 'cobranza':
							require_once './modulos/cobranza/cobranza.php';
							break;
						default:
							require_once './modulos/home/home.php';
						break;
					}
				}
				else{
					require_once './modulos/home/home.php';
				}
			?>
			
				<!-- [ Main Content ] end -->
				
				<!-- Required Js -->
				<script src="assets/js/vendor-all.min.js"></script>
		<script src="assets/js/plugins/bootstrap.min.js"></script>
		<script src="assets/js/ripple.js"></script>
		<script src="assets/js/pcoded.min.js"></script>
		<script src="./motor/closeSession.js"></script>
		</body>

	</html>
<?php
	}
?>