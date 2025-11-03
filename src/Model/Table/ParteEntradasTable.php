<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ParteEntradas Model
 *
 * @property \App\Model\Table\ParteSalidasTable&\Cake\ORM\Association\BelongsTo $ParteSalidas
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\ParteEntradaRegistrosTable&\Cake\ORM\Association\HasMany $ParteEntradaRegistros
 *
 * @method \App\Model\Entity\ParteEntrada newEmptyEntity()
 * @method \App\Model\Entity\ParteEntrada newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ParteEntrada[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ParteEntrada get($primaryKey, $options = [])
 * @method \App\Model\Entity\ParteEntrada findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ParteEntrada patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ParteEntrada[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ParteEntrada|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ParteEntrada saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ParteEntrada[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ParteEntrada[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ParteEntrada[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ParteEntrada[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ParteEntradasTable extends Table
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

        $this->setTable('parte_entradas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ParteSalidas', [
            'foreignKey' => 'parte_salida_id',
        ]);
      
        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
        ]);
        $this->hasMany('ParteEntradaRegistros', [
            'foreignKey' => 'parte_entrada_id',
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
