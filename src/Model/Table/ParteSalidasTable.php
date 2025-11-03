<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ParteSalidas Model
 *
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\ParteEntradasTable&\Cake\ORM\Association\HasMany $ParteEntradas
 * @property \App\Model\Table\ParteSalidaRegistrosTable&\Cake\ORM\Association\HasMany $ParteSalidaRegistros
 *
 * @method \App\Model\Entity\ParteSalida newEmptyEntity()
 * @method \App\Model\Entity\ParteSalida newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ParteSalida[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ParteSalida get($primaryKey, $options = [])
 * @method \App\Model\Entity\ParteSalida findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ParteSalida patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ParteSalida[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ParteSalida|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ParteSalida saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ParteSalida[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ParteSalida[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ParteSalida[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ParteSalida[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ParteSalidasTable extends Table
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

        $this->setTable('parte_salidas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
        ]);
        $this->hasMany('ParteEntradas', [
            'foreignKey' => 'parte_salida_id',
        ]);
        $this->hasMany('ParteSalidaRegistros', [
            'foreignKey' => 'parte_salida_id',
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
