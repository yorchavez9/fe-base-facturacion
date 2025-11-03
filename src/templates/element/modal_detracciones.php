
<div class="modal fade" id="modalDetracciones" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"   >
                    <i class="fa fa-coins fa-fw"></i> Detracciones
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="col-md-12 my-2">
                        <div class="input-group input-group-sm" aria-describedby="tipoCambioDesc">
                            <select class="form-control form-control-sm" id="tipo_detraccion">
                                <option value="000"
                                        data-porcentaje="0"
                                        data-nombre="Sin especificar"
                                >-Sin especificar-</option>
                                <?php foreach($codigo_detracciones as $cod => $desc): ?>
                                    <option value="<?= $cod ?>"
                                            data-porcentaje="<?= $porcentajes_detracciones[$cod] ?>"
                                            data-nombre="<?=$desc?>"
                                    > <?= "{$desc} - {$porcentajes_detracciones[$cod]}%" ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="ModalDetracciones.guardar()"> <i class=" fa fa-save fa-fw"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>




<script>
    /**
     * @type {{tipo_cambio_venta: number, init: TipoCambio.init, consultarTipoCambio: TipoCambio.consultarTipoCambio, guardar: TipoCambio.guardar, setCambioOnRefresh: TipoCambio.setCambioOnRefresh, actualizar_callbac_al_refrescar: boolean, stopButtonLoad: TipoCambio.stopButtonLoad, callback: TipoCambio.callback, validarTipoCambio: (function(*=): number), setButtonLoad: TipoCambio.setButtonLoad, IdModal: string, abrir: TipoCambio.abrir}}
     */
    var ModalDetracciones =
        {
            IdModal :   "modalDetracciones",
            detraccionObj  :   {},
            init    :   function()
            {
                $(`#${ModalDetracciones.IdModal} #es_detraccion`).change(function(){
                    console.log('entra al metodo');
                    if ($(`#${ModalDetracciones.IdModal} #es_detraccion`).prop('checked'))
                    {
                        console.log('entra al if');
                        $(`#${ModalDetracciones.IdModal} #tipo_detraccion`).attr('disabled',false);
                    }else{
                        console.log('entra al else');
                        $(`#${ModalDetracciones.IdModal} #tipo_detraccion`).attr('disabled',true);
                    }
                })
            },
            callback : function(){},
            abrir   :   function(detraccion_actual)
            {
                $(`#tipo_detraccion option[value=${detraccion_actual}]`).prop('selected',true);
                $(`#tipo_detraccion`).change();
                $(`#${this.IdModal}`).modal("show");
            },
            guardar     :   function()
            {

                ModalDetracciones.detraccionObj  = {
                    codigo      :   $(`#${ModalDetracciones.IdModal} #tipo_detraccion`).val(),
                    porcentaje  :   $(`#${ModalDetracciones.IdModal} #tipo_detraccion option:selected`).attr('data-porcentaje'),
                    nombre      :   $(`#${ModalDetracciones.IdModal} #tipo_detraccion option:selected`).attr('data-nombre')
                    };
                $(`#${ModalDetracciones.IdModal}`).modal("hide");
                ModalDetracciones.callback(ModalDetracciones.detraccionObj);
            },

            /**
             * Coloca el boton solicitado en estado de carga
             */
            setButtonLoad   :   function(icon_id,btn_id,class_to_replace)
            {
                $(`#${icon_id}`).removeClass(`${class_to_replace}`);
                $(`#${icon_id}`).addClass('spinner-border');
                $(`#${icon_id}`).addClass('spinner-border-sm');
                $(`#${btn_id}`).prop('disabled',true);

            },
            stopButtonLoad      :   function(icon_id,btn_id,class_to_set)
            {
                $(`#${icon_id}`).removeClass('spinner-border');
                $(`#${icon_id}`).removeClass('spinner-border-sm');
                $(`#${icon_id}`).addClass(`${class_to_set}`);
                $(`#${btn_id}`).prop('disabled',false);
            },
        }
</script>
