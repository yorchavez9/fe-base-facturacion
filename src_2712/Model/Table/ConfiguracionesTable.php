<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Configuraciones Model
 *
 * @method \App\Model\Entity\Configuracion newEmptyEntity()
 * @method \App\Model\Entity\Configuracion newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Configuracion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Configuracion get($primaryKey, $options = [])
 * @method \App\Model\Entity\Configuracion findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Configuracion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Configuracion[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Configuracion|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Configuracion saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Configuracion[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Configuracion[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Configuracion[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Configuracion[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ConfiguracionesTable extends Table
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

        $this->setTable('configuraciones');
        $this->setDisplayField('id');
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
//            ->scalar('varname')
//            ->maxLength('varname', 32)
//            ->allowEmptyString('varname');
//
//        $validator
//            ->scalar('varvalue')
//            ->allowEmptyString('varvalue');

        return $validator;
    }
}
