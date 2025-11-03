var View = {

    generarXml  : function(id){
        let r = confirm("Generar un nuevo XML invalidará el XML anterior, utilice esta función solo si el XML anterior se generó con algun error y si aun está dentro del plazo de envío\n¿Desea continuar?");
        if (r){
            $.ajax({
                url: `${base}sunat-fe-guiarem-remitentes/generar-xml/${id}`,
                data: '',
                type: 'POST',
                success: function (r) {
                    console.log(r);
                    location.reload();
                }
            });
        }
    },

    enviarSunat: function(id){

        $.ajax({
            url: `${base}sunat-fe-guiarem-remitentes/enviar-sunat/${id}`,
            data: '',
            type: 'POST',
            success: function (r) {
                console.log(r);
                location.reload();
            }
        });
    },

    obtenerCDR: function(id){

        $.ajax({
            url: `${base}sunat-fe-guiarem-remitentes/get-cdr/${id}`,
            data: '',
            type: 'POST',
            success: function (r) {
                console.log(r);
                location.reload();
            }
        });
    }

}
