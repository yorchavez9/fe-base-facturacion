<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Persona Entity
 *
 * @property int $id
 * @property string|null $dni
 * @property string|null $nombres
 * @property string|null $apellidos
 * @property string|null $cargo_empresa
 * @property string|null $correo
 * @property string|null $telefono
 * @property string|null $anexo
 * @property string|null $celular_trabajo
 * @property string|null $celular_personal
 * @property string $info_busqueda
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $direccion
 * @property string $clave
 *
 * @property \App\Model\Entity\CuentaPersona[] $cuenta_personas
 * @property \App\Model\Entity\SsdDiagnostico[] $ssd_diagnosticos
 */
class Persona extends Entity
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
