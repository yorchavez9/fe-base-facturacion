<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CotizacionRegistro Entity
 *
 * @property int $id
 * @property int|null $oid
 * @property int|null $cotizacion_id
 * @property int|null $item_id
 * @property string|null $item_codigo
 * @property string|null $item_unidad
 * @property string|null $item_nombre
 * @property int|null $cantidad
 * @property string|null $precio_unitario
 * @property string|null $precio_total
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Cotizacion $cotizacion
 * @property \App\Model\Entity\Item $item
 */
class CotizacionRegistro extends Entity
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
        'oid' => true,
        'cotizacion_id' => true,
        'item_id' => true,
        'item_codigo' => true,
        'item_unidad' => true,
        'item_nombre' => true,
        'cantidad' => true,
        'precio_unitario' => true,
        'precio_total' => true,
        'created' => true,
        'modified' => true,
        'cotizacion' => true,
        'item' => true,
    ];
}
