<?php
class head{
    function mostrar(){
        $cont = '';
        $cont = '
                    <title>CHARRPIERRE FRAMEWORK</title>
                    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
                    <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
                    <!--[if lt IE 11]>
                        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
                        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                        <![endif]-->
                    <!-- Meta -->
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <meta name="description" content="" />
                    <meta name="keywords" content="">
                    <meta name="author" content="Phoenixcoded" />
                    <!-- Favicon icon -->
                    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">

                    <script src="./motor/charpierre_funciones.js"></script>

                    <!-- vendor css -->
                    <link rel="stylesheet" href="assets/css/style.css">
                    
                    ';

                    
        
        return $cont;
    }
    
}
?>