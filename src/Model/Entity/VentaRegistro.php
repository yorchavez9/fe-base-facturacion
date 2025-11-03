<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VentaRegistro Entity
 *
 * @property int $id
 * @property int|null $oid
 * @property int $venta_id
 * @property int $item_index
 * @property int $item_id
 * @property string $item_codigo
 * @property string $item_nombre
 * @property string|null $item_comentario
 * @property string $item_unidad
 * @property string $inc_igv
 * @property int $cantidad
 * @property string $precio_ucompra
 * @property string $precio_uventa
 * @property string $precio_uventa_sinigv
 * @property string $subtotal
 * @property string $igv_monto
 * @property string $precio_total
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Venta $venta
 * @property \App\Model\Entity\Item $item
 */
class VentaRegistro extends Entity
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
