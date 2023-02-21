<?php 
    require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'motor'.DIRECTORY_SEPARATOR.'conexion.php';

    class perfil_funciones{
        function changePass($idUser,$contra){
            try{
                $datos = [
                    'error'=>false,
                    'resultado'=>false
                    ];
                $conexion = new conexion();


                $sql="CALL sp_cambia_pass(
                    ".$conexion->procesaNULL($idUser,FALSE).", 
                    ".$conexion->procesaNULL($contra,TRUE)."
                );";

                $resultado= $conexion->realizaConsulta($sql);

                    if($conexion->bd_cuentaRegistros($resultado)>0){
                    
                        while($row = $conexion->bd_dameRegistro($resultado)){
                            
                            $datos['resultado'] = $row['resultado'];
                            
                        }
                    }
                return $datos;
            }
            catch(Exception $e){
                echo 'Ocurrio un error'.$e->getMessage();
            }
        }
    }
?>