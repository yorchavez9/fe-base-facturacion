<div class="modal fade" id="modalEstablecimientos" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="width:850px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Establecimientos</h5>
                <button type="button" class="close" aria-label="Close" onclick="ConfiguracionEstablecimientos.cancelar()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create(null, ['class' => 'form', 'onsubmit' => "ConfiguracionEstablecimientos.guardarEleccion(event)" , 'id' => 'formEstablecimientos']);
                $this->Form->setTemplates(['inputContainer' => '{{content}}']);
            ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" >
                                                    <i class="fa fa-warehouse fa-fw" ></i>&nbsp;&nbsp;&nbsp;
                                                </span>
                                            </div>

                                            <?= $this->Form->control('nombre',
                                                ['class' => 'form-control form-control-sm letras',
                                                    'autocomplete' => 'off','required'=>true,'label' =>false,
                                                    'placeholder' => 'Nombre']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" >
                                                    <i class="fa fa-code fa-fw" ></i>&nbsp;&nbsp;
                                                    Codigo
                                                </span>
                                            </div>
                                            <?= $this->Form->control('codigo',['required',
                                                'class' => 'form-control form-control-sm',
                                                'autocomplete' => 'off', 'placeholder' =>'Codigo' ,
                                                'label' => false]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-2">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" >
                                                    <i class="fa fa-phone fa-fw" ></i>
                                                </span>
                                            </div>
                                            <?= $this->Form->control('telefono',
                                                ['class' => 'form-control form-control-sm telefono','placeholder' => 'Teléfono',
                                                    'autocomplete' => 'off','required'=>true,'label' =>false]) ?>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" >
                                                    <i class="fa fa-envelope fa-fw" ></i>
                                                </span>
                                            </div>
                                            <?= $this->Form->control('correo',
                                                ['class' => 'form-control form-control-sm email', 'placeholder' => 'Correo',
                                                    'autocomplete' => 'off','required'=>true,'type'=>'email',
                                                    'label' => false]) ?>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" >
                                                    <i class="fa fa-map-marker-alt fa-fw" ></i>
                                                </span>
                                            </div>
                                            <?= $this->Form->control('ubigeo_dpr',
                                                ['required', 'label' => 'Ubicación', 'placeholder'=> 'Ubicacion',
                                                    'type'=> 'text', 'class' => 'form-control form-control-sm',
                                                    'autocomplete' => 'off','label'=>false]) ?>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" >
                                                    <i class="fa fa-home fa-fw" ></i>
                                                </span>
                                            </div>
                                            <?= $this->Form->control('urbanizacion',
                                                ['required',
                                                    'label' => 'Urbanización',
                                                    'type'=> 'text','placeholder'=>'Urbanizacion',
                                                    'class' => 'form-control form-control-sm',
                                                    'autocomplete' => 'off','label'=>false]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" >
                                                    <i class="fa fa-marker fa-fw" ></i>&nbsp;&nbsp;&nbsp; Dirección
                                                </span>
                                            </div>
                                            <?= $this->Form->control('direccion',
                                                ['required',
                                                    'type'=> 'text',
                                                    'placeholder'=> 'Direccion',
                                                    'class' => 'form-control form-control-sm',
                                                    'autocomplete' => 'off',
                                                    'label'=>false]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <?= $this->Form->control('map_lat',['type' => 'hidden']) ?>
                                        <?= $this->Form->control('map_len',['type' => 'hidden']) ?>
                                        <?= $this->Form->control('ubigeo',['type' => 'hidden']) ?>
                                        <?= $this->Form->control('departamento',['type' => 'hidden']) ?>
                                        <?= $this->Form->control('provincia',['type' => 'hidden']) ?>
                                        <?= $this->Form->control('distrito',['type' => 'hidden']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <h6>Seleccionas las series que estaran vinculadas al Establecimiento</h6>
                            <div id="seriesList">
                                <div style="overflow-y: auto; height: 250px">
                                    <ul>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="">Continuar</button>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<style>
    .autocomplete-suggestions {color:gray; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border: 1px solid #999; background: #FFF; cursor: default; overflow: auto; -webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); -moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-no-suggestion { padding: 2px 5px;}
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: bold; color: #000; }
    .autocomplete-group { padding: 2px 5px; font-weight: bold; font-size: 16px; color: #000; display: block; border-bottom: 1px solid #000; }
</style>
<script>
var ConfiguracionEstablecimientos = {
    id: '#modalEstablecimientos',
    init : function ($nombre = '') {
        $(ConfiguracionEstablecimientos.id).modal('show');
        ConfiguracionEstablecimientos.getData();
        $(`[name=codigo]`).mask('0000');
        $(`[name=telefono]`).mask('000000000');
    },
    getData : function () {
        $.ajax({
            url: `${base}sunat-fe-emisores/ajax-get-series-emisor`,
            type: 'GET',
            success: function (r) {
                if (r.success) {
                    ConfiguracionEstablecimientos.setLista(r.data);
                } else {
                    alert(r.message)
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
            complete: function () {
            }
        });
        var ubi = [];
        $.ajax({
            url: `${base}establecimientos/api-get-ubicaciones`,
            type: 'GET',
            success: function (r) {
                if (r.success) {
                   ubi = r.data;
                } else {
                    alert(r.message)
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
            complete: function () {
                ConfiguracionEstablecimientos.setData(ubi);
            }
        });
    },
    setData : function (ubicaciones) {

        $('input[name=ubigeo_dpr]').autocomplete({
            minChars        : 0,
            autoSelectFirst : true,
            showNoSuggestionNotice : 'Sin resultados',
            lookup          : ubicaciones,
            onSelect        : function(suggestion) {
                $("input[name=ubigeo]").val(suggestion.id);
            },
            onSearchComplete   : function(query, suggestions){
            },
            onHint: function (hint) {
                $('#autocomplete-ajax-x').val(hint);
            },
            onInvalidateSelection: function() {
                $('#selction-ajax').html('You selected: none');
            }
        });


    },
    setLista : function (data) {
        var html = '';
        data.forEach( (e) => {
            html += `
                <li>
                    <label>
                        <input type="checkbox" class="" name="series[]" value="${e.id}" checked" />
                        ${e.serie}
                    </label>
                </li>
            `;
        });
        $("#seriesList ul").html( html );
    },
    getFormData :function () {
        var form = new FormData(document.getElementById("formEstablecimientos"));
        return form;
    },
    guardarEleccion : function (e) {
        e.preventDefault();
        $("#formEstablecimientos #btn_guardar").prop("disabled", true);
        GlobalSpinner.mostrar();
        var datos = ConfiguracionEstablecimientos.getFormData();
        $.ajax({
            url: `${base}configuraciones/api-configuracion-series-establecimiento`,
            data: datos,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (r) {
                console.log(r);
                if (r.success) {
                    ConfiguracionEstablecimientos.cerrar();
                    ConfiguracionFinal.init();
                } else {
                    aler(r.message)
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
            complete: function () {
                $("#formEstablecimientos #btn_guardar").prop("disabled", false);
                GlobalSpinner.ocultar();
            }
        });
    },
    cancelar : function () {
        var c = confirm("Si cancela la configuracion se asignara todo en modo DEMO. ¿Continuar?");
        if(c){
            $("#formEstablecimientos #btn_guardar").prop("disabled", true);
            GlobalSpinner.mostrar();
            $.ajax({
                url: `${base}configuraciones/api-configuracion-cancelar`,
                data: { },
                type: 'POST',
                success: function (r) {
                    if (r.success) {
                        ConfiguracionEstablecimientos.cerrar();
                    } else {
                        alert("Ocurrio un error, consulte con el administrador.")
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                },
                complete: function () {
                    $("#formEstablecimientos #btn_guardar").prop("disabled", false);
                    GlobalSpinner.ocultar();
                }
            });
        }
    },
    cerrar : function () {
        ConfiguracionEstablecimientos.limpiar();
        $(ConfiguracionEstablecimientos.id).modal('hide')
    },
    limpiar: function () {
        $("#formEstablecimientos [name=boleta]").val("");
        $("#formEstablecimientos [name=boleta_correlativo]").val("");
        $("#formEstablecimientos [name=factura]").val("");
        $("#formEstablecimientos [name=factura_correlativo]").val("");
        $("#formEstablecimientos [name=guia_remitente]").val("");
        $("#formEstablecimientos [name=guia_remitente_correlativo]").val("");
        $("#formEstablecimientos [name=factura_nc]").val("");
        $("#formEstablecimientos [name=factura_nc_correlativo]").val("");
        $("#formEstablecimientos [name=boleta_nc]").val("");
        $("#formEstablecimientos [name=boleta_nc_correlativo]").val("");
        $("#formEstablecimientos [name=factura_nd]").val("");
        $("#formEstablecimientos [name=factura_nd_correlativo]").val("");
        $("#formEstablecimientos [name=boleta_nd]").val("");
        $("#formEstablecimientos [name=boleta_nd_correlativo]").val("");
    }
}

</script>
