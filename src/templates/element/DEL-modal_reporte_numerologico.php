<div class="modal fade" id="modalReporteNumerologico" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Consulta numerólogica</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="" id="resultado_numerologico">
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <span>
                            Servicio provisto por <a href="https://www.superha.pe/" target="_blank">Superha</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .superha{
        font-size: 0.5 em;
        color: #B4B6B8
    }
</style>

<script>
    var reporteNumerologicoPersonas = {
        IdModal: 'modalReporteNumerologico',
        persona_id: 0,
        empresa_id: 0,
        endpoint: `${base}personas/api-get-reporte-numerologico/`,
        resultado_numerologico: {},
        abrir: function(persona_id = 0,empresa_id = 0) {
            this.limpiar();
            this.persona_id = persona_id;
            this.empresa_id = empresa_id;
            this.consultarReporte();
            
        },
        mostrar :   function(){
            $(`#${reporteNumerologicoPersonas.IdModal}`).modal('show');
        },
        ocultar :   function(){
            $(`#${reporteNumerologicoPersonas.IdModal}`).modal('hide');
        },
        limpiar: function() {
            $(`#${reporteNumerologicoPersonas.IdModal} #resultado_numerologico`).html('');
        },
        consultarReporte: function() {
            GlobalSpinner.mostrar();
            var emp_id = reporteNumerologicoPersonas.empresa_id != 0 ? reporteNumerologicoPersonas.empresa_id : '';
            var endpoint = `${reporteNumerologicoPersonas.endpoint}${reporteNumerologicoPersonas.persona_id}/${emp_id}`;
            $.ajax({
                url: endpoint,
                data: '',
                type: 'GET',
                success: function(data) {
                    if (data.success) {
                        reporteNumerologicoPersonas.resultado_numerologico = data.data;
                        reporteNumerologicoPersonas.mostrarReporte();
                    } else {
                        alert(data.message);
                    }
                    GlobalSpinner.ocultar();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    GlobalSpinner.ocultar();
                    reporteNumerologicoPersonas.ocultar();
                    alert('Ha habido un error, intente de nuevo mas tarde');
                }
            })
        },
        mostrarReporte: function() {
            console.log(reporteNumerologicoPersonas.resultado_numerologico);
            var html = '';
            var reporte     =   reporteNumerologicoPersonas.resultado_numerologico,
                fecha_anio  =   reporte.fecha_anio,
                fecha_mes   =   reporte.fecha_mes,
                fecha_dia   =   reporte.fecha_dia,
                alma        =   reporte.numerologia.alma,
                alma_positiva_info =    alma.positiva != null ? alma.positiva.info_basica : '',
                alma_negativa_info =    alma.negativa != null ? alma.negativa.info_basica : '',  
                karma       =   reporte.numerologia.karma,
                destino     =   reporte.numerologia.destino,
                talentos    =   reporte.numerologia.talentos,
                mision      =   reporte.numerologia.mision;
            html = `
            <div class="contenedor py-5">
    <div class="w-100">
        <div class="">
            <!--<img class="img-logo" src="<?= $this->Url->build("/public/logo.png") ?>" alt="logo" />-->
            <div class="title">
                Consulta Numerológica
            </div>
        </div>
        <hr>
        <div>
            <div class="text-center">
                <i class="fa fa-user" style="font-size:5em;color:#00CFF2" aria-hidden="true"></i>
                <div class="div-name mt-2">
                    <div class="text-bold">
                        ${reporte.nombres} 
                    </div>
                    <div class="div-fecha">
                        
                        ${fecha_dia} - ${fecha_mes} - ${fecha_anio}
                    </div>
                </div>
            </div>
            <div class="back row">
                <div class="col-6 pl-0 pr-1">
                    <div class="numeros py-4">
                        <div class="text-center pb-3 font-weight-bold">
                            ALMA <span class="numero alma"> ${alma.cantidad} </span> 
                            <div>
                                <!--<img src="<?= $this->Url->build('/numerologia/alma.png', ['fullBase' => true]) ?>" style="width:100px" />-->
                            </div>
                        </div>
                        <div class="row contenido-90">
                            <div class="col-6">
                                <div class="font-weight-bold">Vibración Positiva:</div>
                                ${alma_positiva_info}
                            </div>
                            <div class="col-6">
                                <div class="font-weight-bold">Vibración Negativa:</div>
                                ${alma_negativa_info}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-1 pr-0">
                    <div class="numeros py-4">
                        <div class="text-center pb-3 font-weight-bold"> 
                            REGALO <span class="numero talentos"> ${talentos.cantidad} </span> 
                            <div>
                                <!--<img src="<?= $this->Url->build('/numerologia/talentos.png', ['fullBase' => true]) ?>" style="width:100px" />-->
                            </div>
                        </div>
                        <div class="contenido">
                            ${talentos.informacion != null ? talentos.informacion.info_basica : ''}
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex">
                <div class="col d-flex align-items-center px-0">
                    <div class="back w-100" style="height:5px;"></div>
                </div>
                <div class="bg-center">
                    MISIÓN <span class="numero mision"> ${mision.cantidad} </span>
                    <div>
                        <!--<img src="<?= $this->Url->build('/numerologia/mision_b.png', ['fullBase' => true]) ?>" style="width:100px" />-->
                    </div>
                    <div class="px-2">
                        ${mision.informacion != null ? mision.informacion.info_basica : ''}
                    </div>
                </div>
                <div class="col d-flex align-items-center px-0">
                    <div class="back w-100" style="height:5px;"></div>
                </div>
            </div>

            <div class="back row">
                <div class="col-6 pl-0 pr-1">
                    <div class="numeros py-4">
                        <div class="text-center pb-3 font-weight-bold"> 
                            KARMA <span class="numero karma"> ${karma.cantidad}</span> 
                            <div>
                                <!--<img src="<?= $this->Url->build('/numerologia/karma.png', ['fullBase' => true]) ?>" style="width:113px" />-->
                            </div>
                        </div>
                        <div class="row contenido-90">
                            <div class="col-6">
                                <div class="font-weight-bold">Vibración Positiva:</div>
                                ${karma.positiva != null ? karma.positiva.info_basica : ''}
                            </div>
                            <div class="col-6">
                                <div class="font-weight-bold">Vibración Negativa:</div>
                                ${karma.negativa != null ? karma.negativa.info_basica : ''}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-1 pr-0">
                    <div class="numeros py-4">
                        <div class="text-center pb-3 font-weight-bold">
                            DESTINO <span class="numero destino"> ${destino.cantidad}</span>
                            <div>
                                <!--<img src="<?= $this->Url->build('/numerologia/destino.png', ['fullBase' => true]) ?>" style="width:100px" />-->
                            </div>
                        </div>
                        <div class="contenido">
                            ${destino.informacion != null ? destino.informacion.info_basica : ''}
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
            `;


            $(`#${reporteNumerologicoPersonas.IdModal} #resultado_numerologico`).html(html);
            reporteNumerologicoPersonas.mostrar();
        }
    }
</script>

<style>
    .contenedor{
    width:700px;
    margin: auto;
}
.img-tipo{
    width:30%;
    margin:auto
}
.numero{
    color: white;
    padding-left: 10px ;
    padding-right: 10px ;
    border-radius: 10px;
}
.alma{background-color: #40e0d0;}
.karma{background-color: #dc143c;}
.destino{background-color: #7fff00;}
.talentos{background-color: #6a5acd;}
.mision{background-color: #ff8c00;}

.title{
    text-align: center;
    font-weight: bold;
    font-size: 2em;
    margin-top: -1.5em;
}
.img-logo{
    width:3em;
    display: block;
    position: relative;
    left: 0;
}
hr{
    border-top: 2px solid #00CFF2;
}
.div-name{
    margin: auto;
    width: 200px;
    background-color: #0A67B1 ;
    color: white;
    border-radius: 5px;
}
.text-bold{
    font-weight: bold;
}
.back{
    background-color: #0A67B1;
    font-size: 12px;
}
.numeros{
    background-color: white;
    padding-left: 5px;
    padding-right: 5px;
    height: 100%;
}
.contenido{
    width: 80%;
    margin: auto;
}
.contenido-90{
    width: 90%;
    margin: auto;
}
.bg-white{
    background-color: white;
}
.bg-center{
    font-size: 12px;
    text-align: center;
    color: white;
    background-color: #0A67B1;
    max-width: 300px;
    padding: 5px;
    height: 100%;
    border-radius: 10px ;
}
.pl-1{
    padding-left: 2.5px !important ;
}
.pr-1{
    padding-right: 2.5px !important ;
}
</style>