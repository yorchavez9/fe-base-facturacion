
<div class="modal fade" id="modalEditarTipoCambio" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"   >
                    <i class="fa fa-coins fa-fw"></i> Tipo de Cambio
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="col-md-12 my-2">
                        <div class="input-group input-group-sm" aria-describedby="tipoCambioDesc">
                            <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-dollar-sign fa-fw"></i>
                                    </span>
                            </div>
                            <input type="number" id="tipo_cambio_venta" name="tipo_cambio_venta" placeholder="Venta" step="0.001" class="form-control form-control-sm" >
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-primary" id="btnConsultarTipoCambio" type="button" onclick="GlobalFormTipoCambio.consultarTipoCambio()">
                                    <i id="iconConsultarTipoCambio" class="fa fa-sync fa-fw"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="GlobalFormTipoCambio.guardar()"> <i class=" fa fa-save fa-fw"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>




<script>
    /**
     * @type {{tipo_cambio_venta: number, init: TipoCambio.init, consultarTipoCambio: TipoCambio.consultarTipoCambio, guardar: TipoCambio.guardar, setCambioOnRefresh: TipoCambio.setCambioOnRefresh, actualizar_callbac_al_refrescar: boolean, stopButtonLoad: TipoCambio.stopButtonLoad, callback: TipoCambio.callback, validarTipoCambio: (function(*=): number), setButtonLoad: TipoCambio.setButtonLoad, IdModal: string, abrir: TipoCambio.abrir}}
     */
    var GlobalFormTipoCambio =
        {
            IdModal :   "modalEditarTipoCambio",
            tipo_cambio_venta :  0 ,
            actualizar_callback_al_refrescar : false,
            init    :   function()
            {
                GlobalFormTipoCambio.obtenerTC();
            },
            callback : function(){},
            abrir   :   function()
            {
                $(`#${this.IdModal}`).modal("show");

            },
            guardar     :   function()
            {
                this.tipo_cambio_venta  = this.validarTipoCambio($('#tipo_cambio_venta').val());
                $(`#${this.IdModal}`).modal("hide");
                this.callback(this.tipo_cambio_venta);
            },


            obtenerTC  : function(){
                GlobalFormTipoCambio.consultarTipoCambio(function(){
                    GlobalFormTipoCambio.callback(GlobalFormTipoCambio.tipo_cambio_venta);
                });
            },

            /**
             * Consulta al api el respectivo tipo de cambio
             */
            consultarTipoCambio :   function(fn=null) {
                var endpoint = `https://www.factura24.pe/api/consulta-doc/tipo-cambio`;

                GlobalFormTipoCambio.setButtonLoad("iconConsultarTipoCambio","btnConsultarTipoCambio","fa-sync");

                $.ajax({
                    url: endpoint,
                    data: '',
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            GlobalFormTipoCambio.tipo_cambio_venta = data.data.venta;
                            $('#tipo_cambio_venta').val(data.data.venta);
                        } else {
                            alert('Ha ocurrido un error en identificar el tipo de cambio, intente nuevamente');
                            GlobalFormTipoCambio.tipo_cambio_venta = 1;
                            $('#tipo_cambio_venta').val(1);
                        }
                        if(fn != null)
                        {
                            fn();
                        }
                        GlobalFormTipoCambio.stopButtonLoad("iconConsultarTipoCambio","btnConsultarTipoCambio","fa-sync");
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr.status);
                        console.log(thrownError);

                        alert('Ha ocurrido un error en identificar el tipo de cambio, intente nuevamente');
                        GlobalFormTipoCambio.tipo_cambio_venta = 1;
                        $('#tipo_cambio_venta').val(1);
                        if(fn != null)
                        {
                            fn();
                        }

                        GlobalFormTipoCambio.stopButtonLoad("iconConsultarTipoCambio","btnConsultarTipoCambio","fa-sync");
                    }

                });

            },
            validarTipoCambio   :   function(cambio)
            {
                    if(typeof cambio !== 'undefined' && cambio != null && cambio !== "" && parseFloat(cambio) !== 0)
                    {
                        cambio = parseFloat(cambio);
                    }
                    else
                    {
                        cambio = 1;
                    }

                    return cambio;
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
