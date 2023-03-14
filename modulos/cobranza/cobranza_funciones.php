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
        function muestraCliente($idCliente){
            try{
                $datos = [
                    'error'=>false,
                    'resultado'=>array()
                    ];
                $conexion = new conexion();


                $sql=" CALL sp_clientes_muestraCliente(".$conexion->procesaNULL($idCliente,FALSE).");";

                $resultado= $conexion->realizaConsulta($sql);

                    if($conexion->bd_cuentaRegistros($resultado)>0){
                        $resultset = array();
                    
                        while($row = $conexion->bd_dameRegistro($resultado)){
                            
                            $resultset = [
                                            "id_cliente" => $row['id_cliente'],
                                            "nombre" => $row['nombre'],
                                            "ap_paterno" => $row['ap_paterno'],
                                            "ap_materno" => $row['ap_materno'],
                                            "rfc" => $row['rfc'],
                                            "curp" => $row['curp'],
                                            "calle" => $row['calle'],
                                            "cp" => $row['cp'],
                                            "colonia" => $row['colonia'],
                                            "ciudad" => $row['ciudad'],
                                            "estado" => $row['estado'],
                                            "no_ext" => $row['no_ext'],
                                            "no_int" => $row['no_int'],
                                            "telefono" => $row['telefono'],
                                            "mail" => $row['mail'],
                                            "fecha_envio" => $row['fecha_envio'],
                                            "fecha_confirmacion" => $row['fecha_confirmacion'],
                                            "estatus" => $row['estatus'],
                                            "ubicacion" => $row['ubicacion'],
                                            "navegador" => $row['navegador'],
                                            "sistema operativo" => $row['sistema operativo']
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
      
        function registraCliente(
                                    $nombre,
                                    $apPaterno,
                                    $apMaterno,
                                    $rfc,
                                    $curp,
                                    $calle,
                                    $colonia,
                                    $no_ext,
                                    $no_int,
                                    $ciudad,
                                    $estado,
                                    $cp,
                                    $telefono,
                                    $email
                                ){
            try{
                $datos = [
                    'error'=>false
                    ];
                $conexion = new conexion();


                $sql="CALL sp_cliente_registraCliente_sistema(
                                                                ".$conexion->procesaNULL($nombre,TRUE).",
                                                                ".$conexion->procesaNULL($apPaterno,TRUE).",
                                                                ".$conexion->procesaNULL($apMaterno,TRUE).",
                                                                ".$conexion->procesaNULL($rfc,TRUE).",
                                                                ".$conexion->procesaNULL($curp,TRUE).",
                                                                ".$conexion->procesaNULL($calle,TRUE).",
                                                                ".$conexion->procesaNULL($colonia,TRUE).",
                                                                ".$conexion->procesaNULL($cp,TRUE).",
                                                                ".$conexion->procesaNULL($ciudad,TRUE).",
                                                                ".$conexion->procesaNULL($estado,TRUE).",
                                                                ".$conexion->procesaNULL($no_ext,TRUE).",
                                                                ".$conexion->procesaNULL($no_int,TRUE).",
                                                                ".$conexion->procesaNULL($telefono,TRUE).",
                                                                ".$conexion->procesaNULL($email,TRUE)."
                                                            );";

                $conexion->realizaConsulta($sql);

                return $datos;
            }
            catch(Exception $e){
                echo 'Ocurrio un error'.$e->getMessage();
            }
        }
    }
?>