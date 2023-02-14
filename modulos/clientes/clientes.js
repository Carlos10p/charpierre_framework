$(document).ready(function() {
    
        let fun = new funciones();
        fun.generaDataTable("#tablaClientes");

        $("#agregarCliente").click(function(){
            $('#registraClientes').show();
            $('#listaCliente').hide();
        });
    }
);