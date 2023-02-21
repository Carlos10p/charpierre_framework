
$(document).ready(function() {
        $('#btn-changePass').click(function(){
            let fun = new funciones();
                let resultado = fun.validaChangePassword("newPass1","newPass2");
                if(resultado == false){
                    $("#alertaPass").show("fast");
                    $("#alertaSuccessPass").hide("fast");
                }
                else{
                    $("#alertaSuccessPass").show("fast");
                    $("#alertaPass").hide("fast");

                    let formData = new FormData();
                    formData.append('contrasena',$("#newPass1").val());
                    formData.append('funcion','cambiaContraseña');

                    let ajax = new charpierre_ajax();
                    ajax.realizaPeticion("./modulos/perfil/select_function.php", formData);
                    $('#carga').modal('hide');
                    
                }
            }
        );
    }
);