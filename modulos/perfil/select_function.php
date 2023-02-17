<?php
    //Comprobamos que el valor no venga vacío
if(isset($_POST['funcion']) && !empty($_POST['funcion'])) {
    $funcion = $_GET['funcion'];

    require_once '.'.DIRECTORY_SEPARATOR.'perfil_funciones.php';
    //En función del parámetro que nos llegue ejecutamos una función u otra

    $funciones = new perfil_funciones();

    switch($funcion) {
        case 'cambiaContraseña': 
            $resultado = $funciones -> changePass($_SESSION['id'],$_POST['pass']);
            break;
    }
    
    return json_encode($resultado);
}
?>