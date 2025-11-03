<div class="modal fade" id="modalDocumentacionSoporte" tab-index="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentacion_title">
                    Documentación
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="content">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="ModalDocumentacionSoporte.openModalSoporte()" class="btn btn-sm btn-outline-primary">
                    Obtener soporte personalizado
                </button>
                <button type="button" class="btn btn-sm btn-outline-info" data-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var ModalDocumentacionSoporte = {
        idModal: "modalDocumentacionSoporte",
        codigo_doc: '000',
        endpoint: `${base}configuraciones/consultar-documentacion-soporte`,
        documentacion: {},
        abrir: (codigo) => {
            ModalDocumentacionSoporte.clean();
            ModalDocumentacionSoporte.open();
            GlobalSpinner.mostrar();
            GlobalSpinner.setText('Cargando documentacion');
            ModalDocumentacionSoporte.codigo_doc = codigo;
            ModalDocumentacionSoporte.fetchDocumentation();
        },
        open: () => {
            $(`#${ModalDocumentacionSoporte.idModal}`).modal("show");
        },
        close: () => {
            $(`#${ModalDocumentacionSoporte.idModal}`).modal("hide");
        },
        clean: () => {
            ModalDocumentacionSoporte.setHtmlField('content', '');
            ModalDocumentacionSoporte.setHtmlField("documentacion_title", 'Documentación');
        },
        setField: (campo_id, valor) => {
            $(`#${ModalDocumentacionSoporte.idModal} #${campo_id}`).val(valor);
        },
        setHtmlField: (campo_id, valor) => {
            $(`#${ModalDocumentacionSoporte.idModal} #${campo_id}`).html(valor);
        },
        fetchDocumentation: () => {

            $.ajax({
                url: `${ModalDocumentacionSoporte.endpoint}/${ModalDocumentacionSoporte.codigo_doc}`,
                type: 'GET',
                data: '',
                success: function(data) {
                    console.log(data);
                    ModalDocumentacionSoporte.documentacion = data.data;
                    ModalDocumentacionSoporte.fillContent();
                    GlobalSpinner.ocultar();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert('Ha habido un error para obtener la documentacion');
                    console.log(xhr.status);
                    console.log(thrownError);
                    GlobalSpinner.ocultar();
                }
            })


        },
        /**
         * titulo , descripcion, contenido, url_yshortcode, codigo, isValid
         * el url_shortcode debe ser de youtube
         * 
         */
        fillContent: () => {
            var html = "";
            if ([null, undefined].indexOf(ModalDocumentacionSoporte.documentacion) != -1) {
                html = `
                <h6>
                    <i class="fa fa-triangle-warning fa-fw" ></i> No se han encontrado datos de la documentacion solicitada
                </h5>
                `
            } else {
                ModalDocumentacionSoporte.setHtmlTitle()
                html =
                    `
                    <div class="row" >
                ${ModalDocumentacionSoporte.getHtmlVideo()}
                ${ModalDocumentacionSoporte.getHtmlContent()}
                    </div>
                
                `;

            }


            ModalDocumentacionSoporte.setHtmlField('content', html);

        },
        setHtmlTitle: () => {
            var html = "";
            html =
                `
                <i class="fa fa-keyboard fa-fw" ></i> ${ModalDocumentacionSoporte.documentacion.title}
                
            
            `;

            ModalDocumentacionSoporte.setHtmlField("documentacion_title", html);
        },
        getHtmlVideo: () => {
            var html = "";
            html =
                `
                <div class="col-md-12 mb-4" >
                    <div class="text-center" >
                        <div class="embed-responsive embed-responsive-21by9">
                        <iframe class="embed-responsive-item" src="${ModalDocumentacionSoporte.documentacion.url_yshortcode}"></iframe>
                        </div>
                    </div>
                </div>
            
            `;

            return html;

        },
        getHtmlContent: (descripcion) => {
            var html = "";
            html =
                `
                <div class="col-md-12 mb-2" >
                    <h5>Descripcion</h5>
                    <div class="doc_description">
                        ${ModalDocumentacionSoporte.documentacion.content}
                    </div>
                </div>
            `;

            return html;
        },
        openModalSoporte: () => {
            ModalDocumentacionSoporte.close();
            GlobalModalSoporte.abrir()
        }

    }
</script>


<style>
    .doc_description {
        max-height: 30vh;
        overflow-y: scroll;
        width: inherit;
    }
</style>