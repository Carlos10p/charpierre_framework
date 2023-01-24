function validaChangePassword(idInput1,idInput2){

    let texto1 = $("#"+idInput1).val();
    let texto2 = $("#"+idInput2).val();

    let valor = false;
    if(texto1 == "" || texto2 == ""){
        valor = false;
    }
    else if(texto1 == texto2){
        valor = false;
    }
    else{
        valor = true;
    }
    return valor;
}