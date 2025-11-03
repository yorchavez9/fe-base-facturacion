<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SunatFeFactura Entity
 *
 * @property int $id
 * @property int|null $venta_id
 * @property int|null $resumen_id
 * @property int|null $cliente_id
 * @property int|null $emisor_id
 * @property string|null $emisor_ruc
 * @property string|null $emisor_razon_social
 * @property string|null $emisor_nombre_comercial
 * @property int|null $establecimiento_id
 * @property string|null $establecimiento_codigo
 * @property string|null $establecimiento_ubigeo
 * @property string|null $establecimiento_departamento
 * @property string|null $establecimiento_provincia
 * @property string|null $establecimiento_distrito
 * @property string|null $establecimiento_urbanizacion
 * @property string|null $establecimiento_direccion
 * @property string|null $ubl_version
 * @property string|null $tipo_operacion
 * @property string|null $cod_detraccion
 * @property string|null $porcentaje_detraccion
 * @property string|null $monto_detraccion
 * @property string|null $tipo_doc
 * @property string|null $serie
 * @property string|null $correlativo
 * @property \Cake\I18n\DateTime|null $fecha_emision
 * @property \Cake\I18n\Date|null $fecha_vencimiento
 * @property string $cliente_tipo_doc
 * @property string $cliente_num_doc
 * @property string $cliente_razon_social
 * @property string $nombre_archivo_xml
 * @property string $nombre_archivo_cdr
 * @property string $sunat_estado
 * @property string|null $consulta_cpe_estado
 * @property string|null $consulta_cpe_respuesta
 * @property string|null $sunat_cdr_notes
 * @property string|null $sunat_cdr_description
 * @property string|null $sunat_cdr_code
 * @property string|null $sunat_err_code
 * @property string|null $sunat_err_message
 * @property string|null $mto_oper_gravadas
 * @property string|null $mto_oper_exoneradas
 * @property string|null $mto_oper_inafectas
 * @property string|null $mto_otros_cargos
 * @property string|null $mto_igv
 * @property string $isc_monto
 * @property string $icb_per_monto
 * @property string|null $total_impuestos
 * @property string|null $valor_venta
 * @property string|null $sub_total
 * @property string|null $mto_imp_venta
 * @property string $nombre_unico
 * @property string $mto_letras
 * @property string $forma_pago
 * @property string $tipo_moneda
 * @property string|null $items
 * @property string $estado_boleta
 *
 * @property \App\Model\Entity\Venta $venta
 */
class SunatFeFactura extends Entity
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
        '*' => true,
    ];
}
