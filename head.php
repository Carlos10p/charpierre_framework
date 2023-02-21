<?php
class head{
    function mostrar(){
        $cont = '';
        $cont = '
                <title>DAKRON MANAGER</title>
                
                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                    
                <!-- Meta -->
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="description" content="" />
                <meta name="keywords" content="">
                <meta name="author" content="Phoenixcoded" />
                <!-- Favicon icon -->
                <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">

                
                <script src="./motor/md5.js"></script>

                <!-- vendor css -->
                <link rel="stylesheet" href="assets/css/style.css">
                <link rel="stylesheet" href="./motor/custom.css">

                <!-- Plugins -->

                <script type="text/javascript" src="./assets/js/plugins/datatables/datatables.min.js"></script>
                <script type="text/javascript" src="./assets/js/plugins/datatables/DataTables-1.13.2/js/dataTables.bootstrap4.min.js"></script>

                <link rel="stylesheet" type="text/css" href="./assets/js/plugins/datatables/datatables.min.css">
                <link rel="stylesheet" type="text/css" href="./assets/js/plugins/datatables/DataTables-1.13.2/css/dataTables.bootstrap4.min.css">
                
                ';

        
        
        return $cont;
    }
    
}
?>
