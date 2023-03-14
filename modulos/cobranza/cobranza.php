<?php 
class viewCobranza{

	private $modulojs= '<script src="./modulos/cobranza/cobranza.js"></script>';

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
												<h5 class="m-b-10">Cobranza</h5>
											</div>
											<ul class="breadcrumb">
												<li class="breadcrumb-item"><a href="?page=home"><i class="feather icon-home"></i></a></li>
												<li class="breadcrumb-item"><a href="#!">Cobranza</a></li>
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
											<h5>LISTA DE VENTAS</h5>
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
															<th scope="col">Producto</th>
															<th scope="col">Costo</th>
															<th scope="col">Googler</th>
															<th scope="col">Promesa Pago</th>
															<th scope="col">Acciones</th>
														</tr>
													</thead>
													<tbody id="tablaClientes_body">
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="card" style="display: none;" id="registraClientes">
										<div class="card-header">
											<h5>REGISTRAR VENTA</h5>
											<div class="card-header-right">
												<div class="btn-group card-option">
													<button type="button" class="btn btn-warning" id="irAtras"><i class="feather icon-arrow-left"></i> Regresar</button>
												</div>
											</div>
										</div>
										<div class="card-body">
												<div class="row">
													
													<div class="col-12">
														<div class="">
															<label for="producto" class="floating-label">Producto</label>
															<input type="text" name="producto" id="producto" class="form-control" required maxlength="60">
														</div>
													</div>
													<div class="col-12">
														<div class="row">
															<div class="col md-6 sm-12">
																<div class="">
																	<label for="costo" class="floating-label">Costo</label>
																	<input type="number" name="costo" id="costo" class="form-control" required maxlength="60">
																</div>
															</div>
															<div class="col md-6 sm-12">
																<div class="">
																	<label for="fechaPromesa" class="floating-label">Fecha Promesa de Pago</label>
																	<input type="date" name="fechaPromesa" id="fechaPromesa" class="form-control" required maxlength="60">
																</div>
															</div>
														</div>
													</div>
													<div class="col md-12">
														<div class="row">
															<div class="col md-6 sm-12">
																<div class="">
																	<label for="factura" class="floating-label">No. Factura</label>
																	<input type="text" name="factura" id="factura" class="form-control" required minlength="10" maxlength="13">
																</div>
															</div>
															<div class="col md-6 sm-12">
																<div class="">
																	<label for="curp" class="floating-label">Orden de Producción</label>
																	<input type="text" name="orden" id="orden" class="form-control" required minlength="18" maxlength="18">
																</div>
															</div>
														</div>
													</div>
													<div class="col-12">
														<div class="row">
															<div class="col md-4 sm-12">
																<div class="">
																	<label for="calle" class="floating-label">Contrato</label>
																	<input type="text" name="contrato" id="contrato" class="form-control" required maxlength="100">
																</div>
															</div>
															<div class="col md-4 sm-12">
																<div class="">
																	<label for="cliente" class="floating-label">Cliente</label>
																	<input type="text" name="cliente" id="cliente" class="form-control" required maxlength="100">
																</div>
															</div>
															<div class="col md-2 sm-12">
																<div class="">
																	<label for="googler" class="floating-label">Googler</label>
																	<input type="text" name="googler" id="googler" class="form-control" required maxlength="10">
																</div>
															</div>
														</div>
														<hr style="margin-top: 10px;">
														<button class="btn btn-outline-success" id="enviar">Continuar</button>
													</div>
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