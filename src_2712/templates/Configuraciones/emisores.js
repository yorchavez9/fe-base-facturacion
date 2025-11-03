$(document).ready(function () {
    $("[name=emisor_ruc]").mask("00000000000")

    Emisor.getData()
});

var Emisor = {
    modalId: "#modalEmisor",
    getData: function () {
        var endpoint = `${API_FE}/configuraciones/get-emisor`;
        $.ajax({
            url: endpoint,
            type: "GET",
            success: function (r) {
                if (r.success) {
                    console.log(r);
                    $("#emisor_ruc").html(r.data.emisor_ruc);
                    $("#emisor_raz_social").html(r.data.emisor_razon_social);
                    $("#emisor_usuario_sec").html(r.data.emisor_usuario_sec);

                    $("[name=emisor_ruc]").val(r.data.emisor_ruc)
                    $("[name=emisor_razon_social]").val(r.data.emisor_razon_social)
                    $("[name=emisor_usuario_sec]").val(r.data.emisor_usuario_sec)
                    $("[name=emisor_clave_sol]").val(r.data.emisor_clave_sol)
                    $("[name=certificado_pfx_ruta]").val(r.data.certificado_pfx_ruta)
                    $("[name=emisor_certificado_clave]").val(r.data.emisor_certificado_clave)
                }
            },
        });
    },
    sendData: function (e) {
        e.preventDefault();
        var endpoint = `${API_FE}/configuraciones/set-emisor`;
        var form = new FormData( document.getElementById("formEmisor"));
        $("#btn_guardar").html('Guardar...' + `<i class="fa fa-spinner fa-spin"></i>`)
        $("#btn_guardar").attr('disabled', true)
        $.ajax({
            url: endpoint,
            type: "POST",
            data: form,
            cache   :   false,
            contentType :   false,
            processData :   false,
            success: function (r) {
                console.log(r);
                if (r.success) {
                    alert("Datos actualizados")
                    $("#btn_guardar").html('Guardar')
                    $(Emisor.modalId).modal('hide')
                    Emisor.getData()
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
            complete: function(){
                $("#btn_guardar").html('Guardar')
                $("#btn_guardar").attr('disabled', false)
            }
        });
    },
};
