var venta_montos = [],
    venta_fechas = [],
    ventas_acumuladas = [],
    ventas_diarias = [],
    total_fechas = [],
    ctx2 = document.getElementById('chartHistoricoVentasDiarios')


$(document).ready(function () {

    $("[name=fecha_ini]").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });
    $("[name=fecha_fin]").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });

    poblarStocks();
    poblarProductosMasVendidos();
    var historicoDatasets = generadorHistorico.generarArrayObjetosParaHistorico(
       {
           object_collection: _ventas,
           label: 'Ventas de este mes por día',
           borderColor: 'rgba(60, 213, 69, 1)',
           backgroundColor : 'rgba(60, 213, 69, 1)' ,
        },
       );

    var myChart2 = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: generadorHistorico.fechas_totales ,
            datasets: historicoDatasets
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
                    text:  'Ventas del mes'
                }
            },
            scales: {
                x: {
                  display:true
                },
                y: {
                    display: true,
                    position: 'left',
                    suggestedMin: 0
                },
            }
        }


    });
});


var generadorHistorico  = {
    arrayVentas : [],
    fechas_totales : [],
    generarArrayObjetosParaHistorico : function(...arrayElementsCollection){
        var datasets = [];
        /**
         * Cada elemento corresponde un objeto con esta forma :
         * {
         *  object_collection => esto puede ser el array de ventas, de gastos, de compras, etc, considerar que cada uno
         *  de estos debe tener la forma { total , fecha}
         *  label => el label que se quiere que figure en el data,
         *  borderColor => el color del borde que se quiere en el grafico
         *  backgroundColor => backgroundColor que se quiere en el grafico
         * }
         */
        var arrayElementsCollectionCopy =
        arrayElementsCollection.map((element) => {
            /**
             * En esta parte lo que se busca es que cada objecto
             * o cada registro que se ha extraido de la base de datos
             * tenga la forma {total, fecha} pero con una fecha formateada
             * correctamente en javascript
             */
            if(element.object_collection.length > 0){
                element.object_collection = element.object_collection.map((objecto) => {
                    var objecto_fecha = new Date(objecto.fecha + "T00:00:00" );
                    return {
                        total : objecto.total,
                        fecha : objecto_fecha.getFullYear() + "-" + (objecto_fecha.getMonth() + 1) + "-" + objecto_fecha.getDate()
                    }
                })
            }else{
                element.object_collection = [];
            }

            return element;
        })

        generadorHistorico.fechas_totales = generadorHistorico.getFechasTotales(arrayElementsCollectionCopy);

        arrayElementsCollectionCopy.forEach((element) => {
            datasets.push(generadorHistorico.getDataOption(element));
        })

        return datasets;


    },
    /**
     *
     * @param {*} arrayElementsCollectionCopy
     * @returns
     * Este meotod lo que hace es recorrer cada uno de los elementos suministrados
     * Entediendose por elemento un objecto con la siguiente forma :
     * {
         *  object_collection => esto puede ser el array de ventas, de gastos, de compras, etc, considerar que cada uno
         *  de estos debe tener la forma { total , fecha}
         *  label => el label que se quiere que figure en el data,
         *  borderColor => el color del borde que se quiere en el grafico
         *  backgroundColor => backgroundColor que se quiere en el grafico
         * }
         * Con el fin de obtener todas las fechas a mostrar
     */
    getFechasTotales(arrayElementsCollectionCopy){
        var fechas_totales = [];
        arrayElementsCollectionCopy.forEach((collection) => {
            collection.object_collection.forEach((object) => {
                fechas_totales.push(object.fecha)
            });
        })
        fechas_totales = [...new Set(fechas_totales)];

        return fechas_totales.sort((a, b) =>  new Date(a) - new Date(b));
    },
    /**
     *
     * @param {*} element
     * @returns
     * El objecto de este metodo
     * es obtener cada un objeto para cargar el dataset
     * del reporte historico
     */
    getDataOption(element){
        var objectCollection = element.object_collection;
        var arrayObjectCollectionDates = [];

        if(objectCollection.length > 0){
            arrayObjectCollectionDates =
            objectCollection.map((object) => {
                return object.fecha
            })
        }
        /*Se llenan las fechas que no se encuentran en el array de objectos
        Del elemento, para que la cantidad de montos, corresponda a la cantidad
        de fechas totales*/
        generadorHistorico.fechas_totales.forEach((fecha) => {
            if(arrayObjectCollectionDates.indexOf(fecha) == -1){
                objectCollection.push({
                    total : 0,
                    fecha : fecha
                })
            }
        })

        objectCollection.sort((a, b) =>  new Date(a.fecha) - new Date(b.fecha))

        /**
         * Se recolectan cada unos de los montos del array ordenado
         * con el fin de hacer coincidir cada monto con una fecha del listado
         * de fechas totales
         */
        var arrayObjectCollectionMoneyTotals = []
        arrayObjectCollectionMoneyTotals = objectCollection.map((object) => {
            return object.total
        })

        var dataset = {
            label: element.label,
            fill: true,
            data: arrayObjectCollectionMoneyTotals,
            borderColor: element.borderColor,
            borderWidth: 1,
            backgroundColor : element.backgroundColor  ,
            yAxisID : 'y'
        }

        return dataset;
    }
}



var waypoint = new Waypoint({
    element: document.getElementById('basic-waypoint'),
    handler: function () {
        jQuery(function ($) {
            // custom formatting example
            $('.count-number').data('countToOptions', {
                formatter: function (value, options) {
                    return options.prependMoneda + value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
                }
            });

            // start all the timers
            $('.timer').each(count);

            function count(options) {
                var $this = $(this);
                options = $.extend({}, options || {}, $this.data('countToOptions') || {});
                $this.countTo(options);
            }
        });
    },
    offset: '90%'
})



function isInViewport(node) {
    var rect = node.getBoundingClientRect()
    return (
        (rect.height > 0 || rect.width > 0) &&
        rect.bottom >= 0 &&
        rect.right >= 0 &&
        rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.left <= (window.innerWidth || document.documentElement.clientWidth)
    )
}

(function ($) {
    $.fn.countTo = function (options) {
        options = options || {};

        return $(this).each(function () {
            // set options for current element
            var settings = $.extend({}, $.fn.countTo.defaults, {
                from: $(this).data('from'),
                to: $(this).data('to'),
                speed: $(this).data('speed'),
                refreshInterval: $(this).data('refresh-interval'),
                decimals: $(this).data('decimals'),
                prependMoneda : $(this).data('prepend-moneda')
            }, options);

            // how many times to update the value, and how much to increment the value on each update
            var loops = Math.ceil(settings.speed / settings.refreshInterval),
                increment = (settings.to - settings.from) / loops;

            // references & variables that will change with each update
            var self = this,
                $self = $(this),
                loopCount = 0,
                value = settings.from,
                data = $self.data('countTo') || {};

            $self.data('countTo', data);

            // if an existing interval can be found, clear it first
            if (data.interval) {
                clearInterval(data.interval);
            }
            data.interval = setInterval(updateTimer, settings.refreshInterval);

            // initialize the element with the starting value
            render(value);

            function updateTimer() {
                value += increment;
                loopCount++;

                render(value);

                if (typeof (settings.onUpdate) == 'function') {
                    settings.onUpdate.call(self, value);
                }

                if (loopCount >= loops) {
                    // remove the interval
                    $self.removeData('countTo');
                    clearInterval(data.interval);
                    value = settings.to;

                    if (typeof (settings.onComplete) == 'function') {
                        settings.onComplete.call(self, value);
                    }
                }
            }

            function render(value) {
                var formattedValue = settings.formatter.call(self, value, settings);
                $self.html(formattedValue);
            }
        });
    };

    $.fn.countTo.defaults = {
        from: 0, // the number the element should start at
        to: 0, // the number the element should end at
        speed: 1000, // how long it should take to count between the target numbers
        refreshInterval: 100, // how often the element should be updated
        decimals: 2, // the number of decimal places to show
        prependMoneda : 'S/.',
        formatter: formatter, // handler for formatting the value before rendering
        onUpdate: null, // callback method for every time the element is updated
        onComplete: null, // callback method for when the element finishes updating

    };

    function formatter(value, settings) {
        console.log(settings.prependMoneda);
        return settings.prependMoneda + value.toFixed(settings.decimals);
    }
}(jQuery));


function poblarStocks()
{
    var html = ``;

    html += `<a href="javascript:void(0)" onclick="ModalProductosStockMinimo.abrir()" class="badge badge-danger"> Haz clic aquí para ver los productos con stock mínimo </a>   `;

    if($("#div_stocks").length){
        $("#div_stocks").html(html);
    }
}
function poblarProductosMasVendidos(){
    var fecha_ini = $("[name=fecha_ini]").val();
    var fecha_fin = $("[name=fecha_fin]").val();
    console.log(fecha_ini);
    console.log(fecha_fin);
    var html = ``;

    html += `<a href="javascript:void(0)" onclick="ModalProductosMasVendidos.abrir('${fecha_ini}','${fecha_fin}')" class="badge badge-danger"> Haz clic aquí para ver los productos más vendidos </a>`;

    if($("#div_prod_vend").length){
        $("#div_prod_vend").html(html);
    }
}
