<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DirectorioEmpresas Model
 *
 * @property \App\Model\Table\CategoriasTable&\Cake\ORM\Association\BelongsTo $Categorias
 * @property \App\Model\Table\EmpresasTable&\Cake\ORM\Association\BelongsTo $Empresas
 *
 * @method \App\Model\Entity\DirectorioEmpresa newEmptyEntity()
 * @method \App\Model\Entity\DirectorioEmpresa newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioEmpresa[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioEmpresa get($primaryKey, $options = [])
 * @method \App\Model\Entity\DirectorioEmpresa findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DirectorioEmpresa patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioEmpresa[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioEmpresa|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DirectorioEmpresa saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DirectorioEmpresa[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DirectorioEmpresa[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DirectorioEmpresa[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DirectorioEmpresa[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DirectorioEmpresasTable extends Table
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

        $this->setTable('directorio_empresas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('DirectorioCategorias', [
            'foreignKey' => 'categoria_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('DirectorioCategoriaRel', [
            'foreignKey' => 'empresa_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('DirectorioEmpresas', [
            'foreignKey' => 'empresa_id',
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
//            ->scalar('nombre_comercial')
//            ->maxLength('nombre_comercial', 255)
//            ->allowEmptyString('nombre_comercial');
//
//        $validator
//            ->scalar('razon_social')
//            ->maxLength('razon_social', 255)
//            ->allowEmptyString('razon_social');
//
//        $validator
//            ->scalar('ruc')
//            ->maxLength('ruc', 11)
//            ->allowEmptyString('ruc')
//            ->add('ruc', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);
//
//        $validator
//            ->scalar('domicilio_fiscal')
//            ->maxLength('domicilio_fiscal', 128)
//            ->allowEmptyString('domicilio_fiscal');
//
//        $validator
//            ->scalar('ubigeo')
//            ->maxLength('ubigeo', 6)
//            ->notEmptyString('ubigeo');
//
//        $validator
//            ->scalar('ubigeo_dpr')
//            ->maxLength('ubigeo_dpr', 256)
//            ->notEmptyString('ubigeo_dpr');
//
//        $validator
//            ->scalar('info_busqueda')
//            ->allowEmptyString('info_busqueda');
//
//        $validator
//            ->scalar('estado')
//            ->maxLength('estado', 8)
//            ->allowEmptyString('estado');
//
//        $validator
//            ->scalar('telefonos')
//            ->maxLength('telefonos', 155)
//            ->notEmptyString('telefonos');
//
//        $validator
//            ->scalar('correo')
//            ->maxLength('correo', 155)
//            ->notEmptyString('correo');
//
//        $validator
//            ->scalar('notas')
//            ->maxLength('notas', 155)
//            ->notEmptyString('notas');

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
//        $rules->add($rules->isUnique(['ruc']), ['errorField' => 'ruc']);
//        $rules->add($rules->existsIn(['categoria_id'], 'Categorias'), ['errorField' => 'categoria_id']);
//        $rules->add($rules->existsIn(['empresa_id'], 'Empresas'), ['errorField' => 'empresa_id']);

        return $rules;
    }
}
