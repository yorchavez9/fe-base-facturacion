<div class="modal fade" id="modalItemStockAlmacenes" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" > <i class="fa fa-boxes fa-fw"></i> Stock por Almacen </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                <div id="stocks_almacenes"></div>
            </div>
        </div>
    </div>
</div>


<script>
    var ModalItemsStockAlmacenes ={
        IdModal : 'modalItemStockAlmacenes',
        ItemId : '',
        Abrir: function(item_id){
            this.ItemId = item_id;
            ModalItemsStockAlmacenes.recuperarStocks();
            $(`#${this.IdModal}`).modal('show');
        },
        recuperarStocks: function(){
            var id = ModalItemsStockAlmacenes.ItemId;
            var endpoint = `${base}stock/get-all-stock`;
            console.log(endpoint);
            $.ajax({
                url:endpoint,
                data:{item_id:id},
                type:'GET',
                success: function(data){
                    console.log(data);
                    if(data.success){
                        var html='';
                        data.data.forEach(stock =>
                            html+= `<i class="fa fa-store-alt fa-fw" ></i> <b>${stock.almacen.nombre} : </b> ${stock.stock} <br> `
                        )
                        $(`#${ModalItemsStockAlmacenes.IdModal} #stocks_almacenes`).html(html);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })

        }



    }

</script>
