$(document).ready(function () {
    $("[name=fecha_ini]").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });
    $("[name=fecha_fin]").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });
});


var indexCotizaciones =
    {
        imprimirCotizacion   :   function(venta_id,formato)
        {

            $.ajax({
                url     :   base +  `cotizaciones/api-imprimir-pdf/${venta_id}/${formato}`,
                data    :   '',
                type    :   'GET',

                success :   function (data, status, xhr) {// success callback function
                    console.log(data);
                    if(data.success)
                    {

                        printJS({printable: data.data, type: 'pdf', base64: true, showModal:true, modalMessage: 'Cargando Documento'});

                    }else{
                        alert(data.message);
                    }

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }
    }
