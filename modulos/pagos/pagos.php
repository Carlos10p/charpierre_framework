<?php 
class viewPagos{

	private $modulojs= '<script src="./modulos/pagos/pagos.js"></script>';

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
												<h5 class="m-b-10">Pagos</h5>
											</div>
											<ul class="breadcrumb">
												<li class="breadcrumb-item"><a href="?page=home"><i class="feather icon-home"></i></a></li>
												<li class="breadcrumb-item"><a href="#!">Pagos</a></li>
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
									<div class="card" id="listaPagos">
										<div class="card-header">
											<h5>LISTA DE PAGOS</h5>
											<div class="card-header-right">
												<div class="btn-group card-option">
													<button type="button" class="btn btn-primary" id="agregarPago"><i class="feather icon-plus"></i> Registrar</button>
												</div>
											</div>
										</div>
										<div class="card-body">
											<div class="table-responsive">
												<table class="table table-striped table-hover" id="tablaPagos">
													<thead>
														<tr>
															<th scope="col">#</th>
															<th scope="col">Id_Cobranza</th>
															<th scope="col">Cantidad</th>
															<th scope="col">tipo de Pago</th>
															<th scope="col">Fecha de Pago</th>
															<th scope="col">Acciones</th>
														</tr>
													</thead>
													<tbody id="tablaPagos_body">
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="card" style="display: none;" id="registraPagos">
										<div class="card-header">
											<h5>REGISTRAR PAGO</h5>
											<div class="card-header-right">
												<div class="btn-group card-option">
													<button type="button" class="btn btn-warning" id="irAtras"><i class="feather icon-arrow-left"></i> Regresar</button>
												</div>
											</div>
										</div>
										<div class="card-body">
												<div class="row">
													<div class="col-12">
														<div class="row">
															<div class="col md-5 sm-12">
																<div class="">
																	<label for="idCobranza" class="floating-label">idVenta</label>
																	<input type="number" name="idCobranza" id="idCobranza" class="form-control" required>
																</div>
															</div>
															<div class="col md-5 sm-12">
																<div class="">
																	<label for="cantidad" class="floating-label">Cantidad</label>
																	<input type="number" name="cantidad" id="cantidad" class="form-control" required maxlength="100">
																</div>
															</div>
															<div class="col md-2 sm-12">
																<div class="">
																	<label for="tipoPago" class="floating-label">Tipo Pago</label>
																	<select  name="tipoPago" id="tipoPago" class="form-control" required>
																		<option value="value1" selected>SELECCIONA UNA OPCIÓN</option>
																		<option value="value1">Tarjeta</option>
																		<option value="value2">Efectivo</option>
																		<option value="value3">Transferencia</option>
																	</select>
																</div>
															</div>
														</div>
													</div>
													<div class="col-12">
													</div>
													<div class="col-12">
														<div class="row">
															<div class="col md-6 sm-12">
																<div class="">
																	<label for="fechaPago" class="floating-label">Fecha de Pago</label>
																	<input type="date" name="fechaPago" id="fechaPago" class="form-control" required>
																</div>
															</div>
															<div class="col md-6 sm-12">
																<div class="">
																	<label for="transaccion" class="floating-label">No. transacicion</label>
																	<input type="number" name="transaccion" id="transaccion" class="form-control" required maxlength="100">
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