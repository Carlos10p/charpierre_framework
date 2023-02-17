<?php 
    require_once '.'.DIRECTORY_SEPARATOR.'motor'.DIRECTORY_SEPARATOR.'conexion.php';

    class perfil_funciones{
        function changePass($idUser,$contra){
            $datos = false;
            $conexion = new conexion();

            $sql="CALL sp_usuarios_login(
                ".$conexion->procesaNULL($idUser).", 
                ".$conexion->procesaNULL($contra)."
            );";

            $resultado= $conexion->realizaConsulta($sql);

            $num=$conexion->bd_cuentaRegistros($resultado);

            if($num>0){
                
                while($row = $conexion->bd_dameRegistro($resultado)){
                    
                    $datos = $row['resultado'];
                    
                }
            }
            return $datos;
        }
    }
?>