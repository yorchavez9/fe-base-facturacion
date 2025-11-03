<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CuentaPersonas Model
 *
 * @property \App\Model\Table\CuentasTable&\Cake\ORM\Association\BelongsTo $Cuentas
 * @property \App\Model\Table\PersonasTable&\Cake\ORM\Association\BelongsTo $Personas
 *
 * @method \App\Model\Entity\CuentaPersona newEmptyEntity()
 * @method \App\Model\Entity\CuentaPersona newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CuentaPersona[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CuentaPersona get($primaryKey, $options = [])
 * @method \App\Model\Entity\CuentaPersona findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CuentaPersona patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CuentaPersona[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CuentaPersona|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CuentaPersona saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CuentaPersona[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CuentaPersona[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CuentaPersona[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CuentaPersona[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CuentaPersonasTable extends Table
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

        $this->setTable('cuenta_personas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Cuentas', [
            'foreignKey' => 'cuenta_id',
        ]);
        $this->belongsTo('Personas', [
            'foreignKey' => 'persona_id',
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
