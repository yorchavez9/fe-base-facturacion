<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Almacene Entity
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $correo
 * @property string|null $departamento
 * @property string|null $provincia
 * @property string|null $distrito
 * @property string|null $urbanizacion
 * @property string|null $codigo
 * @property string|null $direccion
 * @property string|null $telefono
 * @property string|null $map_lat
 * @property string|null $map_len
 * @property string|null $ubigeo
 * @property string|null $ubigeo_dpr
 * @property string|null $es_tienda
 * @property string|null $es_publico
 * @property string|null $info_busqueda
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 */
class Almacene extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'nombre' => true,
        'correo' => true,
        'departamento' => true,
        'provincia' => true,
        'distrito' => true,
        'urbanizacion' => true,
        'codigo' => true,
        'direccion' => true,
        'telefono' => true,
        'map_lat' => true,
        'map_len' => true,
        'ubigeo' => true,
        'ubigeo_dpr' => true,
        'es_tienda' => true,
        'es_publico' => true,
        'info_busqueda' => true,
        'created' => true,
        'modified' => true,
    ];
}
