<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Item Entity
 *
 * @property int $id
 * @property int $marca_id
 * @property int $categoria_id
 * @property string|null $codigo
 * @property string|null $nombre
 * @property string $unidad
 * @property string $descripcion
 * @property string $img_ruta
 * @property string|null $codigo_barra_ruta
 * @property string|null $codigo_qr_ruta
 * @property string $es_visible
 * @property string $precio_compra
 * @property string $precio_venta
 * @property string $precio_venta_web
 * @property string $precio_venta_mayor
 * @property int $stock_minimo_local
 * @property int $stock_minimo_global
 * @property string $controlar_inventario
 * @property int $sunat_tipo_tributo_id
 * @property string $info_busqueda
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Marca $marca
 * @property \App\Model\Entity\Categoria $categoria
 * @property \App\Model\Entity\SunatTipoTributo $sunat_tipo_tributo
 * @property \App\Model\Entity\CompraRegistro[] $compra_registros
 * @property \App\Model\Entity\CotizacionRegistro[] $cotizacion_registros
 * @property \App\Model\Entity\ItemFoto[] $item_fotos
 * @property \App\Model\Entity\ItemRel[] $item_rels
 * @property \App\Model\Entity\Stock[] $stock
 * @property \App\Model\Entity\StockHistorial[] $stock_historial
 * @property \App\Model\Entity\VentaRegistro[] $venta_registros
 * @property \App\Model\Entity\WebOrdenPedidoItem[] $web_orden_pedido_items
 */
class Item extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected array $_accessible = [
        '*' => true,

    ];
}
