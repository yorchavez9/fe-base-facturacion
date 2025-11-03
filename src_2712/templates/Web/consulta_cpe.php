
    <div class="row d-flex flex-row py-5" style="width:90vw;margin:auto;max-width: 900px;">

        <div class="col-12 col-md-6">
            <div class="row">
                <div class="col-12 text-center">
                    <img src="<?= $this->Url->build("/{$logo}") ?>" class="img img-fluid float-left" style="max-height: 80px;" />
                </div>
            </div>
            <br>
            <form id="formConsultaCpe" onsubmit="ConsultaCpe.consultarComprobante(event)">
                <!--<div class="form-group">
                    <label for="ruc">RUC</label>
                    <input class="form-control" id="ruc" placeholder="Número de RUC del emisor" autocomplete="off" required>
                </div>-->
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <select class="form-control" id="tipo" name="tipo_documento" autocomplete="off" required>
                        <option value="01">Factura</option>
                        <option value="03">Boleta</option>
                        <!--<option value="06">Nota de Crédito</option>
                        <option value="07" >Nota de Debito</option>-->
                      </select>
                </div>
                <div class="row">
                    <div class="col-6 form-group">
                        <label for="tipo">Serie</label>
                        <input class="form-control" id="serie" name="serie" placeholder="Serie" autocomplete="off" required>
                    </div>
                    <div class="col-6  form-group">
                        <label for="tipo">Correlativo</label>
                        <input class="form-control" id="numero" name="correlativo" placeholder="Correlativo" type="number" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tipo">Importe Total</label>
                    <input class="form-control" id="total" name="total" step="0.01" placeholder="Total" type="number" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block btn-gfast" id="btnConsultaCpe" >
                        <i id="iconConsultaCpe" class="fa fa-globe fa-fw"></i>  Consultar
                    </button>
                </div>
                <div class="form-group">
                    <span id="resultado_consulta"></span>
                </div>
            </form>
            <br>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning">
                        SUGERENCIAS: <br>
                        #1 .- En el caso de las boletas, se recomienda consultarlas un día después de emitida
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6" style="font-size: 13px;">
            <div class="border px-4 py-4"  style="border-radius: 5%;">
                <div class="text-center pb-2">
                    <div class="font-weight-bold" style="font-size: 14px;">EMPRESA S.A.C</div>
                    <div class="font-weight-bold" style="font-size: 14px;">RUC: <span id="ejm_ruc_emisor"> 12345678901 </span></div>
                    <div>Direccion - Perú</div>
                    <div class="font-weight-bold" style="font-size: 14px;" id="ejm_tipo_comprobante">FACTURA ELECTRÓNICA</div>
                    <div class="font-weight-bold" style="font-size: 14px;">
                        <span id="ejm_serie">F001</span>-00000<span id="ejm_numero">254</span>
                    </div>
                </div>

                <div> <label style="min-width:70px" class="my-0 font-weight-bold">Cliente </label> :    <span class="pl-2">CLIENTE E.I.R.L  </span></div>
                <div> <label style="min-width:70px" class="my-0 font-weight-bold">RUC </label>  :       <span class="pl-2">98765432101  </span></div>
                <div> <label style="min-width:70px" class="my-0 font-weight-bold">FECHA </label>  :     <span class="pl-2">04/01/2022 17:40:36  </span></div>
                <div> <label style="min-width:70px" class="my-0 font-weight-bold">F. VENC.</label>  :   <span class="pl-2">04/01/2022  </span></div>
                <div> <label style="min-width:70px" class="my-0 font-weight-bold">F. PAGO</label>  :    <span class="pl-2">CONTADO </span></div>
                <div class="my-2">
                    <table class="table table-sm" >
                        <tr>
                            <th>
                                Código.
                            </th>
                            <th>
                                Descripción
                            </th>
                            <th class="text-center">
                                Cantidad
                            </th>
                            <th class="text-right">
                                P. Unit.
                            </th>
                            <th class="text-right">
                                Importe
                            </th>
                        </tr>
                        <tr>
                            <td>
                                27
                            </td>
                            <td colspan="4">
                                Producto - 6304
                            </td>
                        </tr>
                        <tr style="border: none !important;">
                            <td style="border: none !important;"></td>
                            <td style="border: none !important;"></td>
                            <td style="border: none !important;" class="text-center">
                                2
                            </td>
                            <td style="border: none !important;" class="text-right">
                                100.00
                            </td>
                            <td style="border: none !important;" class="text-right">
                                200.00
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="px-1">
                    <div class="d-flex justify-content-between">
                        <div>OP. GRAVADAS</div>
                        <div class="d-flex">
                            <div>PEN</div>
                            <div class="text-right-padding">164.00</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>OP. GRATUITAS</div>
                        <div class="d-flex">
                            <div>PEN</div>
                            <div class="text-right-padding">00.00</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>OP. EXONERADAS</div>
                        <div class="d-flex">
                            <div>PEN</div>
                            <div class="text-right-padding">00.00</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>OP. INAFECTADAS</div>
                        <div class="d-flex">
                            <div>PEN</div>
                            <div class="text-right-padding">00.00</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>I.G.V</div>
                        <div class="d-flex">
                            <div>PEN</div>
                            <div class="text-right-padding">36.00</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>SUB TOTAL</div>
                        <div class="d-flex">
                            <div>PEN</div>
                            <div class="text-right-padding">164.00</div>
                        </div>
                    </div>
                    <div class="d-flex font-weight-bold justify-content-between" >
                        <div>TOTAL VENTA</div>
                        <div class="d-flex">
                            <div>PEN</div>
                            <div class="text-right-padding"><span id="ejm_total">200.00 </span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


