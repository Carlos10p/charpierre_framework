class charpierre_ajax{
    realizaPeticion(archivo){
        $.ajax({
            type : "POST",
            url : archivo,
            success : function(data) {
                console.log("data",data);
            },
            error : function(objXMLHttpRequest) {
                console.log("error",objXMLHttpRequest);
            }
        });
    }
}
