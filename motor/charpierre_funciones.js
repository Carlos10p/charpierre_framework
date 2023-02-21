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

    generaDataTable(id){
        let tabla = new DataTable(id,{
            language: {
                processing:     "Procesando...",
                search:         "Buscar&nbsp;:",
                lengthMenu:    "Mostrar _MENU_ elementos",
                info:           "Mostrando _START_ elemento a _END_ de _TOTAL_",
                infoEmpty:      "Mostrando artículo 0 a 0 de 0 articulos",
                infoFiltered:   "(filtrado de _MAX_ artículos en total)",
                infoPostFix:    "",
                loadingRecords: "Cargando Datos...",
                zeroRecords:    "Base de datos vacia",
                emptyTable:     "Ningun dato disponible en esta tabla",
                paginate: {
                    first:      "Primero",
                    previous:   "Anterior",
                    next:       "Siguiente",
                    last:       "Ultimo"
                },
                aria: {
                    sortAscending:  ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                }
            }
        } 
        );
    }
}