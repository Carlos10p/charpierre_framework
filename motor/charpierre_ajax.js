class charpierre_ajax{
    realizaPeticion(archivo,formData){
        let datos = '';
        $.ajax({
            //cache: false,
            //contentType: "application/json; charset=utf-8",
            data: formData,
            type : "POST",
            url : archivo,
            dataType: "json",
            enctype: 'multipart/form-data',
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            beforeSend:function(){
                $('#carga').modal();
                
            },
            success : function(data) {
                datos = data;
                $('#carga').modal('hide');
                
                
            },
            error : function(objXMLHttpRequest) {
                console.log("error",objXMLHttpRequest);
                alert('Fallo la petición papito');
                $('#carga').modal('hide');

            }
            ,
            complete:function(){
                
                $('#carga').modal('hide');
                
            }
        });
        return datos;
    }
    
    cierraModal(){
        $('#carga').modal('hide');
    }
}
