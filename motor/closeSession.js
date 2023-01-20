
var n = 10;
var id = window.setInterval(
    function(){
        document.onmousemove = function(){
            n =10;
        };
        n--;
        
        if(n == -1){
            
            $('#alerta').modal({backdrop: 'static'});
            
            
            $("#closeSession").click(function(){
                location.href='./?request=logOff';
            });

        }
    },600000);