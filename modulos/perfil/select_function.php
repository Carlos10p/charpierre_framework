<?php
session_start();
    //Comprobamos que el valor no venga vacío
if(isset($_POST['funcion']) && !empty($_POST['funcion'])) {
    $funcion = $_POST['funcion'];

    require_once '.'.DIRECTORY_SEPARATOR.'perfil_funciones.php';
    //En función del parámetro que nos llegue ejecutamos una función u otra

    $funciones = new perfil_funciones();
    $resultado = array();

    switch($funcion) {
        //FUNCIÓN 
        case 'cambiaContraseña': 
            $resultado = $funciones -> changePass($_SESSION['user']['id'],$_POST['contrasena']);
            break;
    }
    
    echo json_encode($resultado);
}

?>