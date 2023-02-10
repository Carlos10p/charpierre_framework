<?php 
class viewClientes{

	private $modulojs= '<script src="./modulos/clientes/clientes.js"></script>';

		function mostrar(){
			$cont ='
					<!-- [ Main Content ] start -->
					<div class="pcoded-main-container">
						<div class="pcoded-content">
							<!-- [ breadcrumb ] start -->
							<div class="page-header">
								<div class="page-block">
									<div class="row align-items-center">
										<div class="col-md-12">
											<div class="page-header-title">
												<h5 class="m-b-10">Lista de Clientes</h5>
											</div>
											<ul class="breadcrumb">
												<li class="breadcrumb-item"><a href="?page=home"><i class="feather icon-home"></i></a></li>
												<li class="breadcrumb-item"><a href="#!">Clientes</a></li>
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
											<h5>DASHBOARD	</h5>
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
											<div class="table-responsive">
												<table class="table table-striped table-hover" id="tablaClientes">
													<thead>
														<tr>
															<th scope="col">#</th>
															<th scope="col">Nombre Completo</th>
															<th scope="col">Correo</th>
															<th scope="col">Numero de telefono</th>
															<th scope="col">Validación</th>
															<th scope="col">Acciones</th>
														</tr>
													</thead>
													<tbody>
													<tr>
														<th scope="row">1</th>
														<td>Mark</td>
														<td>Otto</td>
														<td>@mdo</td>
														<td><i class="feather icon-check-circle" style="color:green; font-size: 22px;"></i></td>
														<td>
															<button type="button" class="btn btn-secondary"><i class="feather icon-map-pin"></i></button>
															<button type="button" class="btn btn-success"><i class="feather icon-eye"></i></button>
															<button type="button" class="btn btn-danger"><i class="feather icon-trash-2"></i></button>
														</td>
													</tr>
													<tr>
														<th scope="row">2</th>
														<td>Jacob</td>
														<td>Thornton</td>
														<td>@fat</td>
														<td><i class="feather icon-x-circle" style="color:red; font-size: 22px;"></i></td>
														<td>
															<button type="button" class="btn btn-secondary"><i class="feather icon-map-pin"></i></button>
															<button type="button" class="btn btn-success"><i class="feather icon-eye"></i></button>
															<button type="button" class="btn btn-danger"><i class="feather icon-trash-2"></i></button>
														</td>
													</tr>
													<tr>
														<th scope="row">3</th>
														<td>Larry</td>
														<td>the Bird</td>
														<td>@twitter</td>
														<td><i class="feather icon-check-circle" style="color:green; font-size: 22px;"></i></td>
														<td>
															<button type="button" class="btn btn-secondary"><i class="feather icon-map-pin"></i></button>
															<button type="button" class="btn btn-success"><i class="feather icon-eye"></i></button>
															<button type="button" class="btn btn-danger"><i class="feather icon-trash-2"></i></button>
														</td>
													</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<!-- [ sample-page ] end -->
							</div>
							<!-- [ Main Content ] end -->
						</div>
					</div>';

					$cont .= $this->modulojs;

			return $cont;
		}
}