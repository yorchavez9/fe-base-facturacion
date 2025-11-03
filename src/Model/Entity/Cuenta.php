<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cuenta Entity
 *
 * @property int $id
 * @property int $usuario_id
 * @property int $cliente_tipo_id
 * @property int|null $canal_llegada_id
 * @property string $ruc
 * @property string $nombre_comercial
 * @property string $razon_social
 * @property string $domicilio_fiscal
 * @property string $correo
 * @property string $celular
 * @property string $telefono
 * @property string $whatsapp
 * @property string|null $notas
 * @property string $ubigeo
 * @property string $ubigeo_dpr
 * @property string|null $info_busqueda
 * @property string $estado
 * @property \Cake\I18n\FrozenTime|null $fecha_creacion
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string|null $asesor
 *
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\ClienteTipo $cliente_tipo
 * @property \App\Model\Entity\CanalLlegada $canal_llegada
 * @property \App\Model\Entity\Cotizacione[] $cotizaciones
 * @property \App\Model\Entity\CuentaPersona[] $cuenta_personas
 */
class Cuenta extends Entity
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
        'usuario_id' => true,
        'cliente_tipo_id' => true,
        'canal_llegada_id' => true,
        'ruc' => true,
        'nombre_comercial' => true,
        'razon_social' => true,
        'domicilio_fiscal' => true,
        'correo' => true,
        'celular' => true,
        'telefono' => true,
        'whatsapp' => true,
        'notas' => true,
        'ubigeo' => true,
        'ubigeo_dpr' => true,
        'info_busqueda' => true,
        'estado' => true,
        'fecha_creacion' => true,
        'created' => true,
        'modified' => true,
        'asesor' => true,
        'usuario' => true,
        'cliente_tipo' => true,
        'canal_llegada' => true,
        'cotizaciones' => true,
        'cuenta_personas' => true,
    ];
}
