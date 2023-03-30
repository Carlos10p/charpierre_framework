<?php 
    require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'motor'.DIRECTORY_SEPARATOR.'conexion.php';

    class clientes_funciones{
        function listaCobranzas(){
            try{
                $datos = [
                    'error'=>false,
                    'resultado'=>array()
                    ];
                $conexion = new conexion();


                $sql=" CALL sp_cobranza_muestraLista();";

                $resultado= $conexion->realizaConsulta($sql);

                    if($conexion->bd_cuentaRegistros($resultado)>0){
                        $resultset = array();
                    
                        while($row = $conexion->bd_dameRegistro($resultado)){
                            
                            $resultset = [
                                            "id_cobranza" => $row['id_cobranza'],
                                            "producto" => $row['producto'],
                                            "costo" => $row['costo'],
                                            "fechaPromesaPago" => $row['fechaPromesaPago'],
                                            "vendedor" => $row['vendedor'],
                                            "estatus" => $row['estatus'],
                                            "No_factura" => $row['No_factura'],
                                            "ordenProducción" => $row['ordenProducción'],
                                            "contrato" => $row['contrato']
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
        function muestraCobranza($idCobranza){
            try{
                $datos = [
                    'error'=>false,
                    'resultado'=>array()
                    ];
                $conexion = new conexion();


                $sql=" CALL sp_cobranza_muestraCobranza(".$conexion->procesaNULL($idCobranza,FALSE).");";

                $resultado= $conexion->realizaConsulta($sql);

                    if($conexion->bd_cuentaRegistros($resultado)>0){
                        $resultset = array();
                    
                        while($row = $conexion->bd_dameRegistro($resultado)){
                            
                            $resultset = [
                                            "id_cobranza" => $row['id_cobranza'],
                                            "producto" => $row['producto'],
                                            "costo" => $row['costo'],
                                            "fechaPromesaPago" => $row['fechaPromesaPago'],
                                            "vendedor" => $row['vendedor'],
                                            "estatus" => $row['estatus'],
                                            "No_factura" => $row['No_factura'],
                                            "ordenProducción" => $row['ordenProducción'],
                                            "contrato" => $row['contrato'],
                                            "cliente" => $row['cliente']
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
      
        function registraCobranza(
                                    $producto,
                                    $costo,
                                    $fechaPromesa,
                                    $factura,
                                    $orden,
                                    $contrato,
                                    $cliente,
                                    $vendedor
                                ){
            try{
                $datos = [
                    'error'=>false
                    ];
                $conexion = new conexion();


                $sql="CALL sp_cobranza_registraCobranza(
                                                                ".$conexion->procesaNULL($producto,TRUE).",
                                                                ".$conexion->procesaNULL($costo,FALSE).",
                                                                ".$conexion->procesaNULL($fechaPromesa,TRUE).",
                                                                ".$conexion->procesaNULL($factura,TRUE).",
                                                                ".$conexion->procesaNULL($orden,TRUE).",
                                                                ".$conexion->procesaNULL($contrato,TRUE).",
                                                                ".$conexion->procesaNULL($cliente,FALSE).",
                                                                ".$conexion->procesaNULL($vendedor,TRUE)."
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