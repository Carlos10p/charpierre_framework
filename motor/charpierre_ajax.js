class charpierre_ajax{
    realizaPeticion(archivo){
        $.ajax({
            type : "POST",
            url : archivo,
            dataType: "json",
            success : function(data) {
                console.log("data",data);
            },
            error : function(objXMLHttpRequest) {
                console.log("error",objXMLHttpRequest);
                error();
            }
        });
    }
    error(){
        alert('Fallo la petición papito');
    }
}
