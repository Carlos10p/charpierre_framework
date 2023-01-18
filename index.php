<?php
    //IMPORTAR CLASES NECESARIAS PARA TRABAJAR
    require_once '.'.DIRECTORY_SEPARATOR.'motor'.DIRECTORY_SEPARATOR.'conexion.php';
    require_once '.'.DIRECTORY_SEPARATOR.'sesions.php';

    if(isset($_SESSION['user'])){

        $request = $_GET['request'];
        switch($request){
            case 'logOff':
                include_once '.'.DIRECTORY_SEPARATOR.'viewLogin.php';

                $sesiones = new sesions();
                $sesiones->sesiones('destruye',$datos);

                $login = new viewLogin();
                echo $login->cargarVista();

                break;
            default:
                include_once '.'.DIRECTORY_SEPARATOR.'viewAdmin.php';
                break;
            
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
                include_once '.'.DIRECTORY_SEPARATOR.'viewAdmin.php';
                
            }
            else{
                include_once '.'.DIRECTORY_SEPARATOR.'viewLogin.php';

                $login = new viewLogin();
                echo $login->cargarVista(TRUE);
            }
            
        }
        else{
            include_once '.'.DIRECTORY_SEPARATOR.'viewLogin.php';

            $login = new viewLogin();
            echo $login->cargarVista();
            
        }
        
    }
?>