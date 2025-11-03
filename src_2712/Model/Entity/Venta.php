<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Venta Entity
 *
 * @property int $id
 * @property int $usuario_id
 * @property int $almacen_id
 * @property string $emisor_ruc
 * @property string $emisor_razon_social
 * @property int $cliente_id
 * @property string $cliente_persona_tipo
 * @property string $cliente_doc_tipo
 * @property string $cliente_doc_numero
 * @property string $cliente_razon_social
 * @property string $cliente_domicilio_fiscal
 * @property string $documento_tipo
 * @property string $documento_serie
 * @property string $documento_correlativo
 * @property string $nombre_unico
 * @property string $estado
 * @property string $sunat_cdr_rc
 * @property string $sunat_cdr_msg
 * @property string $estado_sunat
 * @property string $subtotal
 * @property string $igv_percent
 * @property string $igv_monto
 * @property string $op_gravadas
 * @property string $op_gratuitas
 * @property string $op_exoneradas
 * @property string $op_inafectas
 * @property string $total
 * @property string $total_en_letras
 * @property string $total_pagos
 * @property string $total_deuda
 * @property int $total_items
 * @property \Cake\I18n\FrozenTime $fecha_venta
 * @property string $tipo_moneda
 * @property string $comentarios
 * @property string $guia_remision
 * @property string $codvendedor
 * @property string $nro_ref
 * @property string $forma_pago
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\Almacen $almacen
 * @property \App\Model\Entity\Cliente $cliente
 * @property \App\Model\Entity\Cotizacione[] $cotizaciones
 * @property \App\Model\Entity\VentaComunicadoBaja[] $venta_comunicado_bajas
 * @property \App\Model\Entity\VentaPago[] $venta_pagos
 * @property \App\Model\Entity\VentaRegistro[] $venta_registros
 * @property \App\Model\Entity\WebOrdenPedido[] $web_orden_pedidos
 */
class Venta extends Entity
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
