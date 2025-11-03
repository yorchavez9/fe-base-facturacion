$(document).ready(function () {
    NotaCreditoModificacion.getData();
    $("#formapago_fecha1").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4',
    });

    $("#formapago_fecha2").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4',
    });

    $("#formapago_fecha3").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4',
    });
    $(".fecha_emision_hidden").hide();
    $("[name=fecha_emision]").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });
});

var almacen_id = 0;
var documento_tipo = "";
var cantidad_items = 0;
var venta_id = 0;

function calcularTotales() {
    var sum_subtotal = 0;
    var sum_igv = 0;
    var sum_total = 0;

    for (var i = 0; i < cantidad_items; i++) {

        let codigo_motivo = $('[name=codigo_motivo_credito]').val();

        let cantidad = 0;
        let precio = 0;
        if(codigo_motivo == '01' ||codigo_motivo == '02' ||codigo_motivo == '06'){
            cantidad = $(`#item_cantidad_dice_${i}`).val();
            precio = $(`#item_precio_uventa_dice_${i}`).val();
        }else if (codigo_motivo == '07'){
            precio = $(`#item_precio_uventa_dice_${i}`).val();
            let cantidad_dice = $(`#item_cantidad_dice_${i}`).val();
            let cantidad_debedecir = $(`#item_cantidad_debedecir_${i}`).val();
            cantidad = parseFloat(cantidad_dice) - parseFloat(cantidad_debedecir);
        }else if (codigo_motivo == '08'){
        }else if (codigo_motivo == '09'){
            cantidad = $(`#item_cantidad_dice_${i}`).val();
            let precio_dice = $(`#item_precio_uventa_dice_${i}`).val();
            let precio_debedecir = $(`#item_precio_uventa_debedecir_${i}`).val();
            precio = parseFloat(precio_dice) - parseFloat(precio_debedecir);
        }

        var precio_total = (parseFloat(cantidad) * parseFloat(precio)).toFixed(2);
        var subtotal = (parseFloat(precio_total) / 1.18).toFixed(2);
        var igv_monto = (parseFloat(precio_total) - parseFloat(subtotal)).toFixed(2);

        $(`#item_subtotal_${i}`).val(subtotal);
        $(`#item_igv_monto_${i}`).val(igv_monto);
        $(`#item_precio_total_${i}`).val(precio_total);

        sum_subtotal += parseFloat(subtotal);
        sum_igv += parseFloat(igv_monto);
        sum_total += parseFloat(precio_total);

        $(`#sum_subtotal`).val(sum_subtotal.toFixed(2));
        $(`#sum_igv`).val(sum_igv.toFixed(2));
        $(`#sum_total`).val(sum_total.toFixed(2));
    }

    // actualizarBloqueosCampos();
}
function actualizarBloqueosCampos() {
    var codigo_motivo_credito = $('[name=codigo_motivo_credito]').val();

    for (var i = 0; i < cantidad_items; i++) {
        if (
                codigo_motivo_credito == "01" ||
                codigo_motivo_credito == "02" ||
                codigo_motivo_credito == "06"
            ) {
            $(`#item_nombre_${i}`).attr('readonly', true);
            $(`#item_unidad_${i}`).attr('readonly', true);
            let cant = $(`#item_cantidad_dice_${i}`).val();
            $(`#item_cantidad_debedecir_${i}`).attr('readonly', true);
            $(`#item_cantidad_debedecir_${i}`).val(cant);
            $(`#item_precio_uventa_debedecir_${i}`).attr('readonly', true);

            $("#nc_fechamontopago_container").hide();
            $("#nc_items_container").show();
        }
        else if (codigo_motivo_credito == "03") {
            $(`#item_nombre_${i}`).attr('readonly', false);
            $(`#item_unidad_${i}`).attr('readonly', false);
            $(`#item_cantidad_${i}`).attr('readonly', true);
            $(`#item_precio_uventa_${i}`).attr('readonly', true);
            $("#nc_fechamontopago_container").hide();
            $("#nc_items_container").show();
        } else if (codigo_motivo_credito == "07") {
            $(`#item_nombre_${i}`).attr('readonly', true);
            $(`#item_unidad_${i}`).attr('readonly', true);
            $(`#item_cantidad_debedecir_${i}`).attr('readonly', false);
            $(`#item_precio_uventa_debedecir_${i}`).attr('readonly', true);
            $("#nc_fechamontopago_container").hide();
            $("#nc_items_container").show();
        } else if (codigo_motivo_credito == "09") {
            $(`#item_nombre_${i}`).attr('readonly', true);
            $(`#item_unidad_${i}`).attr('readonly', true);
            $(`#item_cantidad_debedecir_${i}`).attr('readonly', true);
            $(`#item_precio_uventa_debedecir_${i}`).attr('readonly', false);
            $("#nc_fechamontopago_container").hide();
            $("#nc_items_container").show();
        } else if (codigo_motivo_credito == "13") {

            // aquí se deben obtener detalles de la factura a crédito

            $("#nc_items_container").hide();
            $("#nc_fechamontopago_container").show();
        }
    }

    calcularTotales()
}

function actualizarSeries(estab_id, doc_tipo) {

    var endpoint = `${API_FE}/sunat-fe-series/get-series-by-almacen?tipo=${doc_tipo}&establecimiento_id=${estab_id}`
    $.ajax({
        url: endpoint,
        data: '',
        type: 'GET',
        success: function (r) {
            if (r.success) {
                var html = ``;
                $(r.data).each(function (i, o) {
                    html += `<option value="${o.serie}">${o.serie}</option>`;
                });
                $('#serie').html(html);
            }
        }
    });

}
function sumarCuotas(){
    // obtenemos el monto total original
    let montototal = document.getElementById("nc_monto_original")

    // lo convertimos en numero
    montototal = parseFloat(montototal.innerHTML);

    // obtenemos las cuotas
    let cuotas = document.getElementsByClassName("nueva_cuota");
    let suma = 0;

    // sumamos las cuotas
    for(let c of cuotas){
        if(c.value != ''){
            suma += parseFloat(c.value)
        }
    }

    // validamos
    if(suma > montototal){
        alert("ERROR: La suma de las nuevas cuotas no debe exceder el monto original");
        for(let c of cuotas){
            c.value = 0
        }
    }else{
        var label = document.getElementById("nc_suma_cuotas")
        label.innerHTML = suma;
    }

}

var NotaCreditoModificacion = {
    submit : function(e){
        e.preventDefault();
        var endpoint = `${API_FE}/sunat-fe-notas-credito/nota-credito`;
        var formContent = document.getElementById("notaCreditoModificacionForm");
        var form = new FormData(formContent);

        $("#modalEnviandoNotaCredito").modal("show");

        $.ajax({
            url     : endpoint,
            data    : form,
            type    : 'POST',
            //dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function (data, status, xhr) {
                console.log(data);
                if (data.success) {
                    NotaCreditoModificacion.enviarSunat(data.data.id);
                } else {
                    alert(data.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
        return false;
    },
    enviarSunat : function(nota_id){
        var endpoint = `${API_FE}/sunat-fe-notas-credito/api-enviar-sunat/${nota_id}`;

        $.ajax({
            url     : endpoint,
            data    : {},
            type    : 'POST',
            //dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function (data, status, xhr) {
                console.log(data);
                location.href = `${base}sunat-fe-notas-credito/index`;
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

    },
    getData     : function(){
        var endpoint = `${API_FE}/sunat-fe-facturas/single/${factura_id}`;
        $.ajax({
            url     : endpoint,
            data    : { },
            type    : 'GET',
            //dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function (data, status, xhr) {
                console.log(data);
                if(data.success){
                    NotaCreditoModificacion.setDatos(data.data)
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    },
    setDatos     : function( data ){
        $('[name=cliente_doc_numero]').val(data.cliente_num_doc )
        $('[name=cliente_razon_social]').val(data.cliente_razon_social )
        $('[name=cliente_doc_tipo]').val(data.cliente_tipo_doc)

        $('[name=num_doc_afectado]').val(`${data.serie}-${data.correlativo}`)

        $('[name=tipo_moneda]').val(data.tipo_moneda)

        $('#factura_total').val(data.mto_imp_venta)

        $('[name=mto_oper_gravadas]').val(data.mto_oper_gravadas);
        $('[name=mto_igv]').val(data.mto_igv);
        $('[name=mto_imp_venta]').val(data.mto_imp_venta);
        $('[name=factura_id]').val(data.id);

        $(".tipo_mon_factura").html(data.tipo_moneda)
        $("#nc_monto_original").html(data.mto_imp_venta)

        actualizarSeries(data.establecimiento_id, data.tipo_doc == '01' ? 'FACTURA_NC': 'BOLETA_NC')
        let fecha = new Date(data.fecha_emision);
        $('#factura_f_venta').val(`${fecha.getFullYear()}-${(fecha.getMonth() + 1).toString().padStart(2, "0") }-${fecha.getDate().toString().padStart(2, "0")} ${fecha.getHours().toString().padStart(2, "0")}:${fecha.getMinutes()}`)
        NotaCreditoModificacion.createTable(JSON.parse(data.items));
    },
    createTable     :function (items){
        var str = '';
        
        items.forEach((data, index)=>{
            cantidad_items +=1;
            str += `
            <tr>
                <td>
                    <input type='hidden' name="json_items[${index}][item_codigo]" value="${data.item_codigo}" />
                    <input type='hidden' name="json_items[${index}][item_id]" value="${data.item_id}" />
                    <input type='hidden' name="json_items[${index}][valor_venta]" value="${data.valor_venta}" />
                    <input type='hidden' name="json_items[${index}][item_afectacion_igv]" value="${data.afectacion_igv}" />
                </td>
                <td width='300px'> <input type='text' class='form-control form-control-sm' name='json_items[${index}][item_nombre]' value='${data.item_nombre}' id='item_nombre_${index}'> </td>
                <td> <input type='text' class='form-control form-control-sm' name='json_items[${index}][item_unidad]' value='${data.item_unidad}' id='item_unidad_${index}'> </td>
                <td> 
                    Dice: ${data.cantidad}<br/><input type='hidden' name='json_items[${index}][item_cantidad_dice]' value='${data.cantidad}'  id='item_cantidad_dice_${index}' >
                    <b>Debe decir:</b><input type='number' class='nc_formatototales_input' name='json_items[${index}][item_cantidad_debedecir]' value='${data.cantidad}'  id='item_cantidad_debedecir_${index}' onchange='calcularTotales()' style='width:80px;' >
                </td>
                <td> 
                    Dice: ${data.precio_uventa} <br/><input type='hidden' name='json_items[${index}][item_precio_uventa_dice]' value='${data.precio_uventa}'  id='item_precio_uventa_dice_${index}' >
                    <b>Debe decir:</b> <input type='text' class='nc_formatototales_input' name='json_items[${index}][item_precio_uventa_debedecir]' value='${data.precio_uventa}'  id='item_precio_uventa_debedecir_${index}' onchange='calcularTotales()'  style='width:120px;' >
                </td>
                <td width='170px'>
                    <label style='width:70px; float:left;'>Subtotal</label><input type='text' class='nc_formatototales_input' name='json_items[${index}][item_subtotal]' value='${data.subtotal}' id='item_subtotal_${index}'  style='width:75px; float:right;' readonly>
                    <div style='clear:both;'></div>
                    <label style='width:70px; float:left;'>IGV</label><input type='text' class='nc_formatototales_input' name='json_items[${index}][item_igv_monto]' value='${data.igv_monto}' id='item_igv_monto_${index}'  style='width:75px; float:right;' readonly> <br/>
                    <div style='clear:both;'></div>
                    <label style='width:70px; float:left;'>Total</label><input type='text' class='nc_formatototales_input' name='json_items[${index}][item_precio_total]' value='${data.precio_total}' id='item_precio_total_${index}'  style='width:75px; float:right;' readonly>
                    <div style='clear:both;'></div>
                </td>
            </tr>
            `;
        })

        $('#detalle_items').html(str);
        calcularTotales()
        actualizarBloqueosCampos()
    }
}
