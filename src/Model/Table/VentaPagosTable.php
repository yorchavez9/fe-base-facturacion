<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VentaPagos Model
 *
 * @property \App\Model\Table\VentasTable&\Cake\ORM\Association\BelongsTo $Ventas
 *
 * @method \App\Model\Entity\VentaPago newEmptyEntity()
 * @method \App\Model\Entity\VentaPago newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\VentaPago[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VentaPago get($primaryKey, $options = [])
 * @method \App\Model\Entity\VentaPago findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\VentaPago patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VentaPago[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\VentaPago|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VentaPago saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VentaPago[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\VentaPago[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\VentaPago[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\VentaPago[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VentaPagosTable extends Table
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

        $this->setTable('venta_pagos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Ventas', [
            'foreignKey' => 'ventas_id',
            'joinType' => 'INNER'
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

        $validator
            ->notEmptyString('oid');

        $validator
            ->decimal('monto')
            ->greaterThanOrEqual('monto', 0)
            ->requirePresence('monto', 'create')
            ->notEmptyString('monto');

        $validator
            ->scalar('nota')
            ->requirePresence('nota', 'create')
            ->notEmptyString('nota');

        $validator
            ->date('fecha_pago')
            ->requirePresence('fecha_pago', 'create')
            ->notEmptyDate('fecha_pago');

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
        $rules->add($rules->existsIn(['venta_id'], 'Ventas'), ['errorField' => 'venta_id']);

        return $rules;
    }
}
