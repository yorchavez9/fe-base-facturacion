$(document).ready(function(){

    $("[name=serie]").attr("maxlength",'4');
    $("[name=correlativo]").attr("max",'99999999');

    $("#ruc").on('focus', function(){
        $("#ejm_ruc_emisor").addClass("border-select");
    })
    $("#tipo").on('focus', function(){
        $("#ejm_tipo_comprobante").addClass("border-select");
    })
    $("#serie").on('focus', function(){
        $("#ejm_serie").addClass("border-select");
    })
    $("#numero").on('focus', function(){
        $("#ejm_numero").addClass("border-select");
    })
    $("#total").on('focus', function(){
        $("#ejm_total").addClass("border-select");
    })

    $("#ruc").on('blur', function(){
        $("#ejm_ruc_emisor").removeClass("border-select");
    })
    $("#tipo").on('blur', function(){
        $("#ejm_tipo_comprobante").removeClass("border-select");
    })
    $("#serie").on('blur', function(){
        $("#ejm_serie").removeClass("border-select");
    })
    $("#numero").on('blur', function(){
        $("#ejm_numero").removeClass("border-select");
    })
    $("#total").on('blur', function(){
        $("#ejm_total").removeClass("border-select");
    })


});


var ConsultaCpe =
    {

        Endpoint : `${base}web/buscar-comprobante/`,
        consultarComprobante : function(e)
        {
            e.preventDefault();
            var form = new FormData(document.getElementById('formConsultaCpe'));
            ConsultaCpe.setLinkLoad("#iconConsultaCpe","#btnConsultaCpe","fa-globe")
            $.ajax({
                url : ConsultaCpe.Endpoint,
                data: form,
                type: 'POST',
                cache   :   false,
                processData: false,
                contentType: false,
                success: function(data)
                {
                    $("#resultado_consulta").html(data.message);
                    console.log(data);
                    ConsultaCpe.stopLinkLoad("#iconConsultaCpe","#btnConsultaCpe","fa-globe")

                },
                error   :   function(xhr,ajaxOptions,thrownError)
                {
                    console.log(xhr.status);
                    console.log(thrownError);
                    alert("Ha habido un error al procesar su consulta, intente mas tarde");

                    ConsultaCpe.stopLinkLoad("#iconConsultaCpe","#btnConsultaCpe","fa-globe")

                }
            })
        },
        setLinkLoad   :   function(element,element_parent,class_to_replace)
        {
            $(element).removeClass(`${class_to_replace}`);
            $(element).addClass('spinner-border');
            $(element).addClass('spinner-border-sm');
            $(element_parent).css("pointer-events","none");
        },
        stopLinkLoad      :   function(element,element_parent,class_to_set)
        {
            $(element).removeClass('spinner-border');
            $(element).removeClass('spinner-border-sm');
            $(element).addClass(`${class_to_set}`);
            $(element_parent).css("pointer-events","auto");
        }









    }
