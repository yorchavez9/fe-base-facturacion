<div class="modal fade" id="modalSunatCdr" tabindex="-1"
     role="dialog" aria-labelledby="modalSunatCdrLabel" aria-hidden="true"
     data-backdrop="static" data-keyboard="false"
>
    <div class="modal-dialog " role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="modalSunatCdrLabel"> <i class="fa fa-mail-bulk fa-fw"></i> Detalles del CDR </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-3">
                <h6 id="titulo1" class="font-weight-bold"> </h6>
                <h6 id="titulo2"></h6>
                <h6 id="titulo3"></h6>
                <table class="table table-sm">
                    <tr>
                        <td colspan="2">
                            <label class="font-weight-bold" for="">CDR Notas:</label>
                            <p id="notes"></p>
                        </td>
                    </tr>
                    <tr>
                    <td colspan="2">
                            <label class="font-weight-bold" for="">CDR Descripcion:</label>
                            <p id="description"></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="font-weight-bold" for="">CDR Codigo:</label>
                            <p id="code"></p>
                        </td>
                        <td>
                            <label class="font-weight-bold" for="">Codigo de Error:</label>
                            <p id="err_code"></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label class="font-weight-bold" for="">Mensaje del Error:</label>
                            <p id="err_message"></p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var ModalSunatCdr = {
        IdModal : 'modalSunatCdr'  ,
        Init : function (){
        },

        Abrir: function(factura_id){
            ModalSunatCdr.Limpiar();
            $('#modalSunatCdr').modal('show');
            ModalSunatCdr.recuperarInfoDocumento(factura_id);

        },
        recuperarInfoDocumento :  function(factura_id) {
            endpoint = `${base}sunat-fe-facturas/get-one/${factura_id}`;
            $.ajax({
                url: endpoint,
                data: '',
                type: 'GET',
                success: function (r) {
                    if (r.success) {
                        console.log(r)
                        $(`#${ModalSunatCdr.IdModal} #titulo1`).html(`<i class="fa fa-building fa-fw"></i>` + r.data.cliente_razon_social);
                        $(`#${ModalSunatCdr.IdModal} #titulo2`).html(`<i class="fa fa-user-tie fa-fw"></i>` + r.data.cliente_num_doc);
                        $(`#${ModalSunatCdr.IdModal} #titulo3`).html(`<i class="fa fa-file-upload fa-fw"></i>`+r.data.serie + "-"+r.data.correlativo);
                        
                        $(`#${ModalSunatCdr.IdModal} #notes`).html( r.data.sunat_cdr_notes);
                        $(`#${ModalSunatCdr.IdModal} #description`).html( r.data.sunat_cdr_description);
                        $(`#${ModalSunatCdr.IdModal} #code`).html( r.data.sunat_cdr_code );
                        $(`#${ModalSunatCdr.IdModal} #err_code`).html( r.data.sunat_err_code );
                        $(`#${ModalSunatCdr.IdModal} #err_message`).html( r.data.sunat_err_message );

                    }else{
                        $(`#${ModalSunatCdr.IdModal} #titulo1`).html(`<i class="fas fa-frown fa-fw"></i> Ha habido un error para extraer los datos`);
                    }

                }
            });

        },
        Cerrar: function(){
            $('#formEnviarCorreoModal').modal('hide');
        },
        Limpiar     : function(){
            $(`#${ModalSunatCdr.IdModal} #titulo1`).html(``);
            $(`#${ModalSunatCdr.IdModal} #titulo2`).html(``);
            $(`#${ModalSunatCdr.IdModal} #titulo3`).html(``);
            $(`#${ModalSunatCdr.IdModal} #notes`).html(``);
            $(`#${ModalSunatCdr.IdModal} #description`).html(``);
            $(`#${ModalSunatCdr.IdModal} #code`).html(``);
            $(`#${ModalSunatCdr.IdModal} #err_code`).html(``);
            $(`#${ModalSunatCdr.IdModal} #err_message`).html(``);
        },
    }
</script>