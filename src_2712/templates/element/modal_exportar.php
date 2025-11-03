<div class="modal fade" id="modalExportar" tabindex="-1"
     role="dialog" aria-labelledby="modalExportarLabel" aria-hidden="true"
     data-backdrop="static" data-keyboard="false"
>
    <div class="modal-dialog " role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="modalExportarLabel"> <i class="fa fa-mail-bulk fa-fw"></i> Exportar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-3">
                <h6 id="titulo1" class="font-weight-bold"></h6>

                <form id="form_kardex" method="get" target="_blank">
                    <div class="d-flex justify-content-around">
                        <button class="btn btn-danger" type="button" onclick="modalExportar.Pdf()">
                            <i class="fas fa-file-pdf" style="font-size:5em;"></i><br>
                            Exportar PDF
                        </button>
                    </div>
                </form>
            </div>
            <div class="px-4">
                <form onsubmit="modalExportar.enviarWhatsapp(event, this)" id="form_whatsapp">
                    <label  >Envía tu Comprobante por <strong>Whatsapp</strong></label>
                    <div class="row pb-4">
                        <div class="col-md-12">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend text-center">
                                <span class="input-group-text" style="display: block; width: 48px;">
                                    <i class="fab fa-whatsapp fa-fw"></i>
                                </span>
                                </div>
                                <input type="text" class="form-control form-control-sm" name="celular" placeholder=" Cod.Pais + Cel Ejem: 51222333444" required autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" id="btnEnviarComprobantePorWhatsapp" name="btn_whatsapp" value="1" class="btn btn-sm btn-outline-primary" style="width: 120px;">
                                        <i class="fa fa-paper-plane fa-fw"></i> Whatsapp
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form onsubmit="modalExportar.enviarCorreo(event, this)" id="form_correo">
                    <label  >Envía tu Comprobante por <strong>Correo</strong></label>
                    <div class="row pb-4">
                        <div class="col-md-12">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend text-center">
                                    <span class="input-group-text " style="display: block; width: 48px;" >
                                        <i class="fas fa-envelope fa-fw"></i>
                                    </span>
                                </div>
                                <input type="email" class="form-control form-control-sm" name="correo" placeholder="Ejem : prueba@gmail.com" autocomplete="off" required>
                                <div class="input-group-append">
                                    <button type="submit" id="btnEnviarComprobantePorCorreo" name="btn_correo" value="1" class="btn btn-sm btn-outline-primary" style="width: 120px;">
                                        <i class="fa fa-paper-plane fa-fw"></i> Correo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var modalExportar = {
        ID : 0,
        endpoint: "",
        Init        : function (id, destino, tipo = ''){
            if(destino != 'parte-salidas'){
                $("#btn_carta").attr('hidden',true)
            }
            if(tipo != 'carta'){
                $("#btn_carta").attr('hidden',true)
            }
            modalExportar.ID = id;
            modalExportar.endpoint = `${base}${destino}`
            console.log(modalExportar.ID);
            $('#modalExportar').modal('show');
        },
        Pdf      : function(){
            $("#form_kardex").attr('action',  `${modalExportar.endpoint}/imprimir-pdf/${modalExportar.ID}` )
            $("#form_kardex").submit();
        },
        Limpiar : function(){
            modalExportar.ID = 0;
        },
        enviarWhatsapp(e, fdata ) {
            e.preventDefault();
            var datos = new FormData(document.getElementById("form_whatsapp"));
            var endpoint = `${modalExportar.endpoint}/enviar-adjunto/${modalExportar.ID}/1`;
            $(`#btnEnviarComprobantePorWhatsapp`).html("<i class='spinner-border spinner-border-sm'></i> Enviando ...");
            $.ajax({
                url : endpoint,
                type: 'POST',
                data: datos,
                processData: false,
                cache:false,
                contentType: false,
                success:function (data){
                    console.log(data);
                    $(`#btnEnviarComprobantePorWhatsapp`).html("<i class='fas fa-check fa-fw'></i> Enviando!");
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },

        enviarCorreo: function(e, fdata){
            e.preventDefault();
            let endpoint = `${modalExportar.endpoint}/enviar-adjunto/${modalExportar.ID}/2`;
            var datos = new FormData(document.getElementById("form_correo"));

            $("#btnEnviarComprobantePorCorreo").html(`<i class="spinner-border spinner-border-sm" ></i> Enviando ...`);
            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                success: function (r) {
                    console.log(r);
                    if (r.success) {
                        $("#btnEnviarComprobantePorCorreo").html(`<i class="fa fa-check fa-fw"></i> Enviado!`);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });

        },
    };
</script>
