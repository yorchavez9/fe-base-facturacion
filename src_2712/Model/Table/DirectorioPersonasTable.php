<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DirectorioPersonas Model
 *
 * @property \App\Model\Table\EmpresasTable&\Cake\ORM\Association\BelongsTo $Empresas
 * @property \App\Model\Table\PersonasTable&\Cake\ORM\Association\BelongsTo $Personas
 *
 * @method \App\Model\Entity\DirectorioPersona newEmptyEntity()
 * @method \App\Model\Entity\DirectorioPersona newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioPersona[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioPersona get($primaryKey, $options = [])
 * @method \App\Model\Entity\DirectorioPersona findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DirectorioPersona patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioPersona[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioPersona|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DirectorioPersona saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DirectorioPersona[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DirectorioPersona[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DirectorioPersona[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DirectorioPersona[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DirectorioPersonasTable extends Table
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

        $this->setTable('directorio_personas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('DirectorioEmpresas', [
            'foreignKey' => 'empresa_id',
        ]);
        $this->belongsTo('Personas', [
            'foreignKey' => 'persona_id',
            'joinType' => 'INNER',
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
//
//        $validator
//            ->scalar('rol')
//            ->maxLength('rol', 155)
//            ->requirePresence('rol', 'create')
//            ->notEmptyString('rol');

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
//        $rules->add($rules->existsIn(['empresa_id'], 'Empresas'), ['errorField' => 'empresa_id']);
//        $rules->add($rules->existsIn(['persona_id'], 'Personas'), ['errorField' => 'persona_id']);

        return $rules;
    }


}
