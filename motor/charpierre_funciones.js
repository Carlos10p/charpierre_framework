class funciones{
    
     validaChangePassword(idInput1,idInput2){

        let texto1 = $("#"+idInput1).val();
        let texto2 = $("#"+idInput2).val();

        let valor = false;
        if(texto1 == "" || texto2 == ""){
            valor = false;
            this.validaFormularios(idInput1);
            this.validaFormularios(idInput2);
        }
        else if(texto1 != texto2){
            valor = false;

            this.validaFormularios(idInput1);
            this.validaFormularios(idInput2);
        }
        else{
            valor = true;
        }
        return valor;
    }

    validaFormularios(input){
         // [ Initialize validation ] start
         $('#'+input).css("border-bottom"," 1px solid red");
         $('#'+input).css("color"," red");
    }
    modalLoad(){
        let html = "";

        
    }
}