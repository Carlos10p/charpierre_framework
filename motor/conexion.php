<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'globales_maestro.class.php';
// Incluye Util
require_once __DIR__ . DIRECTORY_SEPARATOR . 'util.class.php';

class conexion{
    //DECLARO LA VARIABLE DE CONEXION
    private $con;
    //FUNCION CONECTAR DONDE SE CARGA TODOS LOS DATOS DE CONEXION A LA BASE DE DATOS
    private function conectar(){
        $host='195.179.237.0:3306';
        $user='u748616290_prueba';
        $pass='kCWq899C>x2O';
        $db='u748616290_testing';

        $this->con = mysqli_connect($host,$user,$pass);
        mysqli_select_db($this->con,$db);

        if(mysqli_connect_errno())
        {
            echo "La conexión falló: " . mysqli_connect_error();
            return;
        }
        else{
            return $this->con;
        }
    }

    //FUNCION ENCARGADA DE GENERAR LAS CONSULTAS SQL
    function realizaConsulta($sql){

        $consult = mysqli_query($this->conectar(),$sql);
        //$this->bd_desconectar();

        return $consult;
    }

    //FUNCION DESARROLLADA PARA MOSTRAR MENSAJES DE ERROR
    function bd_error($con){
        return mysqli_errno($con);	
    }

    //CONTADOR DE REGISTROS
    function bd_cuentaRegistros($resulset)
    {
    return mysqli_num_rows($resulset);
    }

    //MOSTRADOR DE REGISTROS
    function bd_dameRegistro($resulset)
    {
    return mysqli_fetch_assoc($resulset);
    }
    
    //FUNCION ENCARGADA DE LIMPIAR LOS NULOS EN PHP Y PROCESARLOS
    function procesaNULL(
        $parametro, $esString=TRUE, 
        $conEscape=TRUE, $conTrim=TRUE, $conStripTags=TRUE
    )
    {
        $res = $parametro;
        try {
            if (empty($oGlobal)) {
                $oGlobal = new GlobalesMaestro();
            }
            if ($conTrim) {
                $res = trim($res);
            }
            if ($conStripTags) {
                $res = strip_tags($res);
            }
            if ($conEscape) {
                $res = Util::escapaParametro($res);
            }

            $res = $oGlobal->global_procesaParametroNullSQL($res, $esString);
        } catch (Exception $e) {
            throw new Exception('IE: '.$e->getMessage());
        }

        return $res;
        
    }
    //FUNCION ENCARGADA DE DESCONECTAR LA BASE DE DATOS
    function bd_desconectar(){
        mysqli_close($this->con);
    }
}
?>