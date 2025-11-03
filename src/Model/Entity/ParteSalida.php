<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ParteSalida Entity
 *
 * @property int $id
 * @property int|null $almacen_salida_id
 * @property int|null $almacen_destino_id
 * @property int|null $usuario_id
 * @property \Cake\I18n\FrozenDate|null $fecha_emision
 * @property string|null $descripcion
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\AlmacenSalida $almacen_salida
 * @property \App\Model\Entity\AlmacenDestino $almacen_destino
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\ParteEntrada[] $parte_entradas
 * @property \App\Model\Entity\ParteSalidaRegistro[] $parte_salida_registros
 */
class ParteSalida extends Entity
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
