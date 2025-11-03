<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SunatFeFacturas Model
 *
 * @property \App\Model\Table\VentasTable&\Cake\ORM\Association\BelongsTo $Ventas
 *
 * @method \App\Model\Entity\SunatFeFactura newEmptyEntity()
 * @method \App\Model\Entity\SunatFeFactura newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\SunatFeFactura> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SunatFeFactura get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\SunatFeFactura findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\SunatFeFactura patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\SunatFeFactura> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SunatFeFactura|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\SunatFeFactura saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\SunatFeFactura>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SunatFeFactura>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SunatFeFactura>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SunatFeFactura> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SunatFeFactura>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SunatFeFactura>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SunatFeFactura>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SunatFeFactura> deleteManyOrFail(iterable $entities, array $options = [])
 */
class SunatFeFacturasTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('sunat_fe_facturas');
        $this->setDisplayField('cliente_tipo_doc');
        $this->setPrimaryKey('id');

        $this->belongsTo('Ventas', [
            'foreignKey' => 'venta_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->allowEmptyString('venta_id');

        $validator
            ->allowEmptyString('resumen_id');

        $validator
            ->allowEmptyString('cliente_id');

        $validator
            ->allowEmptyString('emisor_id');

        $validator
            ->scalar('emisor_ruc')
            ->maxLength('emisor_ruc', 11)
            ->allowEmptyString('emisor_ruc');

        $validator
            ->scalar('emisor_razon_social')
            ->maxLength('emisor_razon_social', 255)
            ->allowEmptyString('emisor_razon_social');

        $validator
            ->scalar('emisor_nombre_comercial')
            ->maxLength('emisor_nombre_comercial', 255)
            ->allowEmptyString('emisor_nombre_comercial');

        $validator
            ->allowEmptyString('establecimiento_id');

        $validator
            ->scalar('establecimiento_codigo')
            ->maxLength('establecimiento_codigo', 4)
            ->allowEmptyString('establecimiento_codigo');

        $validator
            ->scalar('establecimiento_ubigeo')
            ->maxLength('establecimiento_ubigeo', 6)
            ->allowEmptyString('establecimiento_ubigeo');

        $validator
            ->scalar('establecimiento_departamento')
            ->maxLength('establecimiento_departamento', 255)
            ->allowEmptyString('establecimiento_departamento');

        $validator
            ->scalar('establecimiento_provincia')
            ->maxLength('establecimiento_provincia', 255)
            ->allowEmptyString('establecimiento_provincia');

        $validator
            ->scalar('establecimiento_distrito')
            ->maxLength('establecimiento_distrito', 255)
            ->allowEmptyString('establecimiento_distrito');

        $validator
            ->scalar('establecimiento_urbanizacion')
            ->maxLength('establecimiento_urbanizacion', 255)
            ->allowEmptyString('establecimiento_urbanizacion');

        $validator
            ->scalar('establecimiento_direccion')
            ->maxLength('establecimiento_direccion', 255)
            ->allowEmptyString('establecimiento_direccion');

        $validator
            ->scalar('ubl_version')
            ->maxLength('ubl_version', 6)
            ->allowEmptyString('ubl_version');

        $validator
            ->scalar('tipo_operacion')
            ->maxLength('tipo_operacion', 4)
            ->allowEmptyString('tipo_operacion');

        $validator
            ->scalar('cod_detraccion')
            ->maxLength('cod_detraccion', 3)
            ->allowEmptyString('cod_detraccion');

        $validator
            ->decimal('porcentaje_detraccion')
            ->allowEmptyString('porcentaje_detraccion');

        $validator
            ->decimal('monto_detraccion')
            ->allowEmptyString('monto_detraccion');

        $validator
            ->scalar('tipo_doc')
            ->maxLength('tipo_doc', 2)
            ->allowEmptyString('tipo_doc');

        $validator
            ->scalar('serie')
            ->maxLength('serie', 4)
            ->allowEmptyString('serie');

        $validator
            ->scalar('correlativo')
            ->maxLength('correlativo', 8)
            ->allowEmptyString('correlativo');

        $validator
            ->dateTime('fecha_emision')
            ->allowEmptyDateTime('fecha_emision');

        $validator
            ->date('fecha_vencimiento')
            ->allowEmptyDate('fecha_vencimiento');

        $validator
            ->scalar('cliente_tipo_doc')
            ->maxLength('cliente_tipo_doc', 2)
            ->notEmptyString('cliente_tipo_doc');

        $validator
            ->scalar('cliente_num_doc')
            ->maxLength('cliente_num_doc', 16)
            ->notEmptyString('cliente_num_doc');

        $validator
            ->scalar('cliente_razon_social')
            ->maxLength('cliente_razon_social', 255)
            ->notEmptyString('cliente_razon_social');

        $validator
            ->scalar('nombre_archivo_xml')
            ->maxLength('nombre_archivo_xml', 255)
            ->notEmptyString('nombre_archivo_xml');

        $validator
            ->scalar('nombre_archivo_cdr')
            ->maxLength('nombre_archivo_cdr', 255)
            ->notEmptyString('nombre_archivo_cdr');

        $validator
            ->scalar('sunat_estado')
            ->maxLength('sunat_estado', 16)
            ->notEmptyString('sunat_estado');

        $validator
            ->scalar('consulta_cpe_estado')
            ->maxLength('consulta_cpe_estado', 20)
            ->allowEmptyString('consulta_cpe_estado');

        $validator
            ->scalar('consulta_cpe_respuesta')
            ->maxLength('consulta_cpe_respuesta', 255)
            ->allowEmptyString('consulta_cpe_respuesta');

        $validator
            ->scalar('sunat_cdr_notes')
            ->allowEmptyString('sunat_cdr_notes');

        $validator
            ->scalar('sunat_cdr_description')
            ->allowEmptyString('sunat_cdr_description');

        $validator
            ->scalar('sunat_cdr_code')
            ->maxLength('sunat_cdr_code', 4)
            ->allowEmptyString('sunat_cdr_code');

        $validator
            ->scalar('sunat_err_code')
            ->maxLength('sunat_err_code', 4)
            ->allowEmptyString('sunat_err_code');

        $validator
            ->scalar('sunat_err_message')
            ->allowEmptyString('sunat_err_message');

        $validator
            ->decimal('mto_oper_gravadas')
            ->allowEmptyString('mto_oper_gravadas');

        $validator
            ->decimal('mto_oper_exoneradas')
            ->allowEmptyString('mto_oper_exoneradas');

        $validator
            ->decimal('mto_oper_inafectas')
            ->allowEmptyString('mto_oper_inafectas');

        $validator
            ->decimal('mto_otros_cargos')
            ->allowEmptyString('mto_otros_cargos');

        $validator
            ->decimal('mto_igv')
            ->allowEmptyString('mto_igv');

        $validator
            ->decimal('isc_monto')
            ->notEmptyString('isc_monto');

        $validator
            ->decimal('icb_per_monto')
            ->notEmptyString('icb_per_monto');

        $validator
            ->decimal('total_impuestos')
            ->allowEmptyString('total_impuestos');

        $validator
            ->decimal('valor_venta')
            ->allowEmptyString('valor_venta');

        $validator
            ->decimal('sub_total')
            ->allowEmptyString('sub_total');

        $validator
            ->decimal('mto_imp_venta')
            ->allowEmptyString('mto_imp_venta');

        $validator
            ->scalar('nombre_unico')
            ->maxLength('nombre_unico', 255)
            ->notEmptyString('nombre_unico');

        $validator
            ->scalar('mto_letras')
            ->maxLength('mto_letras', 255)
            ->notEmptyString('mto_letras');

        $validator
            ->scalar('forma_pago')
            ->maxLength('forma_pago', 12)
            ->notEmptyString('forma_pago');

        $validator
            ->scalar('tipo_moneda')
            ->maxLength('tipo_moneda', 12)
            ->notEmptyString('tipo_moneda');

        $validator
            ->scalar('items')
            ->allowEmptyString('items');

        $validator
            ->scalar('estado_boleta')
            ->maxLength('estado_boleta', 1)
            ->notEmptyString('estado_boleta');

        return $validator;
    }

}
