<div class="modal fade"
     id="formEstablecimientoSerieModal"
     tabindex="-1" role="dialog"
     aria-labelledby="formEstablecimientoSerieLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formEstablecimientoSerieLabel">Lista de establecimientos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <h5> Serie: <label id="label_serie"></label> </h5>
                </div>
                <div>
                    <ul id="lista_establecimientos">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var FormEstablecimientoSerie = {
        IdFormulario: 'formEstablecimientoSerie',
        IdModal: 'formEstablecimientoSerieModal',

        Init: function (serie_id = 0 , serie_label = '') {
            $(`#${this.IdModal}`).modal('show');
            $("#label_serie").html(serie_label);
            this.Limpiar();
            this.poblarAlmacenes(serie_id);
        },

        Limpiar: function () {
            $("#label_serie").html();
            $("#lista_establecimientos").html();
        },

        Cerrar : function () {
            $(`#${this.IdModal}`).modal('hide');
        },

        poblarAlmacenes: function (sel) {
            var endpoint = `${base}sunat-fe-emisor-serie-almacen/get-establecimientos-by-serie/${sel}`;
            $.ajax({
                url: endpoint,
                type: 'GET',
                data: '',
                success: function (r) {
                    var html = ``;
                    $(r.data).each(function (i, e) {
                        html += `<li> ${e.nombre_almacen} </li>`
                    });
                    $("#lista_establecimientos").html(html);
                }
            });
        },

    };
</script>


