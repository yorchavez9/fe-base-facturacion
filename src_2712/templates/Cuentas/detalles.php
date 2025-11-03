<!---------nombre comercial---------->
<div class="contenedor800px">
  <div class="panel">

    <div class="panel-heading">
        <div class="panel-title">
            <h3>
                  <?= $this->Html->Link("<i class='fa fa-chevron-left fa-fw'></i>", ['controller' => 'Clientes', 'action' => 'index'],['escape' => false]);?>
                Detalles del cliente : <?= $cliente->nombre_comercial ?></h3>

        </div>
    </div>

    <div class="panel panel-default">

        <div class="panel-body">
              <div class="row">
                  <div class="col-xs-6 col-md-10">
                      <label> RUC:  </label> <?= $cliente->ruc ?>
                  </div>
                  <div class="col-xs-6 col-md-10">
                      <label> Raz&oacute;n Social:  </label> <?= $cliente->razon_social ?>
                  </div>
                  <div class="col-xs-12 col-md-10">
                      <label>Domicilio Fiscal:  &nbsp; </label><?= $cliente->domicilio_fiscal ?>
                  </div>
              </div>
        </div>
<!-------------fin nombre comercial-------------->
<!-------------contactos-------------->
        <div class="panel-heading">
            <h4 class="panel-title">
                  <a data-toggle="collapse" href="#contactos">Contactos de <?= $cliente->razon_social ?> </a>
            </h4>
        </div>
      <div id="contactos" class="panel-collapse collapse">
        <div class="panel-body">
              <table cellpadding="0" cellspacing="0" class="table table-hover">
                <thead>
                        <tr>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Cargo</th>
                            <th>Telefono</th>
                            <th>Celular</th>
                            <th>Correo</th>
                        </tr>
                </thead>
                <tbody>
                    <?php foreach ($contactos_del_cliente as $c): ?>
                    <tr>
                        <td><?= $c->nombres?></td>
                        <td><?= $c->apellidos?></td>
                        <td><?=$c->cargo_empresa?></td>
                        <td><?=$c->telefono?></td>
                        <td><?=$c->celular1?><br>
                        <?=$c->celular2?><br>
                        <?=$c->celular3?></td>
                        <td><?=$c->correo?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
              </table>
       </div>
      </div>
<!-------------fin contactos-------------->
<!-------------costos eventos-------------->

          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" href="#eventos">Eventos de <?= $cliente->razon_social ?> </a>
            </h4>
          </div>

      <div id="eventos" class="panel-collapse collapse">
          <div class="panel-body">
            <div class="table-responsive">

              <table cellpadding="0" cellspacing="0" class="table table-hover">
                <thead>
                      <tr>
                        <th>Comentario</th>
                        <th>Usuario</th>
                        <th>Cont&aacute;cto</th>
                        <th>Proyecto</th>
                        <th>Proforma</th>
                        <th width="15%">Fecha</th>
                      </tr>
                </thead>

                <tbody>
                  <?php foreach ($eventos_del_cliente as $c): ?>
                    <tr>
                        <td>
                          <a href="#" onclick='verResumenEvento(<?= json_encode($c)?>)'>
                            <i class='fa fa-search fa-fw'></i>Ver Más
                          </a>
                        </td>
                        <td> <?= $c->cliente_nombre ?> </td>
                        <td> <?= $c->cliente_nombre?> </td>
                        <td> <?= $c->proyecto_nombre?> </td>
                        <td> <?= $c->cotizacion_nombre?> </td>
                        <td> <?=$c->created->format("d/m/Y")?></td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>

              </table>

                </div>
          </div>
      </div>
 <!-----------fin eventos-------------->
<!-------------proceso inportacion-------------->
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" href="#importaciones">Proyectos de <?= $cliente->razon_social ?></a>
        </h4>
      </div>

      <div id="importaciones" class="panel-collapse collapse">
        <div class="panel-body">
                   <table cellpadding="0" cellspacing="0" class="table table-hover">
            <thead>
              <tr>
                  <th>Nombre del proyecto</th>
                  <th>Direcci&oacute;n</th>
                  <th width="60%">Proformas</th></tr>
            </thead>

          <tbody>
            <?php foreach ($proyectos_del_cliente as $c): ?>
              <tr>
                <td> <?=$c->nombre ?> </td>
                <td> <?=$c->direccion ?></td>
                <td>

                  <ul>
                    <?php foreach ($c->cotizaciones as $coti): ?>
                    <li><?= $this->Html->Link($coti->codigo, ['controller' => 'Cotizaciones', 'action' => 'detalles', $coti->id],['target' => '_blank']) ?> ($<?= $coti->precio_venta?> ) </li>
                    <?php endforeach; ?>
                  </ul>

                </td>

              </tr>
             <?php endforeach; ?>

          </tbody>

                 </table>
        </div>
      </div>
<!-------------fin proceso inportacion-------------->

</div>
</div>



<div class="modal" tabindex="-1" role="dialog" id="modalEventoCliente">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Resumen del Evento</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
