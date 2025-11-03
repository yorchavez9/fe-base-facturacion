<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ItemPrecio Entity
 *
 * @property int $id
 * @property string|null $precio
 * @property string|null $descripcion
 * @property int|null $item_id
 * @property int|null $almacen_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Item $item
 * @property \App\Model\Entity\Almacen $almacen
 */
class ItemPrecio extends Entity
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
        'precio' => true,
        'descripcion' => true,
        'item_id' => true,
        'almacen_id' => true,
        'created' => true,
        'modified' => true,
        'item' => true,
        'almacen' => true,
    ];
}
