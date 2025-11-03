<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cotizacione Entity
 *
 * @property int $id
 * @property int $usuario_id
 * @property int $cuenta_id
 * @property string $estado
 * @property string $subtotal
 * @property string $total
 * @property \Cake\I18n\FrozenTime $fecha_cotizacion
 * @property string $comentarios
 * @property string $codvendedor
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\Cuenta $cuenta
 */
class Cotizacion extends Entity
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
