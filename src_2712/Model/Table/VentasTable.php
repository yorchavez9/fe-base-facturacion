<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Ventas Model
 *
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\AlmacensTable&\Cake\ORM\Association\BelongsTo $Almacens
 * @property \App\Model\Table\ClientesTable&\Cake\ORM\Association\BelongsTo $Clientes
 * @property \App\Model\Table\CotizacionesTable&\Cake\ORM\Association\HasMany $Cotizaciones
 * @property \App\Model\Table\VentaComunicadoBajasTable&\Cake\ORM\Association\HasMany $VentaComunicadoBajas
 * @property \App\Model\Table\VentaPagosTable&\Cake\ORM\Association\HasMany $VentaPagos
 * @property \App\Model\Table\VentaRegistrosTable&\Cake\ORM\Association\HasMany $VentaRegistros
 * @property \App\Model\Table\WebOrdenPedidosTable&\Cake\ORM\Association\HasMany $WebOrdenPedidos
 *
 * @method \App\Model\Entity\Venta newEmptyEntity()
 * @method \App\Model\Entity\Venta newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Venta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Venta get($primaryKey, $options = [])
 * @method \App\Model\Entity\Venta findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Venta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Venta[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Venta|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Venta saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Venta[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Venta[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Venta[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Venta[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VentasTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('ventas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Usuarios', [
            'foreignKey'    => 'usuario_id',
            'propertyName'  => 'Vendedor',
        ]);

        $this->belongsTo('Cuentas', [
            'foreignKey'    => 'cliente_id',
            'propertyName'  => 'Cliente'
        ]);

        $this->hasMany('VentaRegistros', [
            'foreignKey' => 'venta_id'
        ]);
        $this->hasMany('VentaPagos', [
            'foreignKey' => 'venta_id'
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
            ->allowEmptyString('id', null, 'create');

        /*
        $validator
            ->scalar('emisor_ruc')
            ->maxLength('emisor_ruc', 11)
            ->notEmptyString('emisor_ruc');

        $validator
            ->scalar('emisor_razon_social')
            ->maxLength('emisor_razon_social', 255)
            ->notEmptyString('emisor_razon_social');

        $validator
            ->scalar('cliente_doc_tipo')
            ->maxLength('cliente_doc_tipo', 2)
            ->notEmptyString('cliente_doc_tipo');

        $validator
            ->scalar('cliente_doc_numero')
            ->maxLength('cliente_doc_numero', 16)
            ->notEmptyString('cliente_doc_numero');

        $validator
            ->scalar('cliente_razon_social')
            ->maxLength('cliente_razon_social', 256)
            ->notEmptyString('cliente_razon_social');

        $validator
            ->scalar('cliente_domicilio_fiscal')
            ->maxLength('cliente_domicilio_fiscal', 256)
            ->notEmptyString('cliente_domicilio_fiscal');

        $validator
            ->scalar('documento_tipo')
            ->maxLength('documento_tipo', 2)
            ->requirePresence('documento_tipo', 'create')
            ->notEmptyString('documento_tipo');

        $validator
            ->scalar('documento_serie')
            ->maxLength('documento_serie', 16)
            ->notEmptyString('documento_serie');

        $validator
            ->scalar('documento_numero')
            ->maxLength('documento_numero', 16)
            ->requirePresence('documento_numero', 'create')
            ->notEmptyString('documento_numero');

        $validator
            ->scalar('nombre_unico')
            ->maxLength('nombre_unico', 255)
            ->notEmptyString('nombre_unico');

        $validator
            ->scalar('estado')
            ->maxLength('estado', 16)
            ->notEmptyString('estado');

        $validator
            ->scalar('sunat_cdr_rc')
            ->maxLength('sunat_cdr_rc', 8)
            ->requirePresence('sunat_cdr_rc', 'create')
            ->notEmptyString('sunat_cdr_rc');

        $validator
            ->scalar('sunat_cdr_msg')
            ->maxLength('sunat_cdr_msg', 255)
            ->requirePresence('sunat_cdr_msg', 'create')
            ->notEmptyString('sunat_cdr_msg');

        $validator
            ->scalar('estado_sunat')
            ->maxLength('estado_sunat', 32)
            ->notEmptyString('estado_sunat');

        $validator
            ->decimal('subtotal')
            ->greaterThanOrEqual('subtotal', 0)
            ->notEmptyString('subtotal');

        $validator
            ->decimal('igv_percent')
            ->greaterThanOrEqual('igv_percent', 0)
            ->notEmptyString('igv_percent');

        $validator
            ->decimal('igv_monto')
            ->greaterThanOrEqual('igv_monto', 0)
            ->notEmptyString('igv_monto');

        $validator
            ->decimal('op_gravadas')
            ->greaterThanOrEqual('op_gravadas', 0)
            ->notEmptyString('op_gravadas');

        $validator
            ->decimal('op_gratuitas')
            ->greaterThanOrEqual('op_gratuitas', 0)
            ->notEmptyString('op_gratuitas');

        $validator
            ->decimal('op_exoneradas')
            ->greaterThanOrEqual('op_exoneradas', 0)
            ->notEmptyString('op_exoneradas');

        $validator
            ->decimal('op_inafectas')
            ->greaterThanOrEqual('op_inafectas', 0)
            ->notEmptyString('op_inafectas');

        $validator
            ->decimal('total')
            ->requirePresence('total', 'create')
            ->notEmptyString('total');

        $validator
            ->scalar('total_en_letras')
            ->maxLength('total_en_letras', 255)
            ->notEmptyString('total_en_letras');

        $validator
            ->decimal('total_pagos')
            ->greaterThanOrEqual('total_pagos', 0)
            ->notEmptyString('total_pagos');

        $validator
            ->decimal('total_deuda')
            ->greaterThanOrEqual('total_deuda', 0)
            ->notEmptyString('total_deuda');

        $validator
            ->notEmptyString('total_items');

        $validator
            ->dateTime('fecha_venta')
            ->requirePresence('fecha_venta', 'create')
            ->notEmptyDateTime('fecha_venta');

        $validator
            ->scalar('tipo_moneda')
            ->maxLength('tipo_moneda', 3)
            ->notEmptyString('tipo_moneda');

        $validator
            ->scalar('comentarios')
            ->requirePresence('comentarios', 'create')
            ->notEmptyString('comentarios');

        $validator
            ->scalar('guia_remision')
            ->maxLength('guia_remision', 16)
            ->notEmptyString('guia_remision');

        $validator
            ->scalar('codvendedor')
            ->maxLength('codvendedor', 64)
            ->notEmptyString('codvendedor');

        $validator
            ->scalar('nro_ref')
            ->maxLength('nro_ref', 125)
            ->notEmptyString('nro_ref');

        $validator
            ->scalar('forma_pago')
            ->maxLength('forma_pago', 125)
            ->notEmptyString('forma_pago');

        */
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
   
        return $rules;
    }
}
