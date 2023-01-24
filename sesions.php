<?php
    class sesions{
        function sesiones($peticion,$datos){
            switch($peticion){
                case 'crear':
                    $_SESSION['user'] = $datos;
                    break;
                case 'inicia':
                    if(session_status() != PHP_SESSION_ACTIVE){
                        session_start();
                    }
                    break;
                case 'destruye':
                    if(session_status() == PHP_SESSION_ACTIVE){
                        session_destroy();
                        header("Location: ./");
                    }
                    break;
            }
        }
    }
?>