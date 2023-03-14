$(document).ready(function() {
        cargaTabla();

        $("#agregarCliente").click(function(){
            let validacion = false;
            muestraOculta(validacion);
        });

        $("#enviar").click(function(){
            insertaCliente();
        });

        $("#irAtras").click(function(){
            let validacion = true;
            muestraOculta(validacion);
        });
    }
);

function cargaTabla(){
    let formData = new FormData();
    formData.append('funcion','muestraListadoClientes');
    let ajax = new charpierre_ajax();

    ajax.realizaPeticion("./modulos/cobranza/select_function.php", formData,function(datos){

        if(datos['error']==false){
            arrResultado = datos['resultado'];
            let html = '';
            arrResultado.forEach(function(arr){
                let aut = '';
                if(arr['estatus'] == 'AUTENTICADO'){
                    aut='<td style="color:green;"><i class="feather icon-check-circle" style="color:green; font-size: 22px;"></i> '+arr['estatus']+'</td>';
                }
                else if (arr['estatus'] == 'SIN AUTENTICAR'){
                    aut='<td style="color:red;"><i class="feather icon-x-circle" style="color:red; font-size: 22px;"></i> '+arr['estatus']+'</td>';
                }
                else{
                    aut='<td style="color:blue;"><i class="feather icon-info" style="color:blue; font-size: 22px;"></i> '+arr['estatus']+'</td>';
                }
                let texto = '<tr>'+
                                '<th scope="row">'+arr['id_cliente']+'</th>'+
                                '<td>'+arr['nombre']+'</td>'+
                                '<td>'+arr['correo']+'</td>'+
                                '<td>'+arr['telefono']+'</td>'+
                                aut+
                                '<td>'+
                                    '<button type="button" class="btn btn-success verCliente" id="verCliente" data-id="'+arr['id_cliente']+'"><i class="feather icon-eye"></i></button>'+
                                    '<button type="button" class="btn btn-danger" data-id="'+arr['id_cliente']+'"><i class="feather icon-trash-2"></i></button>'+
                                '</td>'+
                            '</tr>';
                        html = html + texto;
                
            });

            $("#tablaClientes_body").html(html);

            setTimeout(() => {
                let fun = new funciones();
                fun.generaDataTable("#tablaClientes");
            }, 100);
            
            $('.verCliente').on('click',function () {
                muestraCliente(this);
            });
        }
        else{

        }
    });
}


function muestraOculta($oculta,$limpia = true){
    if($oculta == true){
            $('#registraClientes').hide();
            $('#listaCliente').show();
            if($limpia == true){
                $("#nombre").val('');
                $("#apPaterno").val('');
                $("#apMaterno").val('');
                $("#rfc").val('');
                $("#curp").val('');
                $("#calle").val('');
                $("#colonia").val('');
                $("#no_ext").val('');
                $("#no_int").val('');
                $("#telefono").val('');
                $("#email").val('');
            }
            
    }
    else{
            $('#registraClientes').show();
            $('#listaCliente').hide();
    }
}

function insertaCliente(){
    try{
        let formData = new FormData();

        formData.append('funcion','registraCliente');
        formData.append('nombre',$('#nombre').val());
        formData.append('apPaterno',$('#apPaterno').val());
        formData.append('apMaterno',$('#apMaterno').val());
        formData.append('rfc',$('#rfc').val());
        formData.append('curp',$('#curp').val());
        formData.append('calle',$('#calle').val());
        formData.append('colonia',$('#colonia').val());
        formData.append('no_ext',$('#no_ext').val());
        formData.append('no_int',$('#no_int').val());
        formData.append('ciudad',$('#ciudad').val());
        formData.append('estado',$('#estado').val());
        formData.append('cp',$('#cp').val());
        formData.append('telefono',$('#telefono').val());
        formData.append('email',$('#email').val());

        let ajax = new charpierre_ajax();

        ajax.realizaPeticion("./modulos/cobranza/select_function.php", formData,function(datos){

            if(datos['error']==false){
                arrResultado = datos['resultado'];

                let validacion = true;
                muestraOculta(validacion);

                cargaTabla();
            }
            else{
    
            }
        });
    }
    catch(e){

    }
}


function muestraCliente(data){
    let idCliente = $(data).data("id");
    let ajax = new charpierre_ajax();
    let formData = new FormData();

    formData.append('funcion','verCliente');
    formData.append('idCliente',idCliente);

    ajax.realizaPeticion("./modulos/cobranza/select_function.php", formData,function(datos){

        if(datos['error']==false){
            arrResultado = datos['resultado'];
            let validacion = false;
            muestraOculta(validacion,false);

            $('#nombre').val(arrResultado[0]['nombre']);
            $('#apPaterno').val(arrResultado[0]['ap_paterno']);
            $('#apMaterno').val(arrResultado[0]['ap_materno']);
            $('#rfc').val(arrResultado[0]['rfc']);
            $('#curp').val(arrResultado[0]['curp']);
            $('#calle').val(arrResultado[0]['calle']);
            $('#colonia').val(arrResultado[0]['colonia']);
            $('#no_ext').val(arrResultado[0]['no_ext']);
            $('#no_int').val(arrResultado[0]['no_int']);
            $('#ciudad').val(arrResultado[0]['ciudad']);
            $('#estado').val(arrResultado[0]['estado']);
            $('#cp').val(arrResultado[0]['cp']);
            $('#telefono').val(arrResultado[0]['telefono']);
            $('#email').val(arrResultado[0]['mail']);
        }
        else{
            
        }
    });
}