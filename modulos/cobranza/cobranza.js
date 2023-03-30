$(document).ready(function() {
        cargaTabla();

        $("#agregarCobranza").click(function(){
            let validacion = false;
            muestraOculta(validacion);
        });

        $("#enviar").click(function(){
            insertaCobranza();
        });

        $("#irAtras").click(function(){
            let validacion = true;
            muestraOculta(validacion);
        });
    }
);

function cargaTabla(){
    let formData = new FormData();
    formData.append('funcion','muestraListadoCobranzas');
    let ajax = new charpierre_ajax();

    ajax.realizaPeticion("./modulos/cobranza/select_function.php", formData,function(datos){

        if(datos['error']==false){
            arrResultado = datos['resultado'];
            let html = '';
            arrResultado.forEach(function(arr){
                let aut = '';
                if(arr['estatus'] == 'Pagado'){
                    aut='<td style="color:green;"><i class="feather icon-check-circle" style="color:green; font-size: 22px;"></i> '+arr['estatus']+'</td>';
                }
                else if (arr['estatus'] == 'No pagado'){
                    aut='<td style="color:red;"><i class="feather icon-x-circle" style="color:red; font-size: 22px;"></i> '+arr['estatus']+'</td>';
                }
                else{
                    aut='<td style="color:blue;"><i class="feather icon-info" style="color:blue; font-size: 22px;"></i> '+arr['estatus']+'</td>';
                }
                let texto = '<tr>'+
                                '<th scope="row">'+arr['id_cobranza']+'</th>'+
                                '<td>'+arr['producto']+'</td>'+
                                '<td>'+arr['costo']+'</td>'+
                                '<td>'+arr['vendedor']+'</td>'+
                                '<td>'+arr['fechaPromesaPago']+'</td>'+
                                aut+
                                '<td>'+
                                    '<a href="'+arr['No_factura']+'" type="button" class="btn btn-secondary" target="_blank" ><i class="feather icon-file-text"></i></a>'+
                                    '<a href="'+arr['contrato']+'" type="button" class="btn btn-primary" target="_blank" ><i class="feather icon-file-text"></i></a>'+
                                    '<a href="'+arr['ordenProducción']+'" type="button" class="btn btn-warning" target="_blank" ><i class="feather icon-paperclip"></i></a>'+
                                    '<button type="button" class="btn btn-success verCobranza" id="verCobranza" data-id="'+arr['id_cobranza']+'"><i class="feather icon-eye"></i></button>'+
                                    '<button type="button" class="btn btn-danger" data-id="'+arr['id_cobranza']+'"><i class="feather icon-trash-2"></i></button>'+
                                '</td>'+
                            '</tr>';
                        html = html + texto;
                
            });

            $("#tablaCobranzas_body").html(html);

            setTimeout(() => {
                let fun = new funciones();
                fun.generaDataTable("#tablaCobranzas");
            }, 100);
            
            $('.verCobranza').on('click',function () {
                muestraCobranza(this);
            });
        }
        else{

        }
    });
}


function muestraOculta($oculta,$limpia = true){
    if($oculta == true){
            $('#registraCobranzas').hide();
            $('#listaCobranza').show();
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
            $('#registraCobranzas').show();
            $('#listaCobranza').hide();
    }
}

function insertaCobranza(){
    try{
        let formData = new FormData();

        formData.append('funcion','registraCobranza');
        formData.append('producto',$('#producto').val());
        formData.append('costo',$('#costo').val());
        formData.append('fechaPromesa',$('#fechaPromesa').val());
        formData.append('factura',$('#factura').val());
        formData.append('orden',$('#orden').val());
        formData.append('contrato',$('#contrato').val());
        formData.append('cliente',$('#cliente').val());
        formData.append('googler',$('#googler').val());
        

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


function muestraCobranza(data){
    let idCobranza = $(data).data("id");
    let ajax = new charpierre_ajax();
    let formData = new FormData();

    formData.append('funcion','verCobranza');
    formData.append('idCobranza',idCobranza);

    ajax.realizaPeticion("./modulos/cobranza/select_function.php", formData,function(datos){

        if(datos['error']==false){
            arrResultado = datos['resultado'];
            let validacion = false;
            muestraOculta(validacion,false);

            $('#producto').val(arrResultado[0]['producto']);
            $('#costo').val(arrResultado[0]['costo']);
            $('#fechaPromesa').val(arrResultado[0]['fechaPromesaPago']);
            $('#factura').val(arrResultado[0]['No_factura']);
            $('#orden').val(arrResultado[0]['ordenProducción']);
            $('#contrato').val(arrResultado[0]['contrato']);
            $('#cliente').val(arrResultado[0]['cliente']);
            $('#googler').val(arrResultado[0]['vendedor']);
    
        }
        else{
            
        }
    });
}