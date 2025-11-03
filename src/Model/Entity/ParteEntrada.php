<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ParteEntrada Entity
 *
 * @property int $id
 * @property int|null $parte_salida_id
 * @property \Cake\I18n\FrozenDate|null $fecha
 * @property string|null $descripcion
 * @property int|null $almacen_id
 * @property int|null $usuario_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\ParteSalida $parte_salida
 * @property \App\Model\Entity\Almacen $almacen
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\ParteEntradaRegistro[] $parte_entrada_registros
 */
class ParteEntrada extends Entity
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
