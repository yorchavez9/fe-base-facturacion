<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CuentaCanalLlegadas Model
 *
 * @method \App\Model\Entity\CuentaCanalLlegada newEmptyEntity()
 * @method \App\Model\Entity\CuentaCanalLlegada newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CuentaCanalLlegada[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CuentaCanalLlegada get($primaryKey, $options = [])
 * @method \App\Model\Entity\CuentaCanalLlegada findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CuentaCanalLlegada patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CuentaCanalLlegada[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CuentaCanalLlegada|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CuentaCanalLlegada saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CuentaCanalLlegada[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CuentaCanalLlegada[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CuentaCanalLlegada[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CuentaCanalLlegada[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CuentaCanalLlegadasTable extends Table
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

        $this->setTable('cuenta_canal_llegadas');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
//            ->scalar('nombre')
//            ->maxLength('nombre', 64)
//            ->allowEmptyString('nombre');
//
//        $validator
//            ->scalar('descripcion')
//            ->allowEmptyString('descripcion');

        return $validator;
    }
}
