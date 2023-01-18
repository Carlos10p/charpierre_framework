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
				
			?>
			<!-- [ Main Content ] start -->
				<div class="pcoded-main-container">
					<div class="pcoded-content">
						<!-- [ breadcrumb ] start -->
						<div class="page-header">
							<div class="page-block">
								<div class="row align-items-center">
									<div class="col-md-12">
										<div class="page-header-title">
											<h5 class="m-b-10">Nombre de Modulo</h5>
										</div>
										<ul class="breadcrumb">
											<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
											<li class="breadcrumb-item"><a href="#!">Modulo</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!-- [ breadcrumb ] end -->
						<!-- [ Main Content ] start -->
						<div class="row">
							<!-- [ sample-page ] start -->
							<div class="col-sm-12">
								<div class="card">
										<div class="card-header">
											<h5>TITULO DE TARJETA	</h5>
											<div class="card-header-right">
												<div class="btn-group card-option">
													<button type="button" class="btn dropdown-toggle btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														<i class="feather icon-more-horizontal"></i>
													</button>
													<ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
														
													</ul>
												</div>
											</div>
										</div>
										<div class="card-body">
											<p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
												aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui
												officia deserunt mollit anim id est laborum."
											</p>
										</div>
									</div>
							</div>
							<!-- [ sample-page ] end -->
						</div>
						<!-- [ Main Content ] end -->
					</div>
				</div>
				<!-- [ Main Content ] end -->
				<!-- Required Js -->
				<script src="assets/js/vendor-all.min.js"></script>
		<script src="assets/js/plugins/bootstrap.min.js"></script>
		<script src="assets/js/ripple.js"></script>
		<script src="assets/js/pcoded.min.js"></script>
		</body>

	</html>
<?php
	}else{
		header('Necesitas inciar sesión');
	}
?>