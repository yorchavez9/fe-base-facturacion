<ul class="list-unstyled gw-navleft">
    <li>
        <a href="<?= $this->Url->Build(['controller' => 'Reportes', 'action' => 'reportesGenerales']) ?>">
            <i class="fas fa-chart-line fa-fw"></i> Inicio
        </a>
    </li>
    <li>
        <a href="<?= $this->Url->Build(['controller' => 'Cuentas', 'action' => 'index']) ?>">
            <i class="fas fa-users fa-fw"></i> <span class="menu-title">Clientes</span>
        </a>
    </li>
    <li>
        <a href="<?= $this->Url->Build(['controller' => 'Cotizaciones', 'action' => 'index']) ?>">
            <i class="fas fa-file-alt fa-fw"></i> Proformas
        </a>
    </li>
    <li>
        <a href="<?= $this->Url->Build(['controller' => 'Ventas', 'action' => 'index']) ?>">
            <i class="fas fa-dollar-sign fa-fw"></i> Ventas
        </a>
    </li>

    <li>
        <a href="#menuItems" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <i class="fas fa-shopping-cart fa-fw"></i><span class="menu-title"> Productos</span>
        </a>
        <ul class="collapse list-unstyled" id="menuItems">
            <li>
                <a href="<?= $this->Url->Build(['controller' => 'Items', 'action' => 'index']) ?>">
                    <i class="fas fa-shopping-cart fa-fw"></i> Productos
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->Build(['controller' => 'Items', 'action' => 'servicios']) ?>">
                    <i class="fas fa-user-secret fa-fw"></i> Servicios
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->Build(['controller' => 'ItemCategorias', 'action' => 'index']) ?>">
                    <i class="fas fa-table fa-fw"></i> Categorías
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->Build(['controller' => 'ItemMarcas', 'action' => 'index']) ?>">
                    <i class="fas fa-table fa-fw"></i> Marcas
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="#menuFacturacion" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <i class="fas fa-shopping-cart fa-fw"></i><span class="menu-title"> Facturación </span>
        </a>
        <ul class="collapse list-unstyled" id="menuFacturacion">
            <li>
                <a href="<?= $this->Url->Build(['controller' => 'SunatFeFacturas', 'action' => 'index']) ?>">
                    <i class="fas fa-cubes fa-fw"></i> Facturas
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->Build(['controller' => 'ResumenDiarios', 'action' => 'index']) ?>">
                    <i class="fas fa-cubes fa-fw"></i> Resumenes Diarios
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->Build(['controller' => 'SunatFeNotasCredito', 'action' => 'index']) ?>">
                    <i class="fas fa-cubes fa-fw"></i> Notas de Credito
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->Build(['controller' => 'SunatFeNotasDebito', 'action' => 'index']) ?>">
                    <i class="fas fa-cubes fa-fw"></i> Notas de Debito
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->Build(['controller' => 'SunatFeComunicacionBajas', 'action' => 'index']) ?>">
                    <i class="fas fa-cubes fa-fw"></i> Comunicacion Bajas
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->Build(['controller' => 'guia-rem-remitentes', 'action' => 'index']) ?>">
                    <i class="fas fa-cubes fa-fw"></i> Guias de Remision
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->Build(['controller' => 'guia-rem-transportistas', 'action' => 'index']) ?>">
                    <i class="fas fa-cubes fa-fw"></i> Guias de Transportista
                </a>
            </li>
        </ul>
    </li>

    <li>
        <a href="<?= $this->Url->Build(['controller' => 'Almacenes', 'action' => 'index']) ?>">
            <i class="fas fa-cubes fa-fw"></i> Establecimientos
        </a>
    </li>
   
    <li>
        <a href="<?= $this->Url->Build(['controller' => 'Configuraciones', 'action' => 'series']) ?>">
            <i class="fas fa-boxes fa-fw"></i> Series
        </a>
    </li>
    <li>
        <a href="<?= $this->Url->Build(['controller' => 'parte-entradas', 'action' => 'index']) ?>">
            <i class="fas fa-boxes fa-fw"></i> Parte Entradas
        </a>
    </li>
    <li>
        <a href="<?= $this->Url->Build(['controller' => 'parte-salidas', 'action' => 'index']) ?>">
            <i class="fas fa-shipping-fast fa-fw"></i> Parte Salidas
        </a>
    </li>
    <li>
        <a href="<?= $this->Url->Build(['controller' => 'Reportes', 'action' => 'index']) ?>">
            <i class="fas fa-file-export fa-fw"></i> Reportes
        </a>
    </li>

    <hr />
    <li>
        <a href="#menuSistema" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <i class="fas fa-cogs fa-fw"></i><span class="menu-title"> Sistema</span>
        </a>
        <ul class="collapse list-unstyled" id="menuSistema">

            <li>
                <a href="<?= $this->Url->Build(['controller' => 'usuarios', 'action' => 'index']) ?>">
                    <i class="fas fa-caret-right"></i> Usuarios
                </a>
            </li>

            <li>
                <a href="<?= $this->Url->Build(['controller' => 'Configuraciones', 'action' => 'documentos']) ?>">
                    <i class="fas fa-caret-right"></i> Documentos
                </a>
            </li>
        </ul>
    </li>
    <?php if ($usuario_sesion['rol'] == "SUPERADMIN") { ?>
        <hr />
        <li>
            <a href="#menuSuperadmin" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-cogs fa-fw"></i><span class="menu-title"> Superadmin</span>
            </a>
            <ul class="collapse list-unstyled" id="menuSuperadmin">
                <li>
                    <a href="<?= $this->Url->Build(['controller' => 'Configuraciones', 'action' => 'branding']) ?>">
                        1. Branding
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->Build(['controller' => 'Configuraciones', 'action' => 'emisores']) ?>">
                        2. Emisor
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->Build(['controller' => 'Configuraciones', 'action' => 'ver-logs']) ?>">
                        3. Logs
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->Build(['controller' => 'Configuraciones', 'action' => 'eliminar-tmp']) ?>">
                        3. Eliminar Tmp
                    </a>
                </li>


            </ul>
        </li>
    <?php } ?>

    <!-- Lista del contador -->

    <li>
        <hr />
        <?= $this->Html->Link("<i class='fas fa-sign-out-alt fa-fw'></i> Salir del Sistema", ['controller' => 'Usuarios', 'action' =>  'cerrarSesion'], ['class' => '', 'escape' => false]); ?>
    </li>

</ul>