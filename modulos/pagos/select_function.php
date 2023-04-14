<?php
session_start();
    //Comprobamos que el valor no venga vacío
if(isset($_POST['funcion']) && !empty($_POST['funcion'])) {
    $funcion = $_POST['funcion'];

    require_once '.'.DIRECTORY_SEPARATOR.'pagos_funciones.php';
    //En función del parámetro que nos llegue ejecutamos una función u otra

    $funciones = new pagos_funciones();
    $resultado = array();

    switch($funcion) {
        //FUNCIÓN 
        case 'muestraListadoPagos': 
            $resultado = $funciones -> listaPagos();
            break;
        case 'verCliente': 
            $resultado = $funciones -> muestraCliente($_POST['idCliente']);
            break;
        case 'registraCliente': 
            $resultado = $funciones -> registraCliente(
                                                        $_POST['nombre'],
                                                        $_POST['apPaterno'],
                                                        $_POST['apMaterno'],
                                                        $_POST['rfc'],
                                                        $_POST['curp'],
                                                        $_POST['calle'],
                                                        $_POST['colonia'],
                                                        $_POST['no_ext'],
                                                        $_POST['no_int'],
                                                        $_POST['ciudad'],
                                                        $_POST['estado'],
                                                        $_POST['cp'],
                                                        $_POST['telefono'],
                                                        $_POST['email']
                                                    );
            break;
    }
    
    echo json_encode($resultado);
}

?>