<?php
    class sesions{
        function sesiones($peticion,$datos){
            switch($peticion){
                case 'crear':
                    $_SESSION['user'] = $datos;
                    break;
                case 'inicia':
                    // if(isset($_SESSION['user'])){
                    //     session_destroy();
                    // }
                    if(session_status() != PHP_SESSION_ACTIVE){
                        session_start();
                    }
                    else{
                        //$this->sesiones('destruye',null);
                    }
                    
                        
                    
                    break;
                case 'destruye':
                    if(session_status() == PHP_SESSION_ACTIVE){
                        //session_start();
                        
                        session_destroy();
                    }
                    
                    break;
            }
        }
    }
?>