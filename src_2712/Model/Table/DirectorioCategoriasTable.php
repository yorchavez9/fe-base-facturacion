<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DirectorioCategorias Model
 *
 * @method \App\Model\Entity\DirectorioCategoria newEmptyEntity()
 * @method \App\Model\Entity\DirectorioCategoria newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioCategoria[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioCategoria get($primaryKey, $options = [])
 * @method \App\Model\Entity\DirectorioCategoria findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DirectorioCategoria patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioCategoria[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DirectorioCategoria|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DirectorioCategoria saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DirectorioCategoria[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DirectorioCategoria[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DirectorioCategoria[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DirectorioCategoria[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DirectorioCategoriasTable extends Table
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

        $this->setTable('directorio_categorias');
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
//            ->maxLength('nombre', 128)
//            ->allowEmptyString('nombre');

        return $validator;
    }
}
