<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DirectorioCategoriaRel Model
 *
 * @property \App\Model\Table\EmpresasTable&\Cake\ORM\Association\BelongsTo $Empresas
 * @property \App\Model\Table\CategoriasTable&\Cake\ORM\Association\BelongsTo $Categorias
 *
 * @method \App\Model\Entity\DirectorioCategoriaRel newEmptyEntity()
 * @method \App\Model\Entity\DirectorioCategoriaRel newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioCategoriaRel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioCategoriaRel get($primaryKey, $options = [])
 * @method \App\Model\Entity\DirectorioCategoriaRel findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DirectorioCategoriaRel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioCategoriaRel[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioCategoriaRel|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DirectorioCategoriaRel saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DirectorioCategoriaRel[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DirectorioCategoriaRel[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DirectorioCategoriaRel[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DirectorioCategoriaRel[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DirectorioCategoriaRelTable extends Table
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

        $this->setTable('directorio_categoria_rel');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('DirectorioEmpresas', [
            'foreignKey' => 'empresa_id',
        ]);
        $this->belongsTo('DirectorioCategorias', [
            'foreignKey' => 'categoria_id',
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
//        $rules->add($rules->existsIn(['empresa_id'], 'Empresas'), ['errorField' => 'empresa_id']);
//        $rules->add($rules->existsIn(['categoria_id'], 'Categorias'), ['errorField' => 'categoria_id']);

        return $rules;
    }
}
