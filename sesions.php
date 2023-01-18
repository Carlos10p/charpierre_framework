<?php
    class sesions{
        function sesiones($peticion,$datos){
            switch($peticion){
                case 'crear':
                    $_SESSION['user'] = $datos;
                    break;
                case 'destruye':
                    unset($_SESSION['user']);
                    break;
            }
        }
    }
?>