<div class="modal fade " id="modalPublicidadReporteError" tabindex="-1" role="dialog" aria-hidden="=true" aria-labelledby="#labelModalPublicidadReporteError">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title modal-title-reporte-error" id="labelModalPublicidadReporteError">
                    Reporta este error <i class="fa fa-smile fa-fw"></i>
                </h3>
                <button class="close" type="button" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-2 ">
                        <img class="img-fluid rounded d-none d-sm-block" src="<?= $this->Url->build("/media/publicidad/banner_publicidad.webp") ?>" alt="">
                        <img class="img-fluid rounded d-sm-none d-block " src="<?= $this->Url->build("/media/publicidad/banner_publicidad_movil.webp") ?>" alt="">
                    </div>
                    <div class="col-12">
                        <form id="formPublicidadReporteError" onsubmit="ModalPublicidadReporteError.enviarDatos(event)">

                            <div class="form-row">
                                <div class="col-12 col-sm-6  col-md-3 my-1 my-sm-0">
                                    <span class="modal-reportar-error-caption" > Déjanos tus datos y reclama tu premio </span>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 my-1 my-sm-0">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-user fa-fw"></i></span></div>
                                        <input name="nombre" type="text" class="form-control form-control-sm" placeholder="Ingresa tu nombre" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-3 my-1 my-sm-0">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fab fa-whatsapp fa-fw"></i></span></div>
                                        <input name="celular" type="text" class="form-control form-control-sm" pattern="[0-9]{1,9}" placeholder="Ingresa tu Whatsapp" required>
                                    </div>
                                </div>
                                <input type="text" hidden name="url_request" value="">
                                <div class="col-12 col-sm-6 col-md-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-paper-plane fa-fw"></i> Reportar
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .modal-title-reporte-error{
        color: #0BBE93;
    }
    .modal-reportar-error-caption{
        color : #046A51;
    }
</style>

<script>
    var ModalPublicidadReporteError = {
        idModal: "modalPublicidadReporteError",
        idModalLabel: "labelModalPublicidadReporteError",
        idForm: "formPublicidadReporteError",
        endpoint_base: `${base}configuraciones`,
        abrir: () => {
            ModalPublicidadReporteError.open();
            ModalPublicidadReporteError.reportarError();
        },
        clean: () => {
            ModalPublicidadReporteError.setField('nombre', '');
            ModalPublicidadReporteError.setField('celular', '');
            ModalPublicidadReporteError.setField('url_request', '');
        },
        open: () => {
            $(`#${ModalPublicidadReporteError.idModal}`).modal("show");

        },
        close: () => {
            $(`#${ModalPublicidadReporteError.idModal}`).modal("hide");
        },
        reportarError: () => {
            GlobalSpinner.mostrar();
            var endpoint = `${ModalPublicidadReporteError.endpoint_base}/reportar-error`;
            var data = {
                url_request: location.href
            }

            $.ajax({
                url: endpoint,
                data: data,
                type: 'POST',
                success: (data) => {
                    if (data.success) {
                        console.log('reporte satisfactorio');
                        ModalPublicidadReporteError.setHtmlField(`${ModalPublicidadReporteError.idModalLabel}`,"¡Gracias por reportar este error <i class='far fa-smile-beam fa-fw ' ></i> !")
                    } else {
                        alert("Ha habido un error para reportar el error");
                        ModalPublicidadReporteError.close();
                    }
                    GlobalSpinner.ocultar();
                },
                error: (xhr, ajaxOptions, thrownError) => {
                    console.log(xhr.status);
                    console.log(thrownError);
                    alert('Ha habido un error para reportar el error');
                    ModalPublicidadReporteError.close();
                    GlobalSpinner.ocultar();
                }
            })
        },
        enviarDatos: (e) => {
            GlobalSpinner.mostrar();
            e.preventDefault();
            var endpoint = `${ModalPublicidadReporteError.endpoint_base}/reclamar-regalo-reporte-error`;

            ModalPublicidadReporteError.setField('url_request', location.href);
            var data = ModalPublicidadReporteError.getData();

            $.ajax({
                url: endpoint,
                data: data,
                type: 'POST',
                success: (data) => {
                    if (data.success) {
                        alert('Sus datos han sido enviados con éxito');
                    } else {
                        alert('Ha habido un error al intentar enviar sus datos, intente nuevamente');
                    }
                    GlobalSpinner.ocultar();
                },
                error: (xhr, ajaxOptions, thrownError) => {
                    console.log(xhr.status);
                    console.log(thrownError);
                    alert('Ha habido un error para enviar tus datos, envía nuevamente');
                    ModalPublicidadReporteError.close();
                    GlobalSpinner.ocultar();
                }

            })
        },
        setHtmlField: (campo_id, valor) => {
            $(`#${ModalPublicidadReporteError.idModal} #${campo_id}`).html(valor);
        },
        setField: (campo_name, valor) => {
            $(`#${ModalPublicidadReporteError.idModal} [name=${campo_name}]`).val(valor);
        },
        getData: () => {
            var data = $(`#${ModalPublicidadReporteError.idModal} #${ModalPublicidadReporteError.idForm}`).serializeObject();
            return data
        }



    }
</script>