<div class="modal fade"
     id="listadoPlanillaPagosModal"
     tabindex="-1" role="dialog"
     aria-labelledby="listadoPlanillaPagosLabel"
     aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="listadoPlanillaPagosLabel">Historial de Pagos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="form-group row">
          <div class="col">
            Total A Pagar: <b id="pp_total"></b>
          </div>
          <div class="col">
            Pagado: <b id="pp_pagado"></b>
          </div>
          <div class="col">
            Por Pagar: <b id="pp_por_pagar"></b>
          </div>
        </div>

        <table cellspacing="0" class="table table-hover">
          <thead>
          <tr>
            <th>Descripción</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Periodo</th>
          </tr>
          </thead>
          <tbody id="listadoPlanillaPagos">

          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>

<script>
  var ListadoPlanillaPagos = {
    IdModal: 'listadoPlanillaPagosModal',
    IdTabla: 'listadoPlanillaPagos',

    Nuevo: function (proyecto_id, planilla_id) {
      $('#'+this.IdModal).modal('show');
      this.Limpiar();
      this.PoblarTabla(proyecto_id, planilla_id);
      this.poblarInfo(proyecto_id, planilla_id);
    },

    Limpiar: function () {
      $('#'+this.IdTabla).html('');
      $('#pp_total').html('');
      $('#pp_pagado').html('');
      $('#pp_por_pagar').html('');
    },

    poblarInfo: function (proyecto_id, planilla_id) {

      var endpoint = `${base}proyecto-planilla/get-info-pagos/${proyecto_id}/${planilla_id}`;
      $.ajax({
        url: endpoint,
        data: '',
        type: 'GET',
        success: function (r) {
          if (r.success) {
            $('#pp_total').html(parseFloat(r.data.total).toFixed(2));
            $('#pp_pagado').html(parseFloat(r.data.pagado).toFixed(2));
            $('#pp_por_pagar').html(parseFloat(r.data.por_pagar).toFixed(2));
          }
        }
      });

    },

    PoblarTabla: function (proyecto_id, planilla_id) {

      var endpoint = `${base}planilla-pagos/get-all/${proyecto_id}/${planilla_id}`;
      $.ajax({
        url: endpoint,
        data: '',
        type: 'GET',
        success: function (r) {
          if (r.success) {
            var tabla = ``;
            $(r.data).each(function (i, o) {
              tabla += `
                <tr>
                  <td>${o.descripcion}</td>
                  <td>${parseFloat(o.monto).toFixed(2)}</td>
                  <td>${o.fecha.substring(0, 10)}</td>
                  <td>${o.periodo}</td>
                </tr>
              `;
            });
            $('#'+ListadoPlanillaPagos.IdTabla).html(tabla);
          }
        }
      });

    }
  };
</script>
