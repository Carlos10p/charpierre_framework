<?php 
    require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'motor'.DIRECTORY_SEPARATOR.'conexion.php';

    class clientes_funciones{
        function listaClientes(){
            try{
                $datos = [
                    'error'=>false,
                    'resultado'=>array()
                    ];
                $conexion = new conexion();


                $sql=" CALL sp_clientes_muestraTabla();";

                $resultado= $conexion->realizaConsulta($sql);

                    if($conexion->bd_cuentaRegistros($resultado)>0){
                        $resultset = array();
                    
                        while($row = $conexion->bd_dameRegistro($resultado)){
                            
                            $resultset = [
                                            "id_cliente" => $row['id_cliente'],
                                            "nombre" => $row['nombre'],
                                            "correo" => $row['correo'],
                                            "telefono" => $row['telefono'],
                                            "estatus" => $row['estatus'],
                                            "ubicacion" => $row['ubicacion']
                                        ];
                            array_push($datos['resultado'],$resultset);
                        }
                    }
                    else{
                        $datos['error'] = true;
                    }
                return $datos;
            }
            catch(Exception $e){
                echo 'Ocurrio un error'.$e->getMessage();
            }
        }
    }
?>