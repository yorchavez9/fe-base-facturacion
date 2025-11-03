<div class="modal fade" id="modalReporteKardex" tabindex="-1"
     role="dialog" aria-labelledby="modalReporteKardexLabel" aria-hidden="true"
     data-backdrop="static" data-keyboard="false"
>
    <div class="modal-dialog " role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="modalReporteKardexLabel"> <i class="fa fa-mail-bulk fa-fw"></i> Exportar Kardex</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="modalReporteKardex.Limpiar()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-3">
                <h6 id="titulo1" class="font-weight-bold"></h6>

                <form id="form_kardex" onsubmit="modalReporteKardex.Enviar(event)" method="post" target="_blank" action="<?= $this->Url->build(['action' => 'exportarKardex', $item->id]) ?>">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha inicio</label>
                            <input type="text" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="fecha_fin">Fecha fin</label>
                            <input type="text" id="fecha_fin" name="fecha_fin" required>
                        </div>
                    </div>
                </div>
                <button class="btn btn-danger" name="pdf" value="1">Exportar PDF</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var modalReporteKardex = {
        ID : 0,
        Init        : function (id){
            this.ID = id;
            this.getDatos();
        },
        Abrir       : function(){
            $('#modalReporteKardex').modal('show');
        },
        getDatos    : function () {
            $.ajax({
                url: base + "items/get-one/" + this.ID,
                type: 'GET',
                success: function (r) {
                    console.log(r);
                    if (r.success) {
                        $("#titulo1").html('<i class="fas fa-tag"></i> ' + r.data.nombre);
                        $("#export_excel").attr('href', base + 'items/exportar-kardex/' + r.data.id );
                        $("#export_excel").attr('disabled', false);
                    }
                }
            });
        },
        Limpiar     : function(){

        },
        Enviar      : function(){

        },

    };
</script>

<style>
    .click {
        cursor: pointer;
    }
</style>

