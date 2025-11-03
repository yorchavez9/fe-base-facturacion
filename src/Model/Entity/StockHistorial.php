<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StockHistorial Entity
 *
 * @property int $id
 * @property int|null $item_id
 * @property string|null $item_codigo
 * @property string|null $item_nombre
 * @property int|null $usuario_id
 * @property int|null $almacen_id
 * @property string|null $operacion
 * @property string|null $comentario
 * @property int|null $cantidad
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Item $item
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\Almacen $almacen
 */
class StockHistorial extends Entity
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
