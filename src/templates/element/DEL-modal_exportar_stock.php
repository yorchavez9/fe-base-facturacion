<div class="modal fade" id="modalReporteStock" tabindex="-1"
     role="dialog" aria-labelledby="modalReporteStockLabel" aria-hidden="true"
     data-backdrop="static" data-keyboard="false"
>
    <div class="modal-dialog " role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="modalReporteStockLabel"> <i class="fa fa-mail-bulk fa-fw"></i> Exportar Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="modalReporteStock.Limpiar()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-3">
                <h6 id="titulo1" class="font-weight-bold"></h6>
                <h6 id="titulo2"></h6>

                <div class="d-flex flex-row pb-4 justify-content-center " style="width: 100% !important; margin:auto"   >
                <?php /*
                    <div class="text-center ">
                        <i class="fas fa-file-pdf" style="font-size:5em;color:#7D3C98"></i><br>
                        <div class="dropdown" >
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                Descargar PDF
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="menu_pdf" style="max-height:350px;overflow:auto">
                            </div>
                        </div>
                    </div>
                    */?>
                    <a class="text-center" href="#" target="_blank" id="export_excel" disabled>
                        <i class="fas fa-file-excel" style="font-size:5em;color:#28B463"></i><br>
                        Descargar Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var modalReporteStock = {
        ID : 0,
        Init : function (id){
            this.ID = id;
            this.getDatos();
        },

        Abrir: function(){
            $('#modalReporteStock').modal('show');
            console.log(this.ID);
        },


        getDatos: function () {
            $.ajax({
                url: base + "almacenes/get-almacen-stock/" + this.ID,
                type: 'GET',
                success: function (r) {
                    console.log(r);
                    if (r.success) {
                        $("#titulo1").html('<i class="fas fa-building"></i> ' + r.data.nombre);
                        $("#titulo2").html('<i class="fas fa-map-marker-alt"></i> ' + r.data.direccion);
                        // $("#export_pdf").attr('href', base + 'almacenes/exportar-stock-pdf/' + r.data.id );
                        var str = '';
                        for (let index = 1; index <= r.cantidad; index++) {   
                            str += `<a class='dropdown-item-text' href='${base}almacenes/exportar-stock-pdf/${r.data.id}?page=${index}' target='_blank'> Exportar Página ${index}</a>`;
                        }
                        $("#menu_pdf").html(str);
                        $("#export_excel").attr('href', base + 'almacenes/exportar-stock-excel/' + r.data.id );
                        $("#export_pdf").attr('disabled', false);
                        $("#export_excel").attr('disabled', false);
                    }
                }
            });
        },



        Limpiar     : function(){
            $("#export_pdf").attr('disabled', true);
            $("#export_excel").attr('disabled', true);
        },

    };
</script>

<style>
    .click {
        cursor: pointer;
    }
</style>

