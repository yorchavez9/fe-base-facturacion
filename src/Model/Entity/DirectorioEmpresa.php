<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DirectorioEmpresa Entity
 *
 * @property int $id
 * @property int $categoria_id
 * @property int|null $empresa_id
 * @property string|null $nombre_comercial
 * @property string|null $razon_social
 * @property string|null $ruc
 * @property string|null $domicilio_fiscal
 * @property string $ubigeo
 * @property string $ubigeo_dpr
 * @property string|null $info_busqueda
 * @property string|null $estado
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $telefonos
 * @property string $correo
 * @property string $notas
 *
 * @property \App\Model\Entity\Categoria $categoria
 * @property \App\Model\Entity\Empresa $empresa
 */
class DirectorioEmpresa extends Entity
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
