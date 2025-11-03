<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Personas Model
 *
 * @property \App\Model\Table\CuentaPersonasTable&\Cake\ORM\Association\HasMany $CuentaPersonas
 *
 * @method \App\Model\Entity\Persona newEmptyEntity()
 * @method \App\Model\Entity\Persona newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Persona[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Persona get($primaryKey, $options = [])
 * @method \App\Model\Entity\Persona findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Persona patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Persona[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Persona|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Persona saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Persona[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Persona[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Persona[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Persona[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PersonasTable extends Table
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

        $this->setTable('personas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('CuentaPersonas', [
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
 /*       $validator
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('dni')
            ->maxLength('dni', 8)
            ->allowEmptyString('dni');

        $validator
            ->scalar('nombres')
            ->maxLength('nombres', 255)
            ->allowEmptyString('nombres');

        $validator
            ->scalar('apellidos')
            ->maxLength('apellidos', 255)
            ->allowEmptyString('apellidos');

        $validator
            ->scalar('cargo_empresa')
            ->maxLength('cargo_empresa', 64)
            ->allowEmptyString('cargo_empresa');

        $validator
            ->scalar('correo')
            ->maxLength('correo', 255)
            ->allowEmptyString('correo');

        $validator
            ->scalar('telefono')
            ->maxLength('telefono', 255)
            ->allowEmptyString('telefono');

        $validator
            ->scalar('anexo')
            ->maxLength('anexo', 255)
            ->allowEmptyString('anexo');

        $validator
            ->scalar('celular_trabajo')
            ->maxLength('celular_trabajo', 255)
            ->allowEmptyString('celular_trabajo');

        $validator
            ->scalar('celular_personal')
            ->maxLength('celular_personal', 255)
            ->allowEmptyString('celular_personal');

        $validator
            ->scalar('info_busqueda')
            ->maxLength('info_busqueda', 255)
            ->notEmptyString('info_busqueda');

        $validator
            ->scalar('direccion')
            ->maxLength('direccion', 128)
            ->notEmptyString('direccion');

        $validator
            ->scalar('clave')
            ->maxLength('clave', 128)
            ->notEmptyString('clave');
*/
        return $validator;
    }

    public function beforeSave($event, $entity, $options) {
        $entity->info_busqueda = "{$entity->dni} - {$entity->nombres} {$entity->apellidos} - {$entity->celular_personal} {$entity->celular_trabajo}";
    }
}
