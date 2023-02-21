<?php
session_start();
    //Comprobamos que el valor no venga vacío
if(isset($_POST['funcion']) && !empty($_POST['funcion'])) {
    $funcion = $_POST['funcion'];

    require_once '.'.DIRECTORY_SEPARATOR.'clientes_funciones.php';
    //En función del parámetro que nos llegue ejecutamos una función u otra

    $funciones = new clientes_funciones();
    $resultado = array();

    switch($funcion) {
        //FUNCIÓN 
        case 'muestraListadoClientes': 
            $resultado = $funciones -> listaClientes();
            break;
    }
    
    echo json_encode($resultado);
}

?>