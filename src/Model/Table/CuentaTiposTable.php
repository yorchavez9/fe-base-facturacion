<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CuentaTipos Model
 *
 * @method \App\Model\Entity\CuentaTipo newEmptyEntity()
 * @method \App\Model\Entity\CuentaTipo newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CuentaTipo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CuentaTipo get($primaryKey, $options = [])
 * @method \App\Model\Entity\CuentaTipo findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CuentaTipo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CuentaTipo[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CuentaTipo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CuentaTipo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CuentaTipo[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CuentaTipo[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CuentaTipo[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CuentaTipo[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class CuentaTiposTable extends Table
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

        $this->setTable('cuenta_tipos');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');
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
//            ->maxLength('nombre', 155)
//            ->notEmptyString('nombre');
//
//        $validator
//            ->scalar('descripcion')
//            ->maxLength('descripcion', 255)
//            ->notEmptyString('descripcion');

        return $validator;
    }
}
