$(document).ready(function() {

        let formData = new FormData();
        formData.append('funcion','muestraListadoClientes');

        let ajax = new charpierre_ajax();
        datos = ajax.realizaPeticion("./modulos/clientes/select_function.php", formData);

        console.log(datos);

        let fun = new funciones();
        fun.generaDataTable("#tablaClientes");

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