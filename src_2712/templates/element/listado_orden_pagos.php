<div class="modal fade"
     id="listadoOrdenPagosModal"
     tabindex="-1" role="dialog"
     aria-labelledby="listadoOrdenPagosModalLabel"
     aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="listadoOrdenPagosModalLabel">Historial de Pagos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="form-group row">
          <div class="col">
            Total: <b id="listado_total"></b>
          </div>
          <div class="col">
            Pagado: <b id="listado_pagado"></b>
          </div>
          <div class="col">
            Por Pagar: <b id="listado_por_pagar"></b>
          </div>
        </div>

        <table cellspacing="0" class="table table-hover">
          <thead>
          <tr>
            <th>ID</th>
            <th>Descripción</th>
            <th>Monto</th>
            <th>Fecha Pago</th>
          </tr>
          </thead>
          <tbody id="listadoOrdenPagos">

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  var ListadoOrdenPagos = {
    Nuevo: function (orden_id) {
      $('#listadoOrdenPagosModal').modal('show');
      console.log('Nuevo')
      this.Limpiar();
      this.PoblarTabla(orden_id);
      this.poblarInfo(orden_id);
    },
    Limpiar: function () {
      $('#listadoOrdenPagos').html('');
      $('#listado_total').html('');
      $('#listado_pagado').html('');
      $('#listado_por_pagar').html('');
    },

    poblarInfo: function (orden_id) {
      var endpoint = `${base}ordenes/get-info-pagos/${orden_id}`;
      $.ajax({
        url: endpoint,
        data: '',
        type: 'GET',
        success: function (r) {
          if (r.success) {
            $('#listado_total').html(parseFloat(r.data.total).toFixed(2));
            $('#listado_pagado').html(parseFloat(r.data.pagado).toFixed(2));
            $('#listado_por_pagar').html(parseFloat(r.data.por_pagar).toFixed(2));
          }
        }
      });
    },

    PoblarTabla: function (orden_id) {
      var endpoint = `${base}orden-pagos/get-all/${orden_id}`;
      $.ajax({
        url: endpoint,
        data: '',
        type: 'GET',
        success: function (r) {
          console.log("Succes: " + r)
          if (r.success) {
            var data = ``;
            $(r.data).each(function (i, o) {
              data += `
                <tr>
                  <th>${o.id}</th>
                  <th>${o.descripcion}</th>
                  <th>${parseFloat(o.monto).toFixed(2)}</th>
                  <th>${o.fecha_pago.substring(0, 10)}</th>
                </tr>
              `;
            });

            $('#listadoOrdenPagos').html(data);
          }
        }
      });
    }
  };
</script>

