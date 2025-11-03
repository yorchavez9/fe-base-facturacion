<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VentaPago Entity
 *
 * @property int $id
 * @property int $oid
 * @property int $venta_id
 * @property string $monto
 * @property string $nota
 * @property \Cake\I18n\FrozenDate $fecha_pago
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Venta $venta
 */
class VentaPago extends Entity
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
