<div class="container">
    <div class="px-2">
        <h4> <i class="fa fa-fw fa-tasks"></i> Listado de Tareas Programadas</h4>
    </div>
    <div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>
                            Host
                        </th>
                        <th>
                            Cliente
                        </th>
                        <th>
                            Frecuencia
                        </th>
                        <th>
                            Estado
                        </th>
                        <th>
                            Acc
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($tareas_programadas) : ?>
                        <?php foreach ($tareas_programadas as $tp) : ?>
                            <tr>
                                <td style="max-width: 200px;">
                                    <a href="<?= $tp->host ?>" target="_blank" >
                                        <?= $tp->host; ?>
                                    </a>
                                </td>
                                <td style="max-width: 200px;">
                                    <?php if ($tp->cliente) : ?>
                                        <i class="fa fa-fw fa-user" ></i> <?= $tp->cliente->razon_social ?>
                                    <?php else : ?>
                                        <div class="q-badge q-badge-sm q-badge-dark" >
                                            Sin datos
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $tp->tprogramada_frecuencia_tipo ?>
                                </td>
                                <td>
                                    <select  data-id="<?=$tp->id?>"  class="td__estado form-control form-control-sm  <?= $tp->estado == 'ACTIVO' ? 'td_estado_act' : 'td_estado_inact' ?>" >
                                        <option <?= $tp->estado == 'INACTIVO' ? 'selected' : '' ?> value="INACTIVO">Inactivo</option>
                                        <option <?= $tp->estado == 'ACTIVO' ? 'selected' : '' ?> value="ACTIVO">Activo</option>
                                    </select>
                                    <!-- Aqui debo de poner la opción de cambiar estado -->
                                </td>
                                <td>    
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Acciones
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="<?= $this->Url->build(['action' => 'delete', $tp->id]) ?>" class="dropdown-item" > <i class="fa fa-fw fa-trash text-danger" ></i> Eliminar </a>
                                            </div>
                                        </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5">
                                <i class="fa fa-fw fa-exclamation-triangle"></i> No se han encontrado registros
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>



            </table>
        </div>
    </div>





</div>