<?php
    class viewPerfil{
        private $modulojs= '<script src="./modulos/perfil/perfil.js"></script>';
            
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
                                                    <h5 class="m-b-10">Cambiar Contraseña</h5>
                                                </div>
                                                <ul class="breadcrumb">
                                                    <li class="breadcrumb-item"><a href="?page=home"><i class="feather icon-home"></i></a></li>
                                                    <li class="breadcrumb-item"><a href="#!">Cambiar Contraseña</a></li>
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
                                                <h5 class="text-center">CAMBIAR CONTRASEÑA</h5>
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
                                                <div class="row justify-content-center">
                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group fill">
                                                            <div class="alert alert-danger" role="alert" id="alertaPass">
                                                                Los campos no coinciden o estan vacios, intente nuevamente
                                                            </div>
                                                        </div>
                                                        <div class="form-group fill">
                                                            <div class="alert alert-success" role="success" id="alertaSuccessPass">
                                                                El password se ha cambiado con exito.
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-group fill">
                                                            <label class="form-label" for="newPass1">Nueva Contraseña</label>
                                                            <input type="password" class="form-control" id="newPass1" placeholder="Coloca tu nueva contraseña" required>
                                                        </div>
                                                        <div class="form-group fill">
                                                            <label class="form-label" for="newPass2">Confirmacion de contraseña</label>
                                                            <input type="password" class="form-control" id="newPass2" placeholder="Repite la contraseña" required>
                                                        </div>
                                                        <button type="button" id="btn-changePass" class="btn btn-outline-success has-ripple">Cambiar contraseña<span class="ripple ripple-animate" style="height: 90.8281px; width: 90.8281px; animation-duration: 0.7s; animation-timing-function: linear; background: rgb(255, 255, 255); opacity: 0.4; top: -31.5938px; left: -23.3438px;"></span></button>
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
?>