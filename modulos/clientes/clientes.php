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
												<h5 class="m-b-10">Clientes</h5>
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
									<div class="card" id="listaCliente">
										<div class="card-header">
											<h5>LISTA DE CLIENTES</h5>
											<div class="card-header-right">
												<div class="btn-group card-option">
													<button type="button" class="btn btn-primary" id="agregarCliente"><i class="feather icon-plus"></i> Registrar</button>
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
									<div class="card" style="display: none;" id="registraClientes">
										<div class="card-header">
											<h5>REGISTRAR CLIENTE</h5>
											<div class="card-header-right">
												<div class="btn-group card-option">
													<button type="button" class="btn btn-warning" id="irAtras"><i class="feather icon-arrow-left"></i> Regresar</button>
												</div>
											</div>
										</div>
										<div class="card-body">
										<form action="confirm.php" method="post">
										<input  type="hidden" value="" name="urlMap" id="urlMap" required>
										<input  type="hidden" value="" name="so"id="so" required>
										<input  type="hidden" value="" name="browser"id="browser" required>
										<div class="row">
											<h5>Datos Personales</h5>
											<div class="col-12">
												<label for="nombre" class="form-label">Nombre</label>
												<input type="text" name="nombre" id="nombre" class="form-control" required maxlength="60">
											</div>
											<div class="col-12">
												<div class="row">
													<div class="col-md-6 sm-12">
														<label for="apPaterno" class="form-label">Apellido Paterno</label>
														<input type="text" name="apPaterno" id="apPaterno" class="form-control" required maxlength="60">
													</div>
													<div class="col-md-6 sm-12">
														<label for="apMaterno" class="form-label">Apellido Materno</label>
														<input type="text" name="apMaterno" id="apMaterno" class="form-control" required maxlength="60">
													</div>
												</div>
											</div>
											<div class="col-12">
												<div class="row">
													<div class="col-md-6 sm-12">
														<label for="rfc" class="form-label">RFC</label>
														<input type="text" name="rfc" id="rfc" class="form-control" required minlength="10" maxlength="13" pattern="([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])">
													</div>
													<div class="col-md-6 sm-12">
														<label for="curp" class="form-label">CURP</label>
														<input type="text" name="curp" id="curp" class="form-control" required minlength="18" maxlength="18" pattern="([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)">
													</div>
												</div>
											</div>
											<h5>Datos de Ubicación</h5>
											<div class="col-12">
												<div class="row">
													<div class="col-md-4 sm-12">
														<label for="calle" class="form-label">Calle</label>
														<input type="text" name="calle" id="calle" class="form-control" required maxlength="100">
													</div>
													<div class="col-md-4 sm-12">
														<label for="colonia" class="form-label">Colonia</label>
														<input type="text" name="colonia" id="colonia" class="form-control" required maxlength="100">
													</div>
													<div class="col-md-2 sm-12">
														<label for="no_ext" class="form-label">No. Exterior</label>
														<input type="text" name="no_ext" id="no_ext" class="form-control" required maxlength="10">
													</div>
													<div class="col-md-2 sm-12">
														<label for="no_int" class="form-label">No. Interior</label>
														<input type="text" name="no_int" id="no_int" class="form-control" maxlength="10">
													</div>
												</div>
											</div>
											<div class="col-12">
												<div class="row">
													<div class="col-md-5 sm-12">
														<label for="ciudad" class="form-label">Ciudad</label>
														<input type="text" name="ciudad" id="ciudad" class="form-control" required maxlength="100">
													</div>
													<div class="col-md-5 sm-12">
														<label for="estado" class="form-label">Estado</label>
														<input type="text" name="estado" id="estado" class="form-control" required maxlength="100">
													</div>
													<div class="col-md-2 sm-12">
														<label for="cp" class="form-label">Codigo Postal</label>
														<input type="number" name="cp" id="cp" class="form-control" required maxlength="5">
													</div>
												</div>
											</div>
											<h5>Datos de Contacto</h5>
											<div class="col-12">
												<div class="row">
													<div class="col-md-6 sm-12">
														<label for="telefono" class="form-label">telefono</label>
														<input type="text" name="telefono" id="telefono" class="form-control" required maxlength="10">
													</div>
													<div class="col-md-6 sm-12">
														<label for="email" class="form-label">E-mail</label>
														<input type="email" name="email" id="email" class="form-control" required maxlength="100">
													</div>
												</div>
											</div>
											<div class="col-12">
												<div class="form-check">
													<br>
													<input class="form-check-input" type="checkbox" value="" id="autoriza" required>
													<label class="form-check-label" for="autoriza">
													Manifiesto estar de acuerdo en hacer uso de medios electrónicos para tener acceso a los servicios ofrecidos por Grupo Dakron, así como para expresar mi consentimiento respeto a los términos y condiciones
													</label>
												</div>
											</div>
											<hr style="margin-top: 10px;">
											<button type="submit" class="btn btn-outline-success" id="enviar">Continuar</button>
										</div>
									</form>
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