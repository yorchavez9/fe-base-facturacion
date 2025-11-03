<tr class="<?= ($v->total_deuda > 0) ? "warning" : "" ?>"
    style="color:
    <?php
        if($v->estado == 'ANULADO' || $v->estado == 'ANULACION_NC' || $v->estado == 'ANULACION_NB') {
            echo "#f25a5a";
        }elseif($v->estado == "PORANULAR"){
            echo "#ff9500";
        }
    ?>"
>
    <td>
        <i class="fas fa-calendar-alt fa-fw"></i> <?= $v->fecha_venta->format("d/m/Y") ?><br />
        <i class="fas fa-clock fa-fw"></i> <?= $v->fecha_venta->format("H:i:s") ?><br />
        <small>
            <i class="fas fa-id-badge fa-fw"></i>
            <?= str_pad($v->id, 7, '0', STR_PAD_LEFT)?>
        </small>
    </td>
    <td>
        DNI/RUC: <?= $v->cliente_doc_numero ?><br />
        <?= $v->cliente_razon_social ?> <br>
    </td>
    <!-- <td>
        <?= $opt_documento_tipos[$v->documento_tipo] ?? "" ?><br />
        <?= $this->Html->Link("{$v->documento_serie}-{$v->documento_correlativo}", ['action' => 'detalles', $v->id]) ?>
    </td> -->
    <td>
        Subtotal<br />
        IGV<br />
        Total
    </td>

    <td class="text-right" style="width: 180px;">
        <div class="d-flex">
            <div class="col">
                <?= $this->Number->format($v->subtotal, ['places' => 2, 'precision' => 2]) ?> <?= $v->tipo_moneda ?><br />
                <?= $this->Number->format($v->igv_monto, ['places' => 2, 'precision' => 2]) ?> <?= $v->tipo_moneda ?><br />
                <?= $this->Number->format($v->total, ['places' => 2, 'precision' => 2]) ?> <?= $v->tipo_moneda ?>
            </div>
            <div class="bg-estado-pago" style="background-color: <?= $v->total_deuda == 0 ?  '#ABEBC6' : '#F5B7B1' ?>" data-toggle="tooltip" data-placement="top"
                title=" <?= $v->total_deuda == 0 ?  'PAGADO' : 'DEUDA' ?>">
            </div>
        </div>
    </td>
    <?php if (!isset($show_actions) || (isset($show_actions) && $show_actions)) : ?>
        <td>
            <div class="dropdown">
                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acciones
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <!-- <a style="color: black;" class="dropdown-item" href="#" onclick='ModalDescargarEnviar.AbrirDesdeLink(event, this)' data-cpe-id="<?= $v->id ?>" data-cpe-tipo="<?= $v->documento_tipo ?>">
                        <i class="fas fa-download fa-fw"></i> Descargar y Enviar
                        <i class="fab fa-whatsapp fa-fw"></i> ó <i class="fas fa-envelope fa-fw"></i>
                    </a> -->
                    <!-- <a style="color: black;" class="dropdown-item" href="<?= $flag_dev ? 'http://localhost/fe-api/sunat-fe-facturas/pdf-a4/' : 'https://demo.profecode.com/fe-api/pdf-a4/' ?><?= $v->id?>" target="_blank" >
                        <i class="fas fa-print fa-fw"></i> PDF A4
                    </a>
                    <a style="color: black;" class="dropdown-item" href="<?= $flag_dev ? 'http://localhost/fe-api/sunat-fe-facturas/pdf-ticket/' : 'https://demo.profecode.com/fe-api/pdf-ticket/' ?><?= $v->id?>" target="_blank" >
                        <i class="fas fa-print fa-fw"></i> PDF Ticket
                    </a> -->
                    <!-- <div class="dropdown-divider"></div> -->
                    <?php

                    echo $this->Html->Link("<i class='fa fa-search fa-fw'></i> Detalles", ['action' => 'detalles', $v->id], ['class' => 'dropdown-item', 'escape' => FALSE]);
                    echo $this->Html->Link("<i class='fa fa-coins fa-fw'></i> Ver pagos", ['controller' => 'ventaPagos', 'action' =>  'index', $v->id], ['class'    =>  'dropdown-item', 'escape'    =>  FALSE]);
                    if($v->estado != 'ANULADO'){
                        echo "<a href='#' class='dropdown-item' onclick='FilaVentas.anularNotaVenta(\"{$v->id}\")'><i class='fas fa-trash fa-fw'></i> Anular Venta</a>";
                    }
                    ?>
                </div>
            </div>
        </td>
    <?php endif; ?>
</tr>


<style>
    .verificado {
        color: #069068;
        text-decoration: none !important;
    }

    .no-verificado {
        color: #6c757d;
        text-decoration: none !important;
    }

    .aceptado {
        color: #33FF33;
    }

    .no-aceptado {
        color: #AD2001;
    }

    .descarga-celular {
        display: none
    }
    .bg-estado-pago{
        width: 10px;
        border-radius: 2.5px;
        text-align: left !important;
    }
    @media screen and (max-width : 500px) {
        .descarga-celular {
            display: block;
        }
    }
</style>



<script>
    var FilaVentas = {
        imprimirFactura: function(venta_id, formato) {

            $.ajax({
                url: base + `sunat-fe-facturas/api-imprimir-pdf/${venta_id}/${formato}`,
                data: '',
                type: 'GET',

                success: function(data, status, xhr) { // success callback function
                    console.log(data);
                    if (data.success) {

                        printJS({
                            printable: data.data,
                            type: 'pdf',
                            base64: true,
                            showModal: true,
                            modalMessage: 'Cargando Documento'
                        });

                    } else {
                        alert(data.message);
                    }

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });


        },
        imprimirNotaVenta: function(venta_id, formato) {

            $.ajax({
                url: base + `ventas/api-imprimir-pdf/${venta_id}/${formato}`,
                data: '',
                type: 'GET',

                success: function(data, status, xhr) { // success callback function
                    console.log(data);
                    if (data.success) {
                        printJS({
                            printable: data.data,
                            type: 'pdf',
                            base64: true,
                            showModal: true,
                            modalMessage: 'Cargando Documento'
                        });

                    } else {
                        alert(data.message);
                    }

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });


        },
        anularNotaVenta: function(venta_id) {
            var r = prompt("Por favor, ingrese el motivo de la anulación");
            if (r == null) {
                return false;
            }

            $.ajax({
                url: base + "ventas/api-anular-nota-venta",
                data: {
                    venta_id: venta_id,
                    motivo: r
                },
                type: 'post',

                success: function(data, status, xhr) { // success callback function
                    console.log(data);
                    if (data.success) {
                        location.reload()
                    } else {
                        alert(data.message);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },
        consultarValidezCpe: function(element) {

            // TODO poner animacion de "enviando"
            var factura_id = $(element).attr("data-comprobante-id");
            var link_icono_class = $(element).attr("data-icon-tipo") === '1' ? 'fa-check' : 'fa-times';
            var link_icono = $(element).find("i")[0];


            FilaVentas.setLinkLoad(link_icono, element, link_icono_class);


            var url = `${base}sunat-fe-facturas/api-consulta-validez-cpe/${factura_id}`;
            $.ajax({
                url: url,
                data: '',
                type: 'POST',
                success: function(data, status, xhr) {

                    alert(data.message);

                    FilaVentas.stopLinkLoad(link_icono, element, link_icono_class);
                    location.reload();

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);

                    FilaVentas.stopLinkLoad(link_icono, element, link_icono_class);

                }

            });
        },

        enviarFacturaSunat: function(element) {

            // TODO poner animacion de "enviando"
            var factura_id = $(element).attr("data-factura-id");

            var link_icono = $(element).find("i")[0];
            FilaVentas.setLinkLoad(link_icono, element, "fa-upload");


            var url = `${base}sunat-fe-facturas/enviar-sunat/`;
            $.ajax({
                url: url,
                data: {
                    factura_id: factura_id
                },
                type: 'POST',
                success: function(data, status, xhr) {
                    if (data.success) {
                        $("#message").html(data.data)
                        setTimeout(function() {
                            location.reload();
                        }, 1500)

                    } else {
                        alert(data.data.codigo);
                        alert(data.data.mensaje);
                    }
                    FilaVentas.stopLinkLoad(link_icono, element, "fa-upload");

                    //location.href = `${base}/ventas/procesar-venta/${venta_id}?procesado=1`;
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);

                    FilaVentas.stopLinkLoad(link_icono, element, "fa-upload");

                }

            });
        },
        cambiarEstadoComprobante: function(comprobante_id) {
            var estado = prompt("Por favor, ingrese el nuevo estado");
            if (estado == null || estado == '') {
                return false;
            }

            $.ajax({
                url: base + `ventas/cambiar-estado-venta/${comprobante_id}`,
                data: {
                    nuevo_estado: estado
                },
                type: 'post',

                success: function(data, status, xhr) { // success callback function

                    if (data.success) {
                        location.reload()
                    } else {
                        alert(data.message);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });


        },
        enviarFacturaSoporte: function(element) {
            var venta_id = $(element).attr("data-venta-id");

            var link_icono = $(element).find("i")[0];
            FilaVentas.setLinkLoad(link_icono, element, "fa-upload");

            var url = `${base}sunat-fe-facturas/api-comunicar-soporte-error-comprobante`;
            console.log(url);
            $.ajax({
                url: url,
                data: {
                    venta_id: venta_id
                },
                type: 'POST',
                success: function(data, status, xhr) {
                    console.log(data)
                    if (data.success) {
                        alert(data.message);

                    } else {
                        alert(data.message);
                    }
                    FilaVentas.stopLinkLoad(link_icono, element, "fa-upload");

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);

                    FilaVentas.stopLinkLoad(link_icono, element, "fa-upload");

                }

            });
        },
        setLinkLoad: function(element, element_parent, class_to_replace) {
            $(element).removeClass(`${class_to_replace}`);
            $(element).addClass('spinner-border');
            $(element).addClass('spinner-border-sm');
            $(element_parent).css("pointer-events", "none");
        },
        stopLinkLoad: function(element, element_parent, class_to_set) {
            $(element).removeClass('spinner-border');
            $(element).removeClass('spinner-border-sm');
            $(element).addClass(`${class_to_set}`);
            $(element_parent).css("pointer-events", "auto");
        }





    }
</script>
