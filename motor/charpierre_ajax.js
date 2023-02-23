class charpierre_ajax{
    realizaPeticion(archivo,formData,callback){
        let modal = new modalCarga();
        let res = '';
        
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
                modal.abreModal();
                
            },
            success : function(data) {
                try{
                    callback(data);
                }
                catch(e){
                    console.log(e);
                }
                
            },
            error : function(objXMLHttpRequest) {
                console.log("error",objXMLHttpRequest);
                alert('Fallo la petición papito');
                

            }
            ,
            complete:function(){
                modal.cierraModal();
            }
        });
    }
}

class modalCarga{
    abreModal(){
        
        $('#carga').modal({backdrop:false});
        $('.modal-backdrop fade show').hide();
    }
    cierraModal(){
        $('#carga').hide("fadeOut");
        $('#carga').modal({backdrop:false});
    }
}


