<?php
	class header{
		function mostrar(){
			$cont = '';

			$cont .= '
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
										<li><a href="?page=perfil" class="dropdown-item"><i class="feather icon-user"></i> Cambiar contraseña</a></li>
										<li><a href="?request=logOff" class="dropdown-item"><i class="feather icon-log-out"></i> Salir</a></li>
									</ul>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</header>
			<!-- [ Header ] end -->';

			return $cont;
		}
	}
?>
