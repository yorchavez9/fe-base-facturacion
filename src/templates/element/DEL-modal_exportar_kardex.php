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
                    <div class="col-12">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Almacen</label>
                            <select class="form-control" id="select_almaneces" name="almacen" required>
                                <option value="">-Seleccione-</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--<button class="btn btn-info" name="excel" value="1">Exportar Excel</button>-->
                <button class="btn btn-danger" name="pdf" value="1">Exportar PDF</button>
                </form>
                <!-- <div class="d-flex flex-row pb-4 justify-content-center " style="width: 100% !important; margin:auto"   >
                    <a class="text-center" href="#" target="_blank" id="export_excel" disabled>
                        <i class="fas fa-file-excel" style="font-size:5em;color:#28B463"></i><br>
                        Descargar Excel
                    </a>
                </div> -->
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
            $.ajax({
                url: base + "almacenes/get-all",
                type: 'GET',
                success: function (r) {
                    console.log(r);
                    if (r.success) {
                        var str = '<option value="ALL">Todos</option>';
                        r.data.forEach(e=>{
                            str += `<option value='${e.id}'>${e.nombre}</option>`;
                        })
                        $("#select_almaneces").html(str);
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

