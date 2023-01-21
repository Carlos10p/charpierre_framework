<?php
    //IMPORTAR CLASES NECESARIAS PARA TRABAJAR
    require_once '.'.DIRECTORY_SEPARATOR.'motor'.DIRECTORY_SEPARATOR.'conexion.php';
    require_once '.'.DIRECTORY_SEPARATOR.'sesions.php';

    include_once '.'.DIRECTORY_SEPARATOR.'viewLogin.php';
    include_once '.'.DIRECTORY_SEPARATOR.'viewAdmin.php';

    $sesiones = new sesions();

    $sesiones->sesiones('inicia',null);

    if(isset($_SESSION['user'])){
        if(isset($_GET['request'])){
            $request = $_GET['request'];
            switch($request){
                case 'logOff':
                    $sesiones->sesiones('destruye',null);
                    $login = new viewLogin();
                    echo $login->cargarVista(FALSE);
                    break;
                default:
                    $admin = new viewAdmin();
                    echo $admin->cargaVista();
                    break;
            }
        }
        else{
            $admin = new viewAdmin();
            echo $admin->cargaVista();
        }
    }
    else{
        if(isset($_POST['usuario']) && isset($_POST['contrasena'])){

            $conexion = new conexion();

            $sql="CALL sp_usuarios_login(
                ".$conexion->procesaNULL($_POST['usuario']).", 
                ".$conexion->procesaNULL($_POST['contrasena'])."
            );";

            $resultado= $conexion->realizaConsulta($sql);

            $num=$conexion->bd_cuentaRegistros($resultado);

            if($num>0){
                
                while($row = $conexion->bd_dameRegistro($resultado)){
                    
                    $datos = array(
                                    'id'=>$row['id'],
                                    'usuario'=>$row['usuario'],
                                    'nombre'=>$row['nombre'],
                                    'foto'=>$row['foto']
                                );
                    
                }
                $sesiones = new sesions();
                $sesiones->sesiones('crear',$datos);

                $admin = new viewAdmin();
                echo $admin->cargaVista();
                
            }
            else{
                $login = new viewLogin();
                echo $login->cargarVista(TRUE);
            }
            
        }
        else{

            $login = new viewLogin(TRUE);
            echo $login->cargarVista();
        }
        
    }
?>