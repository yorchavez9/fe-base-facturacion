<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CotizacionRegistros Model
 *
 * @property \App\Model\Table\CotizacionesTable&\Cake\ORM\Association\BelongsTo $Cotizaciones
 * @property \App\Model\Table\ItemsTable&\Cake\ORM\Association\BelongsTo $Items
 *
 * @method \App\Model\Entity\CotizacionRegistro newEmptyEntity()
 * @method \App\Model\Entity\CotizacionRegistro newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CotizacionRegistro[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CotizacionRegistro get($primaryKey, $options = [])
 * @method \App\Model\Entity\CotizacionRegistro findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CotizacionRegistro patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CotizacionRegistro[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CotizacionRegistro|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CotizacionRegistro saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CotizacionRegistro[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CotizacionRegistro[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CotizacionRegistro[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CotizacionRegistro[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CotizacionRegistrosTable extends Table
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

        $this->setTable('cotizacion_registros');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Cotizaciones', [
            'foreignKey' => 'cotizacion_id',
        ]);
        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
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

//        $validator
//            ->allowEmptyString('oid');
//
//        $validator
//            ->scalar('item_codigo')
//            ->maxLength('item_codigo', 32)
//            ->allowEmptyString('item_codigo');
//
//        $validator
//            ->scalar('item_unidad')
//            ->maxLength('item_unidad', 16)
//            ->allowEmptyString('item_unidad');
//
//        $validator
//            ->scalar('item_nombre')
//            ->maxLength('item_nombre', 128)
//            ->allowEmptyString('item_nombre');
//
//        $validator
//            ->nonNegativeInteger('cantidad')
//            ->allowEmptyString('cantidad');
//
//        $validator
//            ->decimal('precio_unitario')
//            ->greaterThanOrEqual('precio_unitario', 0)
//            ->allowEmptyString('precio_unitario');
//
//        $validator
//            ->decimal('precio_total')
//            ->greaterThanOrEqual('precio_total', 0)
//            ->allowEmptyString('precio_total');

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
//        $rules->add($rules->existsIn(['cotizacion_id'], 'Cotizaciones'), ['errorField' => 'cotizacion_id']);
//        $rules->add($rules->existsIn(['item_id'], 'Items'), ['errorField' => 'item_id']);

        return $rules;
    }
}
