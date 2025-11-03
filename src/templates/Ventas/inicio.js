var compra_montos = [],
    compra_fechas = [],
    compras_acumuladas = [],
    compras_diarias = [],
    venta_montos = [],
    venta_fechas = [],
    total_fechas = [],
    ventas_acumuladas = [],
    ventas_diarias = [],
    ctx = document.getElementById('chartHistoricoVentasMensuales'),
    ctx2 = document.getElementById('chartHistoricoVentasDiarios');



$(document).ready(function(){

    getUltimoResumenDiario();
    getFacturasNoEnviadas();
    poblarStocks();
    poblarProductosMasVendidos();
    filtrarVentas();
    filtrarCompras()

    regularizarComprasVentas(ventas_acumuladas,compras_acumuladas,true);
    regularizarComprasVentas(ventas_diarias,compras_diarias);

    setMontosFechas(ventas_acumuladas,compras_acumuladas);


    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: total_fechas ,
            datasets:
                [
                    {
                        label: 'Ventas de este mes',
                        data: venta_montos,
                        borderColor: 'rgba(60, 213, 69, 1)',
                        borderWidth: 1,
                        yAxisID : 'y',

                    },
                    {
                        label: 'Compras de este mes',
                        data: compra_montos,
                        borderColor: 'rgba(243, 151, 34, 1)',
                        borderWidth: 1,
                        yAxisID : 'y1'
                    }
                ]
        },
        options : {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            stacked: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Histórico de venta mensual acumulada'
                }
            },
            scales: {
                x : {
                  display:false
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    suggestedMin: 0
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    // grid line settings
                    grid: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                    },
                    suggestedMin: 0
                },
            }
        }
    });


    setMontosFechas(ventas_diarias,compras_diarias);


    var myChart2 = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: total_fechas ,
            datasets: [{
                label: 'Ventas de este mes por día',
                fill: true,
                data: venta_montos,
                borderColor: 'rgba(60, 213, 69, 1)',
                borderWidth: 1,
                backgroundColor : 'rgba(60, 213, 69, 1)'  ,
                yAxisID : 'y'
            },
                {
                    label: 'Compras de este mes por dia',
                    fill: true,
                    data: compra_montos,
                    borderColor:  'rgba(243, 151, 34, 1)',
                    borderWidth: 1,
                    backgroundColor: 'rgba(243, 151, 34, 1)',
                    yAxisID     :   'y1'
                }


            ]
        },
        options : {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            stacked: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Ventas diarias de este mes'
                }
            },
            scales: {
                x: {
                  display:true
                },
                y: {
                    // type: 'bar',
                    display: true,
                    position: 'left',
                    suggestedMin: 0
                },
                y1: {
                    // type: 'bar',
                    display: true,
                    position: 'right',

                    // grid line settings
                    grid: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                    },
                    suggestedMin: 0
                },
            }
        }


    });
})

function filtrarVentas(){
    if(typeof  _ventas !='undefined' && _ventas.length > 0) {

        for (let i = 0; i < _ventas.length; i++) {
            ventas_acumuladas.push
            (
                {
                    total: i > 0 ? parseFloat(_ventas[i].total) + parseFloat(ventas_acumuladas[i - 1].total) : parseFloat(_ventas[i].total),
                    fecha_venta: new Date(_ventas[i].fecha_venta).getFullYear() + "-" + (new Date(_ventas[i].fecha_venta).getMonth() + 1) + "-" + new Date(_ventas[i].fecha_venta).getDate()
                }
            )
            ventas_diarias.push
            (
                {
                    total: parseFloat(_ventas[i].total),
                    fecha_venta: new Date(_ventas[i].fecha_venta).getFullYear() + "-" + (new Date(_ventas[i].fecha_venta).getMonth() + 1) + "-" + new Date(_ventas[i].fecha_venta).getDate()
                }
            )
        }

    }

}

function filtrarCompras()
{
    if(typeof  _compras !='undefined' && _compras.length > 0){
        for(let i = 0 ; i < _compras.length ; i++){
            compras_acumuladas.push(
                {
                    total: i > 0 ? parseFloat(_compras[i].total)  + parseFloat(compras_acumuladas[i - 1].total) : parseFloat(_compras[i].total) ,
                    fecha_compra :  new Date(_compras[i].fecha_compra).getFullYear() + "-" + (new Date(_compras[i].fecha_compra).getMonth() + 1) + "-" + new Date(_compras[i].fecha_compra).getDate()
                }
            );
            compras_diarias.push(
                {
                    total: parseFloat(_compras[i].total) ,
                    fecha_compra :  new Date(_compras[i].fecha_compra).getFullYear() + "-" + (new Date(_compras[i].fecha_compra).getMonth() + 1) + "-" + new Date(_compras[i].fecha_compra).getDate()
                }
            );
        }

    }
}


function regularizarComprasVentas(ventasArray,comprasArray,es_acumulada = false)
{
    ventasArray.forEach(
        function(venta){
            if(!(comprasArray.filter( compra => compra.fecha_compra === venta.fecha_venta).length > 0)){
                comprasArray.push(
                    {
                        total : 0,
                        fecha_compra: venta.fecha_venta
                    }
                )
            }
        }
    )
    comprasArray.sort((a, b) =>  new Date(a.fecha_compra) - new Date(b.fecha_compra));


    comprasArray.forEach(
        function(compra){
            if(!(ventasArray.filter( venta => venta.fecha_venta === compra.fecha_compra).length > 0)){
                ventasArray.push(
                    {
                        fecha_venta: compra.fecha_compra,
                        total : 0
                    }
                )
            }
        }
    )

    ventasArray.sort((a, b) =>  new Date(a.fecha_venta) - new Date(b.fecha_venta));

    if(es_acumulada){
        ventas_acumuladas = ventasArray;
        compras_acumuladas = comprasArray;
    }
    else
    {
        ventas_diarias = ventasArray;
        compras_diarias = comprasArray;
    }
}
 function setMontosFechas(ventasArray,comprasArray)
 {
     total_fechas = [];
     venta_montos = [];
     compra_montos = [];

     if(typeof  ventasArray !='undefined' && ventasArray.length > 0) {
         for (let i = 0; i < ventasArray.length; i++) {
             venta_montos.push(parseFloat(ventasArray[i].total));
             total_fechas.push(ventasArray[i].fecha_venta);
         }
     }

     if(typeof  comprasArray !='undefined' && comprasArray.length > 0) {
         for (let i = 0; i < comprasArray.length; i++) {
             compra_montos.push(parseFloat(comprasArray[i].total));
             if(total_fechas.indexOf(comprasArray[i].fecha_compra) === -1  ){
                 total_fechas.push(comprasArray[i].fecha_compra);
             }
         }
     }

     total_fechas.sort((a, b) =>  new Date(a) - new Date(b));
 }

function getUltimoResumenDiario()
{
    var endpoint = `${base}sunat-fe-resumen-diarios/get-ultimo-resumen-diario/`;
    setSpinner("div_resumen_diario");
    $.ajax({
        url :   endpoint,
        data:   '',
        type:   'GET',
        success :   function(data)
        {
            if(data.success)
            {
                poblarDatosResumenDiario(data.data);
            }else{
                //alert(data.message);
                var html = `<h5>Último Resúmen diario Enviado</h5>
                            <span> <i class="fa fa-exclamation-triangle fa-fw"></i> Sin Registros</span>`;
                $("#div_resumen_diario").html(html);

            }
        },
        error   :   function(xhr,ajaxOptions,thrownError)
        {
            console.log(xhr.status);
            console.log(ajaxOptions);
            var html = `<h5>Último Resúmen diario Enviado</h5>
                        <span> <i class="fa fa-exclamation-triangle fa-fw"></i> Sin Registros</span>`;
            $("#div_stocks").html(html);
        }

    })

}
function poblarDatosResumenDiario(data)
{
    var html = `<h5>Último Resúmen diario Enviado</h5>
                <br>`;
    html +=
        `
        <i class='fa fa-passport fa-fw'></i>${data.nombre_unico}
        <br>
        <i class='fa fa-calendar-alt fa-fw'></i>${data.fecha_generacion}
        <br>
        <i class='fa fa-file-medical-alt fa-fw'></i>${data.sunat_cdr_description == null ? 'Sin respuesta cdr' : data.sunat_cdr_description }
        <br>
        <a class="badge badge-primary" href="javascript:void(0)" onclick="GlobalResumenDiarioDetalles.abrir(${data.id})">
            Ver Comprobantes
        </a>
        `;

    if($("#div_resumen_diario").length){
        $("#div_resumen_diario").html(html);
    }
}

function poblarStocks()
{
    var html =
        `<h5>Top 10 Productos con Stocks Mínimos</h5> `;

    html += `<a href="javascript:void(0)" onclick="ModalProductosStockMinimo.abrir()" class="badge badge-danger"> Ver productos </a>   `;

    if($("#div_stocks").length){
        $("#div_stocks").html(html);
    }
}
function poblarProductosMasVendidos(){
    var html =
        `<h5>Top 20 Productos mas vendidos</h5> `;

    html += `<a href="javascript:void(0)" onclick="ModalProductosMasVendidos.abrir()" class="badge badge-danger"> Ver productos </a>   `;

    if($("#div_prod_vend").length){
        $("#div_prod_vend").html(html);
    }
}
function setSpinner(element_id)
{
    $(`#${element_id}`).html(`<div class="spinner-grow" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>`);
}

function getFacturasNoEnviadas()
{
    setSpinner("div_facturas_noenviadas");
    var endpoint = `${base}sunat-fe-facturas/get-facturas-sin-enviar`;
    $.ajax({
        url :   endpoint,
        data:   '',
        type:   'GET',
        success :   function(data)
        {
            if(data.success)
            {
                poblarFacturasNoEnviadas(data.data);
            }else{

                var html = `<h5>Facturas sin enviar del mes</h5>
                            <span> <i class="fa fa-exclamation-triangle fa-fw"></i> Sin Registros</span>`;
                $("#div_facturas_noenviadas").html(html);

            }
        },
        error   :   function(xhr,ajaxOptions,thrownError)
        {
            console.log(xhr.status);
            console.log(ajaxOptions);
            var html = `<h5>Facturas sin enviar del mes</h5>
                        <span> <i class="fa fa-exclamation-triangle fa-fw"></i> Sin Registros</span>`;
            $("#div_facturas_noenviadas").html(html);
        }

    })
}


function poblarFacturasNoEnviadas(data)
{
    var html = `<h5>Facturas sin enviar del mes</h5>
                <br> `;

    $(data).each(function(i,obj)
        {
            html +=
                `
                    <i class='fa fa-file fa-fw'></i>${obj.serie}-${obj.correlativo}
                    <br>
                    `;
        }

    )
    if($("#div_facturas_noenviadas").length)
    {
        $("#div_facturas_noenviadas").html(html);
    }
}
