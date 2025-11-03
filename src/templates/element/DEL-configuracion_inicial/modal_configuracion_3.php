<div class="modal fade" id="modalSeries" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="width:650px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agrega tus series y correlativos</h5>
                <button type="button" class="close" aria-label="Close" onclick="ConfiguracionSeries.cancelar()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form >
            <div class="modal-body">
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Boletas</label>
                    <div class="col-sm-4 px-1">
                        <input type="text"  class="form-control serie" name="boleta" value="" placeholder="Serie" required style="text-transform:uppercase" maxlength="4">
                    </div>
                    <div class="col-sm-4 px-1">
                        <input type="number"  class="form-control correlativo" name="boleta_correlativo" value="" placeholder="Correlativo" required min="1">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Facturas</label>
                    <div class="col-sm-4 px-1">
                        <input type="text"  class="form-control serie" name="factura" value="" placeholder="Serie" required style="text-transform:uppercase" maxlength="4">
                    </div>
                    <div class="col-sm-4 px-1">
                        <input type="number"  class="form-control correlativo" name="factura_correlativo" value="" placeholder="Correlativo" required min="1">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Guia Remitente</label>
                    <div class="col-sm-4 px-1">
                        <input type="text"  class="form-control serie" name="guia_remitente" value="" placeholder="Serie" required style="text-transform:uppercase" maxlength="4">
                    </div>
                    <div class="col-sm-4 px-1">
                        <input type="number"  class="form-control correlativo" name="guia_remitente_correlativo" value="" placeholder="Correlativo" required min="1">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Nota de Crédito - Factura</label>
                    <div class="col-sm-4 px-1">
                        <input type="text"  class="form-control serie" name="factura_nc" value="" placeholder="Serie" required style="text-transform:uppercase" maxlength="4">
                    </div>
                    <div class="col-sm-4 px-1">
                        <input type="number"  class="form-control correlativo" name="factura_nc_correlativo" value="" placeholder="Correlativo" required min="1">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Nota de Crédito - Boleta</label>
                    <div class="col-sm-4 px-1">
                        <input type="text"  class="form-control serie" name="boleta_nc" value="" placeholder="Serie" required style="text-transform:uppercase" maxlength="4">
                    </div>
                    <div class="col-sm-4 px-1">
                        <input type="number"  class="form-control correlativo" name="boleta_nc_correlativo" value="" placeholder="Correlativo" required min="1">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Nota de Débito - Factura</label>
                    <div class="col-sm-4 px-1">
                        <input type="text"  class="form-control serie" name="factura_nd" value="" placeholder="Serie" required style="text-transform:uppercase" maxlength="4">
                    </div>
                    <div class="col-sm-4 px-1">
                        <input type="number"  class="form-control correlativo" name="factura_nd_correlativo" value="" placeholder="Correlativo" required min="1">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Nota de Débito - Boleta</label>
                    <div class="col-sm-4 px-1">
                        <input type="text"  class="form-control serie" name="boleta_nd" value="" placeholder="Serie" required style="text-transform:uppercase" maxlength="4">
                    </div>
                    <div class="col-sm-4 px-1">
                        <input type="number"  class="form-control correlativo" name="boleta_nd_correlativo" value="" placeholder="Correlativo" required min="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
var ConfiguracionSeries = {
    id: "#modalSeries",
    init : function ($nombre = '') {
        $(ConfiguracionSeries.id).modal('show');
        ConfiguracionSeries.getData();
        ConfiguracionSeries.setData();
        $("#formSeries .correlativo").mask("######");
        $("#formSeries [name=boleta]").mask("BAAA");
        $("#formSeries [name=factura]").mask("FAAA");
        $("#formSeries [name=guia_remitente]").mask("TAAA");
        $("#formSeries [name=factura_nc]").mask("FAAA");
        $("#formSeries [name=boleta_nc]").mask("BAAA");
        $("#formSeries [name=factura_nd]").mask("FAAA");
        $("#formSeries [name=boleta_nd]").mask("BAAA");
    },
    getFormData : function () {
        var form = new FormData(document.getElementById("formSeries"));
        return form;
    },
    validarSeries : function (array) {
        return new Set(array).size!==array.length
    },
    guardarEleccion : function (e) {
        e.preventDefault();


        var arr = [];
        $("#formSeries .serie").each( (i, e) => {
            arr.push($(e).val())
        });
        if(ConfiguracionSeries.validarSeries(arr)){
            return alert("No debe haber Series repetidas");
        }

        $("#formSeries #btn_guardar").prop("disabled", true);
        GlobalSpinner.mostrar();

        var datos = ConfiguracionSeries.getFormData();

        $.ajax({
            url: `${base}configuraciones/api-configuracion-series-emisor`,
            data: datos,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (r) {
                if (r.success) {
                    ConfiguracionSeries.limpiar();
                    ConfiguracionEstablecimientos.init();
                } else {
                    aler(r.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
            complete: function () {
                $("#formSeries #btn_guardar").prop("disabled", false);
                GlobalSpinner.ocultar();
            }
        });
    },
    cancelar : function () {
        var c = confirm("Si cancela la configuracion se asignara todo en modo DEMO. ¿Continuar?");
        if(c){
            $("#formSeries #btn_guardar").prop("disabled", true);
            GlobalSpinner.mostrar();
            $.ajax({
                url: `${base}configuraciones/api-configuracion-cancelar`,
                data: { },
                type: 'POST',
                success: function (r) {
                    if (r.success) {
                        ConfiguracionSeries.cerrar();
                    } else {
                        alert("Ocurrio un error, consulte con el administrador.")
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                },
                complete: function () {
                    $("#formSeries #btn_guardar").prop("disabled", false);
                    GlobalSpinner.ocultar();
                }
            });
        }
    },
    cerrar : function () {
        ConfiguracionSeries.limpiar();
        $(ConfiguracionSeries.id).modal('hide')
    },
    limpiar: function () {
        $("#formSeries [name=boleta]").val("");
        $("#formSeries [name=boleta_correlativo]").val("");
        $("#formSeries [name=factura]").val("");
        $("#formSeries [name=factura_correlativo]").val("");
        $("#formSeries [name=guia_remitente]").val("");
        $("#formSeries [name=guia_remitente_correlativo]").val("");
        $("#formSeries [name=factura_nc]").val("");
        $("#formSeries [name=factura_nc_correlativo]").val("");
        $("#formSeries [name=boleta_nc]").val("");
        $("#formSeries [name=boleta_nc_correlativo]").val("");
        $("#formSeries [name=factura_nd]").val("");
        $("#formSeries [name=factura_nd_correlativo]").val("");
        $("#formSeries [name=boleta_nd]").val("");
        $("#formSeries [name=boleta_nd_correlativo]").val("");
    }
}

</script>
<style>
::placeholder { /* Recent browsers */
    text-transform: none;
}
</style>
