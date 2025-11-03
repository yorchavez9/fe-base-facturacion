<div class="modal fade" id="modalPagosVentas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" onsubmit="ModalPagosVentas.registrar(event)" id="formPagosVentas">
                    <div class="form-row">
                        <input name="id" id="id" type="hidden" value="" />
                        <div class="col-6">
                            <input type="number" step="0.01" class="form-control form-control-sm" name="monto" placeholder="Monto" required>
                        </div>
                        <div class="col-6">
                            <?=
                                $this->Form->control("medio_pago",
                                [
                                    'label' =>  false,
                                    'class' =>  'form-control form-control-sm',
                                    'placeholder'   =>  'Forma de Pago',
                                    'options'       =>  $metodos_pago
                                ])
                            ?>
                        </div>
                        <div class="col-12 mt-2">
                            <textarea class="form-control form-control-sm" placeholder="Escriba alguna nota" name="nota" id="" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <button class="btn btn-sm btn-outline-primary float-right">
                                <i class="fa fa-save fa-fw"></i> Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    var ModalPagosVentas = {
        IdModal: `modalPagosVentas`,
        venta_id: 0,
        deuda_pendiente : 0,
        abrir: function(venta_id, deuda = 0 , pago_id = '') {
            ModalPagosVentas.venta_id = venta_id;
            ModalPagosVentas.deuda_pendiente = deuda;
            $(`#${ModalPagosVentas.IdModal} input[name=id]`).val(pago_id);
            $(`#${ModalPagosVentas.IdModal} input[name=monto]`).val(deuda);
            $(`#${ModalPagosVentas.IdModal}`).modal('show');

            $(`#${ModalPagosVentas.IdModal} input[name=monto]`).on('input', function() {
                var monto_pagar = $(this).val(),
                    monto_total = parseFloat(ModalPagosVentas.deuda_pendiente);

                if (monto_pagar != '') {
                    monto_pagar = parseFloat(monto_pagar);
                }
                if (monto_pagar > monto_total) {
                    $(this).val(monto_total.toFixed(2));
                }
            });

        },
        limpiar: function() {
            $(`#${this.ModalPagosVentas} input[name=monto]`).val('');
            $(`#${this.ModalPagosVentas} select[name=medio_pago]`).val('EFECTIVO');
            $(`#${this.ModalPagosVentas} textarea[name=monto]`).val('');
        },
        registrar: function(e) {
            e.preventDefault();
            var datos = ModalPagosVentas.getData();
            var endpoint = `${base}venta-pagos/save/${ModalPagosVentas.venta_id}`;

            GlobalSpinner.mostrar();
            GlobalSpinner.setText('Registrando pago');
            $(`#${ModalPagosVentas.IdModal}`).modal('hide');
            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success) {
                        alert('Registro éxitoso');
                    } else {
                        alert(data.message);
                    }
                    GlobalSpinner.ocultar();
                    location.reload();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(thrownError);
                    alert('Ha habido un error en el registro del pago');
                    GlobalSpinner.ocultar();
                }
            })
        },
        getData: function() {
            var datos = document.getElementById('formPagosVentas');
            var form = new FormData(datos);
            return form;
        }

    }
</script>