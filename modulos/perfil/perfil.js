$(document).ready(function() {
        $('#btn-changePass').click(function(){
                let resultado = validaChangePassword("newPass1","newPass2");
                if(resultado == false){
                    alert("Datos incorrectos");
                }
                else{
                    alert("toddo bien");
                }
            }
        );
    }
);