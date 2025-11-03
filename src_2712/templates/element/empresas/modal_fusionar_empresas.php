<div class="modal fade" id="modalFusionarEmpresas" tab-index="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentacion_title">
                    <i class="fa fa-fw fa-window-restore"></i> Selecciona la empresa de destino
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formFusionarCuentas" class="needs-validation" novalidate>
                    <div class="form-row" id="cuentas_inner_form">

                    </div>
                    <div class="row">
                        <div class="col-12 my-1" id="div_mensaje_exito">
                            <div class="alert alert-success" role="alert">
                                Datos fusionados con éxito!
                            </div>
                        </div>
                        <div class="col-12 my-1" id="div_mensaje_error">
                            <div class="alert alert-danger" role="alert">
                                Ha habido un error!
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2 text-right">
                            <button class="btn btn-sm btn-outline-primary" type="submit">
                                <i class="fas fa-exchange-alt fa-fw"></i> Fusionar
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" data-dismiss="modal">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>



<script>
    var ModalFusionarEmpresas = {
        idModal: "modalFusionarEmpresas",
        idForm: "formFusionarCuentas",
        idDivCuentasInnerForm: "cuentas_inner_form",
        empresasArray: [],
        tipo: "CUENTAS",
        init: (tipo) => {
            ModalFusionarEmpresas.tipo = tipo;
        },
        open: (items_array) => {
            if (['CUENTAS', 'DIRECTORIO'].indexOf(ModalFusionarEmpresas.tipo) < 0) {
                alert("Esta opción no es válida para esta vista");
                return;
            }
            if (items_array.length != 2) {
                alert("No se ha especificado la cantidad correcta de items");
                return;
            }

            ModalFusionarEmpresas.empresasArray = items_array;
            ModalFusionarEmpresas.cleanForm();
            ModalFusionarEmpresas.setEmpresas();
            ModalFusionarEmpresas.openModal();
        },
        openModal: () => {
            $(`#${ModalFusionarEmpresas.idModal}`).modal('show');
        },
        setEmpresas: () => {
            html = "";

            ModalFusionarEmpresas.empresasArray.forEach((empresa, index) => {
                html += `
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="radio"  name="empresa_destino" value="${empresa.id}" >
                            <label class="form-check-label" >
                                ${empresa.razon_social}
                            </label>
                            ${ index > 0 ? `
                                <div class="invalid-feedback">
                                    Debes especificar por lo menos una de las opciones
                                </div>
                                `  : ""}
                        </div>
                    </div>
                `;

            })
            $(`#${ModalFusionarEmpresas.idModal} #${ModalFusionarEmpresas.idDivCuentasInnerForm}`).html(html);

        },
        fusionarCuentas: () => {
            if (!ModalFusionarEmpresas.formIsValid()) {
                return;
            }
            const data = ModalFusionarEmpresas.generateForm();
            // console.log(data);
            // return;
            GlobalSpinner.mostrar();

            const controlador = ModalFusionarEmpresas.tipo == 'CUENTAS' ? 'cuentas' : 'directorio';
            const endpoint = `${base}${controlador}/merge-contacts`

            $.ajax({
                url: endpoint,
                data: data,
                type: "POST",
                success: (data) => {
                    console.log(data);
                    if (data.success) {
                        ModalFusionarEmpresas.showDivExito();
                        window.location.reload();
                    }
                },
                error: (xhr, ajaxOptions, thrownError) => {
                    ModalFusionarEmpresas.showDivError();
                },
                complete: () => {
                    GlobalSpinner.ocultar();
                }
            })
        },
        formIsValid: () => {
            const radioSelected = $(`#${ModalFusionarEmpresas.idForm} [name=empresa_destino]:checked`);

            if (radioSelected.length == 0) {
                ModalFusionarEmpresas.setInvalidField("empresa_destino");
                return false;
            } else {
                ModalFusionarEmpresas.setValidField("empresa_destino");
            }

            return true;

        },
        generateForm: () => {
            const empresa_destino_id = parseInt($(`#${ModalFusionarEmpresas.idForm} [name=empresa_destino]:checked`).val());
            var empresa_origen_id = 0;

            const empresa_origen = ModalFusionarEmpresas.empresasArray.find(e => e.id != empresa_destino_id);
            if ([undefined, null].indexOf(empresa_origen) < 0) {
                empresa_origen_id = empresa_origen.id;
            }

            const jsonData = {
                id_origen: empresa_origen_id,
                id_destino: empresa_destino_id
            }

            return jsonData;
        },
        closeModal: () => {
            $(`#${ModalFusionarEmpresas.idModal}`).modal('hide');
        },
        cleanForm: () => {
            $(`#${ModalFusionarEmpresas.idModal} #div_mensaje_exito`).hide();
            $(`#${ModalFusionarEmpresas.idModal} #div_mensaje_error`).hide();

        },
        getFieldValue: (field_name) => {
            return $(`#${ModalFusionarEmpresas.idModal} [name=${field_name}]`).val();
        },
        setFieldValue: (field_name, new_value) => {
            return $(`#${ModalFusionarEmpresas.idModal} [name=${field_name}]`).val(new_value);
        },
        setValidField: (field_name) => {
            $(`[name=${field_name}]`).removeClass('is-invalid');
            $(`[name=${field_name}]`).addClass('is-valid');

        },
        setInvalidField: (field_name) => {
            $(`[name=${field_name}]`).removeClass('is-valid');
            $(`[name=${field_name}]`).addClass('is-invalid');
        },
        cleanValidations: (field_name) => {
            $(`[name=${field_name}]`).removeClass('is-valid');
            $(`[name=${field_name}]`).removeClass('is-invalid');
        },
        showDivExito: () => {
            $(`#${ModalFusionarEmpresas.idModal} #div_mensaje_exito`).show();
            $(`#${ModalFusionarEmpresas.idModal} #div_mensaje_error`).hide();
        },
        showDivError: () => {
            $(`#${ModalFusionarEmpresas.idModal} #div_mensaje_exito`).hide();
            $(`#${ModalFusionarEmpresas.idModal} #div_mensaje_error`).show();
        }

    }

    window.addEventListener('load', () => {
        const form = document.getElementById(ModalFusionarEmpresas.idForm);
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            ModalFusionarEmpresas.fusionarCuentas();
        })
    })
</script>