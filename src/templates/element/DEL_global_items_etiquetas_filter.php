<div class="modal fade"
     id="GlobalItemsEtiquetasModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group input-group-sm mb-3">
                                <input class="form-control" placeholder="Escribir Etiqueta" id="filtroEtiquetas"><br>
                                <!--<div class="input-group-append">
                                    <button class="btn btn-outline-primary" onclick="FormDirectorioCategoria.Nuevo()" type="button">
                                        <i class="fas fa-plus fa-fw"></i>
                                    </button>
                                </div>-->
                            </div>
                            <div id="listaEtiquetas" style="overflow: auto" >

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-sm btn-primary float-right ">
                                <i class="fa fa-save fa-fw" ></i> Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    var GlobalItemsEtiquetasFilter ={
        IdModal : 'GlobalItemsEtiquetasModal',
        EtiquetasList   :   [],
        Init    :   function(){
            (function () {
                jQuery.expr[':'].containsNC = function (elem, index, match) {
                    return (elem.textContent || elem.innerText || jQuery(elem).text() || '').toLowerCase().indexOf((match[3] || '').toLowerCase()) >= 0;
                }
            }(jQuery));


            $(`#${GlobalItemsEtiquetasFilter.IdModal} #filtroEtiquetas`).on("keyup", function () {
                var ttt = $(`#${GlobalItemsEtiquetasFilter.IdModal} #filtroEtiquetas`).val();
                if (ttt == '') {
                    $(".filtrar").css({"display": "block"})
                } else {
                    $(".filtrar").css({"display": "none"})
                    $(`.filtrar:containsNC(${ttt})`).css("display", "block");
                    // $(`.filtrar:containsNC(${ttt})`).css("display", "block");
                }
            })

            GlobalItemsEtiquetasFilter.CargarEtiquetas();
        },
        CargarEtiquetas :   function(){
            var endpoint = `${base}etiquetas/get-all`;

            $.ajax({
                url: endpoint,
                data:   '',
                type:   'GET',
                success :   function(data){
                    if(data.success){
                        GlobalItemsEtiquetasFilter.FetchEtiquetas(data.data);
                    }else{

                    }
                },
                error   :   function(xhr,ajaxOptions,thrownError){
                    console.log(xhr.status);
                    console.log(thrownError);
                }
            })
        },
        FetchEtiquetas  :   function(data){
            var html = ``;
            GlobalItemsEtiquetasFilter.EtiquetasList = data;
            $(data).each(function(i,eti){
                html +=
                   `
                   <div class="filtrar" >
                        <label>
                            <input id="check_${eti.id}" type="checkbox" name="etiquetas_[${eti.id}]" >
                            ${eti.nombre}
                        </label>
                    </div>
                    `;
            })

            $(`#${GlobalItemsEtiquetasFilter.IdModal} #listaEtiquetas`).html(html);
        },
        Abrir   :   function(){
            GlobalItemsEtiquetasFilter.Limpiar();
            GlobalItemsEtiquetasFilter.Mostrar();


        },
        AbriByItem  :   function(){

        },
        FetchEtiquetasByItem  :   function(){

        },
        Limpiar :   function(){
            $(GlobalItemsEtiquetasFilter.EtiquetasList).each(function(i,etiq){
                $(`#check_${etiq.id}`).prop('checked',false);
            });
        },
        Mostrar   :   function(){
            $(`#${GlobalItemsEtiquetasFilter.IdModal}`).modal('show');
        },
        Ocultar :   function(){
            $(`#${GlobalItemsEtiquetasFilter.IdModal}`).modal('hide');
        }

    }
</script>
