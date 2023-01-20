<?php
    class sesions{
        function sesiones($peticion,$datos){
            switch($peticion){
                case 'crear':
                    session_start();
                    $_SESSION['user'] = $datos;
                    
                    break;
                case 'inicia':
                        session_start();
                        break;
                case 'destruye':
                    unset($_SESSION['user']);
                    //session_destroy($_SESSION['user']);
                    break;
            }
        }
    }
?>