
<script>
var ConfiguracionModo = {

    setData : function ($nombre) {
        $("#formModo #lbl_emisor").text($nombre);
    },
    guardarEleccion : function (e) {
        e.preventDefault();

        $("#formModo #btn_guardar").prop("disabled", true);
        GlobalSpinner.mostrar();

        var modo = $("#rad_real").prop('checked') ? 'real' : 'demo' ;
        $.ajax({
            url: `${base}configuraciones/api-configuracion-inicial`,
            data: { modo : modo },
            type: 'POST',
            success: function (r) {
                if (r.success) {
                    if(r.data.varvalue == 'PRODUCCION'){
                        ConfiguracionModo.cerrar();
                        ConfiguracionCertificado.init();
                    }else{
                        ConfiguracionModo.cerrar();
                        ConfiguracionEstablecimientos.init();
                    }
                } else {
                    aler(r.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
            complete: function () {
                $("#formModo #btn_guardar").prop("disabled", false);
                GlobalSpinner.ocultar();
            }
        });
    },
    cancelar : function () {
        var c = confirm("Si cancela la configuracion se asignara todo en modo DEMO. ¿Continuar?");
        if(c){
            GlobalSpinner.mostrar();
            $("#formModo #btn_guardar").prop("disabled", true);
            $.ajax({
                url: `${base}configuraciones/api-configuracion-cancelar`,
                data: { modo : 'demo' , complete: 'final' },
                type: 'POST',
                success: function (r) {
                    // console.log(r);
                    if (r.success) {
                        $('#modalModoFacturacion').modal('hide')
                    } else {
                        alert("Ocurrio un error, consulte con el administrador.")
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                },
                complete: function () {
                    $("#formModo #btn_guardar").prop("disabled", false);
                    GlobalSpinner.ocultar();
                }
            });
        }
    },
    cerrar : function () {
        ConfiguracionModo.limpiar();
        $(ConfiguracionModo.id).modal('hide')
    },
    limpiar: function () {
        $("#formModo #lbl_emisor").text("");
        $("#formModo #rad_demo").prop("checked", false);
        $("#formModo #rad_real").prop("checked", false);
        $("#formModo .me-radio").removeClass("active");
    }
}

</script>
