
var n = 10;
var id = window.setInterval(
    function(){
        document.onmousemove = function(){
            n =10;
        };
        n--;
        console.log(n);
        if(n <= -1){
            //ELIMINAR TODAS LAS COOKIES
            document.cookie.split(";").forEach(function(c) {
                document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
              });
            //MUESTRA EL MODAL
            $('#alerta').modal({backdrop: 'static'});
        }
    },1200);

    $("#closeSession").click(function(){
        location.href='./?request=logOff';
    });