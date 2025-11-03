<div>
    <?= $this->Form->create(null, ['method' => 'GET']);
    $this->Form->setTemplates(['inputContainer' => '{{content}}']) ?>
    <div class="row">
        <div class="col-md-12 col-lg mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <input type="text" class="form-control form-control-sm" placeholder="Por Ruc" name="opt_ruc" autocomplete="off"
                           value="<?= $opt_ruc ?>" aria-label="Recipient's username" aria-describedby="basic-addon2">
                </div>
                <input type="text" class="form-control form-control-sm" placeholder="Por nombre" name="nom" autocomplete="off"
                       value="<?= $nom ?>" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-sm btn-outline-primary" type="submit">Buscar</button>
                </div>
            </div>
        </div>
        <div class="col" style="min-width: 240px;max-width: 240px;">
            <button type="submit" name="exportar" value="1" class="btn btn-sm btn-primary"><i
                    class="fas fa-file-excel fa-fw"></i> Clientes
            </button>
            <button type="submit" name="exportar" value="2" class="btn btn-sm btn-primary"><i
                    class="fas fa-file-excel fa-fw"></i> Contactos
            </button>
        </div>
    </div>
    <br>
    <?= $this->Form->end(); ?>
    <div class="table-responsive gw-table">
        <table class="table table-striped table-sm"  >
            <thead >
            <tr>
                <th>

                    <input type="checkbox" class="sel_all"/>
                    <?= $this->Paginator->sort('nombre_comercial') ?>
                </th>
                <th>Contactos Asociados</th>
                <th>Info. Contacto</th>
                <th><?= $this->Paginator->sort('created', ['label' => 'Fec. Registro']) ?></th>
                <th class="actions">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cuentas as $cliente): ?>
                <tr>
                    <td>
                        <label>
                            <input form='form1' name="ides[<?= $cliente->id ?>]" value="<?= $cliente->id ?>" class="sel" type='checkbox'/>
                            <?= str_pad($cliente->ruc, 7, 0, 0) ?>
                        </label><br/>
                        <?php
                        echo "<i class='fa fa-building fa-fw'></i> {$cliente->razon_social}<br/>";
                        if (strlen($cliente->nombre_comercial ?? "") > 1) {
                            echo "<i class='fa fa-user fa-fw'></i> {$cliente->nombre_comercial}<br/>";
                        }

                        ?>
                    </td>

                    <td>
                        <?php
                        if ($cliente->personas) :
                            foreach ($cliente->personas as $c) :
                                ?>
                                <a style="text-decoration: none; color:black; overflow: paged-y" href="#"
                                   onclick="getContacto(<?= $c->id ?>,<?= $cliente->id?>)">
                                   <i class='far fa-address-card'></i> <?= $c->nombres ?> <?= $c->apellidos?>
                                </a>
                            <?php
                            endforeach;
                        endif;
                        ?>

                        <a href="javascript:FormPersona.NuevoParaCuenta(<?= $cliente->id ?>)"><i class='fa fa-plus fa-fw'></i> Contacto</a>
                    </td>

                    <td>
                        <?php if($cliente->telefono != ''): ?>
                            <i class="fas fa-phone fa-fw"></i><?= $cliente->telefono?><br/>
                        <?php endif; ?>
                        <?php if($cliente->celular != ''): ?>
                            <i class="fas fa-mobile fa-fw"></i><?= $cliente->celular?><br/>
                        <?php endif; ?>
                        <?php if($cliente->whatsapp != ''): ?>
                            <i class="fab fa-whatsapp fa-fw"></i><?= $cliente->whatsapp?><br/>
                        <?php endif; ?>
                        <?php if($cliente->correo != ''): ?>
                            <i class="fas fa-envelope fa-fw"></i><?= $cliente->correo?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <i class="fas fa-calendar fa-fw"></i><?= h($cliente->created ? $cliente->created->format("d/m/Y") : "") ?><br/>
                        <i class="fas fa-clock fa-fw"></i><?= h($cliente->created ? $cliente->created->format("H:i:s") : "") ?>
                    </td>
                    <td class="actions">

                        <div class="dropdown">
                            <button id="dLabel" type="button" class=" btn btn-primary btn-sm dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dLabel">
                                <?php

                                echo "<li role='presentation' class='divider'></li>";

                                echo "<li>";
                                echo $this->Html->link("<i class='fa fa-edit fa-fw'></i> Editar", ['action' => 'edit', $cliente->id], ['class' => 'dropdown-item', 'escape' => false]);
                                echo "</li>";

                                echo "<li>";
                                echo $this->Html->link("<i class='fa fa-eye fa-fw'></i> Ver Contactos", ['action' => 'contactos', $cliente->id], ['class' => 'dropdown-item', 'escape' => false]);
                                echo "</li>";

                                echo "<li>";
                                echo $this->Form->postLink("<i class='fa fa-trash fa-fw'></i> Eliminar", ['action' => 'delete', $cliente->id], ['confirm' => __('¿Está seguro que quiere eliminar {0}?', $cliente->nombre), 'class' => 'dropdown-item', 'escape' => false]);
                                echo "</li>";

                                ?>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <button class="btn btn-danger btn-sm" onclick="borrarSeleccionados()">
        <i class="fas fa-trash"></i>
        Borrar seleccionados
    </button>


    <!-- Fusionar cuentas -->

    <button class="btn btn-primary btn-sm" onclick="indexCuentas.fusionarCuentas()">
        <i class="fas fa-exchange-alt fa-fw"></i>
        Fusionar Cuentas
    </button>


    <!----------------------->

    <?= $this->element("paginador"); ?>
</div>

<div class="modal" tabindex="-1" role="dialog" id="viewModal">
    <div class="modal-dialog " role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Información de Contacto

                    <i onclick="editContacto()"    class="fas fa-pencil-alt"></i>

                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">DNI:</label>
                            <b id="dni_"></b>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Cargo en la Empresa:</label>
                            <b id="cargo_"></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Nombres:</label>
                            <b id="nombre_"></b>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Apellidos:</label>
                            <b id="apellido_"></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label class="control-label">Correo:</label>
                            <b id="correo_"></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label class="control-label">Dirección:</label>
                            <b id="domicilio_"></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Teléfono:</label>
                            <b id="tel_"></b>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Whatsapp:</label>
                            <b id="whatsapp_"></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Cel.Trabajo:</label>
                            <b id="cel2_"></b>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Cel.Personal:</label>
                            <b id="cel_"></b>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <input type="hidden" name="id"/>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="contacto_id" value=""/>
<?php
echo $this->Element('form_persona');
echo $this->Element("empresas/modal_fusionar_empresas");

echo $this->Html->ScriptBlock("var _cuentas =" . json_encode($cuentas) . ";");
