<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ItemActivo Entity
 *
 * @property int $id
 * @property string $codigo
 * @property string|null $descripcion
 * @property string|null $estado
 * @property string|null $ubicacion
 * @property string|null $usuario
 * @property string|null $precio_compra
 * @property \Cake\I18n\FrozenTime|null $fecha_compra
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class ItemActivo extends Entity
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
