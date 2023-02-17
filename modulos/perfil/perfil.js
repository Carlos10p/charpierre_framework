
$(document).ready(function() {
        $('#btn-changePass').click(function(){
            let fun = new funciones();
                let resultado = fun.validaChangePassword("newPass1","newPass2");
                if(resultado == false){
                    $("#alertaPass").show("fast");
                    $("#alertaSuccessPass").hide("fast");
                }
                else{
                    
                    alert(md5($("#newPass1").val()));
                    $("#alertaSuccessPass").show("fast");
                    $("#alertaPass").hide("fast");

                    let ajax = new charpierre_ajax();
                    ajax.realizaPeticion("../modulos/perfil/select_function.php","cambiaContraseña");
                    
                }
            }
        );
    }
);