<div class="modal fade"
    id="formUsuariosModal"
    tabindex="-1" role="dialog"
    aria-labelledby="formUsuariosLabel"
    aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formUsuariosLabel">Registro de Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUsuarios" onsubmit="FormUsuarios.Guardar(event)">
                    <div class="row mb-2">
                        <div class="col">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 140px;">
                                        <i class="fas fa-store fa-fw"></i> Establecimiento
                                    </span>
                                </div>
                                <select name="establecimiento_id" class="form-control form-control-sm"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 100px;">
                                        <i class="fas fa-user fa-fw"></i> Cargo
                                    </span>
                                </div>
                                <input name="rol" class="form-control form-control-sm" placeholder="Cargo" />
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 100px;">
                                        <i class="fas fa-user fa-fw"></i> Nombre
                                    </span>
                                </div>
                                <input type="text" name="nombre" class="form-control form-control-sm" autocomplete="off" placeholder="Nombre a mostrar">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 100px;">
                                        <i class="fas fa-mobile fa-fw"></i> Whatsapp
                                    </span>
                                </div>
                                <input type="text" name="whatsapp" class="form-control form-control-sm" autocomplete="off" placeholder="Whatsapp ejm. 51963852741">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="correo" class="form-control form-control-sm" autocomplete="off" placeholder="Correo Electrónico">
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-mobile fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="celular" class="form-control form-control-sm" autocomplete="off" placeholder="Celular">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="usuario" class="form-control form-control-sm" autocomplete="off" placeholder="Usuario">
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-key fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="clave" class="form-control form-control-sm" autocomplete="off" placeholder="Contraseña">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="form-check check-group">
                                <input class="form-check-input" type="checkbox" value="1" name="check_correo" id="checkCorreo" autocomplete="off">
                                <label class="form-check-label" for="checkCorreo">
                                    Enviar Credenciales de Acceso al correo del usuario
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col mb-2">
                            <button type="submit" class="btn btn-sm btn-primary" id="btn_save"><i class="fa fa-save fa-fw"></i> Guardar</button>
                        </div>
                    </div>

                    <input type="hidden" name="id">
                </form>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Html->scriptBlock(sprintf(
    'var csrfToken = %s;',
    json_encode($this->request->getAttribute('csrfToken'))
)); ?>
<script>
    var FormUsuarios = {
        IdFormulario: 'formUsuarios',
        IdModal: 'formUsuariosModal',
        Nuevo: function() {
            $(`#${this.IdModal}`).modal('show');
            this.Limpiar();
            this.getEstablecimientos(0);
        },
        Limpiar: function() {
            this.setCampo('id', '');
            this.setCampo('nombre', '');
            this.setCampo('usuario', '');
            this.setCampo('correo', '');
            this.setCampo('celular', '');
            this.setCampo('clave', '');
            this.setCampo('rol', '');
        },
        setCampo: function(field, value) {
            $(`#${this.IdFormulario} [name=${field}]`).val(value);
        },
        getCampo: function(field) {
            return $(`#${this.IdFormulario} [name=${field}]`).val();
        },
        getDatos: function() {
            var formElement = document.getElementById(this.IdFormulario);
            var formData = new FormData(formElement);
            return formData;
        },
        setDatos: function(d) {
            this.setCampo('id', d.id);
            this.setCampo('nombre', d.nombre);
            this.setCampo('usuario', d.usuario);
            this.setCampo('correo', d.correo);
            this.setCampo('celular', d.celular);
            this.setCampo('clave', '');
            this.setCampo('rol', d.rol);
            this.getEstablecimientos(d.establecimiento_id);
        },
        Cerrar: function() {
            $(`#${this.IdModal}`).modal('hide');
            this.Limpiar();
        },
        Guardar: function(e) {
            e.preventDefault();
            var endpoint = `${base}usuarios/ajax-save`;
            var datos = this.getDatos();
            $(`#${this.IdModal} #btn_save`).prop('disabled', true);
            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                processData: false,
                cache: false,
                contentType: false,
                success: function(r) {
                    if (!r.success) {
                        alert(r.message);
                    } else {
                        FormUsuarios.Cerrar();
                        location.reload();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                },
                complete: function() {
                    $(`#${this.IdModal} #btn_save`).prop('disabled', false);
                }
            });
        },
        Editar: function(id) {
            this.Nuevo();
            this.fetchDatos(id);
        },
        fetchDatos: function(id) {
            var endpoint = `${base}usuarios/get-one/${id}`;
            $.ajax({
                url: endpoint,
                data: {},
                type: 'GET',
                success: function(r) {
                    if (r.success) {
                        FormUsuarios.setDatos(r.data);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }

            });
        },
        getEstablecimientos: function (id) {
            var endpoint = `${API_FE}/establecimientos`;
            $.ajax({
                url: endpoint,
                type: "GET",
                success: function (r) {
                    if (r.success) {
                        console.log(r);
                        var options = `<option value="0">- Seleccione Almacen -</option>`;
                        $(r.data).each(function (i, o) {
                            if (o.id == id) {
                                options += `<option value="${o.id}" selected>${o.nombre}</option>`;
                            } else {
                                options += `<option value="${o.id}">${o.nombre}</option>`;
                            }
                        });
                        $("[name=establecimiento_id]").html(options);
                    }
                },
            });
        },

    };
</script>