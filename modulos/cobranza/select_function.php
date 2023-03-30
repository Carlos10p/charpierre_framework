<?php
session_start();
    //Comprobamos que el valor no venga vacío
if(isset($_POST['funcion']) && !empty($_POST['funcion'])) {
    $funcion = $_POST['funcion'];

    require_once '.'.DIRECTORY_SEPARATOR.'cobranza_funciones.php';
    //En función del parámetro que nos llegue ejecutamos una función u otra

    $funciones = new clientes_funciones();
    $resultado = array();

    switch($funcion) {
        //FUNCIÓN 
        case 'muestraListadoCobranzas': 
            $resultado = $funciones -> listaCobranzas();
            break;
            case 'verCobranza': 
                $resultado = $funciones -> muestraCobranza($_POST['idCobranza']);
                break;
        case 'registraCobranza': 
            $resultado = $funciones -> registraCobranza(
                                                        $_POST['producto'],
                                                        $_POST['costo'],
                                                        $_POST['fechaPromesa'],
                                                        $_POST['factura'],
                                                        $_POST['orden'],
                                                        $_POST['contrato'],
                                                        $_POST['cliente'],
                                                        $_POST['googler']
                                                    );
            break;
    }
    
    echo json_encode($resultado);
}

?>