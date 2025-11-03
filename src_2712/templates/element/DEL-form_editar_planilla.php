<div class="modal fade"
     id="editarPlanillaModal"
     tabindex="-1" role="dialog"
     aria-labelledby="editarPlanillaLabel"
     aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarPlanillaLabel">Editar Planilla</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form id="formEditarPlanilla" onsubmit="FormEditarPlanilla.Guardar(event)">
          <table class="table table-responsive table-hover" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Nombres</th>
              <th scope="col">Apellidos</th>
              <th scope="col">DNI</th>
              <th scope="col">Cargo en el Proyecto</th>
              <th scope="col">Sueldo Bruto</th>
              <th scope="col">Cantidad Pagos</th>

            </tr>
            </thead>
            <tbody id="planillaListar">

            </tbody>
          </table>
          </table>

          <input type="hidden" name="cant" value="0">
          <input type="hidden" name="proyecto_id" value="<?= $proyecto->id ?>">
          <input type="hidden" name="centro_costo_id" value="<?= $centro_costo_id ?>">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

      </div>
    </div>
  </div>
</div>

<script>
  var FormEditarPlanilla = {
    guardar_callback: null,
    setEventoGuardar: function (fn) {
      this.guardar_callback = fn;
    },

    Limpiar: function () {
      $('#planillaListar').html('');
    },

    Nuevo: function (proyecto_id) {
      $('#editarPlanillaModal').modal('show');
      this.Limpiar();
      this.ListarPlanillas(proyecto_id);
    },

    Cerrar: function () {
      $('#editarPlanillaModal').modal('hide');
      this.Limpiar();
    },

    Guardar: function (e) {
      e.preventDefault();
      var datos = this.getDatos();
      var endpoint = `${base}planillas/editar-planilla`;


      $.ajax({
        url: endpoint,
        data: datos,
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        success: function (r) {
          console.log(r);
          if (r.success) {
            FormEditarPlanilla.guardar_callback();
          }
        }
      });



    },

    getDatos: function () {
      var formElement = document.getElementById("formEditarPlanilla");
      var formData = new FormData(formElement);

      return formData;
    },

    ListarPlanillas: function (proyecto_id) {
      var endpoint = `${base}planillas/get-all`;

      $.ajax({
        url: endpoint,
        data: '',
        type: 'GET',
        success: function (r) {
          if (r.success) {
            $('#planillaListar').html('');

            $(r.data).each(function (i, o) {
              var fila = `<tr>
                  <td><input type="checkbox" id="ch_${i}" name="check[${o.id}]" value="${o.id}"></td>
                  <td>${o.nombres}</td>
                  <td>${o.apellidos}</td>
                  <td>${o.dni}</td>
                  <td><select name="roles[${o.id}]" id="roles_${i}" class="form-control"></select></td>
                  <td><input type="number" class="form-control" step="any" name="sueldo_bruto[${o.id}]" value="0.00" id="ep_sb_${i}"></td>
                  <td><input type="number" class="form-control" step="any" name="cantidad_pagos[${o.id}]" value="0" id="ep_count_${i}"></td>
                </tr>`;

              $('#formEditarPlanilla [name=cant]').val(i+1);

              $('#planillaListar').append(fila);

              var ep = `${base}usuarios/get-roles`;

              $.ajax({
                url: ep,
                data: '',
                type: 'GET',
                success: function (arr) {
                  var htmlSelect = "";
                  $(arr).each(function (it, json) {
                    htmlSelect += `<option value="${json.prefijo}">${json.rol}</option>`;
                  });

                  $(`#roles_${i}`).html(htmlSelect);

                  var epPP = `${base}planillas/get-proyecto-planilla/${proyecto_id}/${o.id}`;
                  $.ajax({
                    url:epPP,
                    data: '',
                    type: 'GET',
                    success: function (rpp) {
                      if (rpp.success && rpp.data != null) {
                        var checkbox = document.getElementById('ch_'+i);
                        checkbox.checked = true;
                        $('#ep_sb_'+i).val(rpp.data.salario_proyecto == null ? "0.00" :  parseFloat(rpp.data.salario_proyecto).toFixed(2));
                        $('#ep_count_'+i).val(rpp.data.cantidad_pagos == null ? "0" :  rpp.data.cantidad_pagos);
                        $('#roles_'+i).val(rpp.data.cargo_proyecto);
                      }
                    }
                  });
                }
              });

            });
          }
        }
      });
    }



  };
</script>
