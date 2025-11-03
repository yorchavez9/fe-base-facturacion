<div class="modal fade" id="modalCertificado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="width:850px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sube tu certificado</h5>
                <button type="button" class="close" aria-label="Close" onclick="ConfiguracionCertificado.cancelar()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  id="formCertificado" enctype="multipart/form-data" onsubmit="ConfiguracionCertificado.guardarEleccion(event)">
            <div class="modal-body">
                <p class="px-2">
                    <h6>Activa Factura24 con tu usuario secundario de sunat y tu Certificado Digital Tributario</h6>
                    Si tienes dudas o consultas, comunícate con nuestra area de soporte 945 256 714.
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Usuario</label>
                                    <input type="text" class="form-control" id="usuario_txt" name="sunat_usuariosec" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Clave</label>
                                    <input type="text" class="form-control" id="clave_txt" name="sunat_clavesol" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Certificado</label>
                                    <input type="file" name="certificado_pfx_ruta" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Clave</label>
                                    <input type="text" name="certificado_clave" class="form-control" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btn_guardar" >Continuar</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
var ConfiguracionCertificado = {
    id: '#modalCertificado',
    init : function ($nombre = '') {
        $(ConfiguracionCertificado.id).modal('show');
        ConfiguracionCertificado.getData();
        ConfiguracionCertificado.setData();
    },
    getData : function () {
    },
    setData : function () {
    },
    guardarEleccion : function (e) {
        e.preventDefault();
        $("#formCertificado #btn_guardar").prop("disabled", true);
        GlobalSpinner.mostrar();
        var formData = new FormData(document.getElementById("formCertificado"));
        $.ajax({
            url: `${base}configuraciones/api-configuracion-certificado`,
            data: formData,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (r) {
                if (r.success) {
                    ConfiguracionCertificado.cerrar();
                    ConfiguracionSeries.init();
                } else {
                    aler(r.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
            complete: function () {
                $("#formCertificado #btn_guardar").prop("disabled", false);
                GlobalSpinner.ocultar();
            }
        });
    },
    cancelar : function () {
        var c = confirm("Si cancela la configuracion se asignara todo en modo DEMO. ¿Continuar?");
        if(c){
            $("#formCertificado #btn_guardar").prop("disabled", true);
            GlobalSpinner.mostrar();
            $.ajax({
                url: `${base}configuraciones/api-configuracion-cancelar`,
                data: {  },
                type: 'POST',
                success: function (r) {
                    if (r.success) {
                        ConfiguracionCertificado.cerrar();
                    } else {
                        alert("Ocurrio un error, consulte con el administrador.")
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                },
                complete: function () {
                    $("#formCertificado #btn_guardar").prop("disabled", false);
                    GlobalSpinner.ocultar();
                }
            });
        }
    },
    cerrar : function () {
        ConfiguracionCertificado.limpiar();
        $(ConfiguracionCertificado.id).modal('hide')
    },
    limpiar: function () {
        $("#formCertificado [name=sunat_usuariosec]").val("");
        $("#formCertificado [name=sunat_clavesol]").val("");
        $("#formCertificado [name=certificado_pfx_ruta]").val("");
        $("#formCertificado [name=certificado_clave]").val("");
    }
}

</script>
