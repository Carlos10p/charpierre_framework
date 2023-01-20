<?php
	require_once '.'.DIRECTORY_SEPARATOR.'motor'.DIRECTORY_SEPARATOR.'conexion.php';
	require_once '.'.DIRECTORY_SEPARATOR.'sesions.php';

	$conexion = new conexion();

	$sql="CALL sp_charpierre_listaModulos();";

	$resultado= $conexion->realizaConsulta($sql);

	$num=$conexion->bd_cuentaRegistros($resultado);
	$res= array();

	if($num>0){
		
		while($row = $conexion->bd_dameRegistro($resultado))
		{
			$datos = array(
							'nombre'=>$row['nombre'],
							'ruta'=>$row['ruta'],
							'Icono'=>$row['Icono']
						);
			array_push($res,$datos);
		}
	}
?>
<!-- [ navigation menu ] start -->
<nav class="pcoded-navbar menu-light ">
		<div class="navbar-wrapper  ">
			<div class="navbar-content scroll-div " >
				
				<div class="">
					<div class="main-menu-header">
						<img class="img-radius" src="assets/images/user/avatar.png" alt="User-Profile-Image">
						<div class="user-details">
							<div id="more-details"><?php echo $_SESSION['user']['nombre']; ?><i class="fa fa-caret-down"></i></div>
						</div>
					</div>
					<div class="collapse" id="nav-user-link">
						<ul class="list-unstyled">
							<li class="list-group-item"><a href="?request='logOff'"><i class="feather icon-log-out m-r-5"></i>Salir</a></li>
						</ul>
					</div>
				</div>
				<ul class="nav pcoded-inner-navbar ">
					<li class="nav-item pcoded-menu-caption">
						<label>Navigation</label>
					</li>
					<?php
						for($i=0;$i<count($res);$i++){
							echo '
									<li class="nav-item">
										<a href="?page='. $res[$i]['ruta'].'" class="nav-link "><span class="pcoded-micon"><i class="feather icon-'. $res[$i]['Icono'].'"></i></span><span class="pcoded-mtext">'. $res[$i]['nombre'].'</span></a>
									</li>
								';	
						}
					?>
				</ul>
			</div>
		</div>
	</nav>
	<!-- [ navigation menu ] end -->