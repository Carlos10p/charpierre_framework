$(document).ready(function() {

        cargaTabla();


        $("#agregarCliente").click(function(){
            $('#registraClientes').show();
            $('#listaCliente').hide();
        });

        $("#irAtras").click(function(){
            $('#registraClientes').hide();
            $('#listaCliente').show();

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
        });
    }
);

function cargaTabla(){
    let formData = new FormData();
    formData.append('funcion','muestraListadoClientes');
    let ajax = new charpierre_ajax();

    ajax.realizaPeticion("./modulos/clientes/select_function.php", formData,function(datos){

        if(datos['error']==false){
            arrResultado = datos['resultado'];
            let html = '';
            arrResultado.forEach(function(arr){
                let aut = '';
                if(arr['estatus'] == 'AUTENTICADO'){
                    aut='<td><i class="feather icon-check-circle" style="color:green; font-size: 22px;"></i> '+arr['estatus']+'</td>';
                }
                else{
                    aut='<td><i class="feather icon-x-circle" style="color:red; font-size: 22px;"></i> '+arr['estatus']+'</td>'
                }
                let texto = '<tr>'+
                                '<th scope="row">'+arr['id_cliente']+'</th>'+
                                '<td>'+arr['nombre']+'</td>'+
                                '<td>'+arr['correo']+'</td>'+
                                '<td>'+arr['telefono']+'</td>'+
                                aut+
                                '<td>'+
                                    '<a href="'+arr['ubicacion']+'" class="btn btn-secondary" data-id="'+arr['id_cliente']+'" target="_blank"><i class="feather icon-map-pin"></i></a>'+
                                    '<button type="button" class="btn btn-success" data-id="'+arr['id_cliente']+'"><i class="feather icon-eye"></i></button>'+
                                    '<button type="button" class="btn btn-danger" data-id="'+arr['id_cliente']+'"><i class="feather icon-trash-2"></i></button>'+
                                '</td>'+
                            '</tr>';
                        html = html + texto;
                
            });

            $("#tablaClientes_body").html(html);

            setTimeout(() => {
                let fun = new funciones();
                fun.generaDataTable("#tablaClientes");
            }, 500);
            
        }
        else{

        }
    });
}

function muestraCliente(idCliente){

}