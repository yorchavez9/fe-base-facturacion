<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Cuentas Model
 *
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\ClienteTiposTable&\Cake\ORM\Association\BelongsTo $ClienteTipos
 * @property \App\Model\Table\CanalLlegadasTable&\Cake\ORM\Association\BelongsTo $CanalLlegadas
 * @property \App\Model\Table\CotizacionesTable&\Cake\ORM\Association\HasMany $Cotizaciones
 * @property \App\Model\Table\CuentaPersonasTable&\Cake\ORM\Association\HasMany $CuentaPersonas
 *
 * @method \App\Model\Entity\Cuenta newEmptyEntity()
 * @method \App\Model\Entity\Cuenta newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Cuenta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cuenta get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cuenta findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Cuenta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cuenta[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cuenta|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cuenta saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cuenta[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Cuenta[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Cuenta[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Cuenta[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CuentasTable extends Table
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

        $this->setTable('cuentas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
        ]);
        $this->belongsTo('CuentaTipos', [
            'foreignKey' => 'cliente_tipo_id',
        ]);
        $this->belongsTo('CuentaCanalLlegadas', [
            'foreignKey' => 'canal_llegada_id',
        ]);

        $this->belongsToMany("Personas",[
            'joinTable'     =>  'cuenta_personas',
            'foreignKey'    =>  'cuenta_id',
            'sort'          =>  ['nombres' => 'ASC']
        ]);

        $this->hasMany('Cotizaciones', [
            'foreignKey' => 'cuenta_id',
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

        /*
        $validator
            ->scalar('ruc')
            ->maxLength('ruc', 11)
            ->notEmptyString('ruc');

        $validator
            ->scalar('nombre_comercial')
            ->maxLength('nombre_comercial', 128)
            ->notEmptyString('nombre_comercial');

        $validator
            ->scalar('razon_social')
            ->maxLength('razon_social', 128)
            ->notEmptyString('razon_social');

        $validator
            ->scalar('domicilio_fiscal')
            ->maxLength('domicilio_fiscal', 255)
            ->notEmptyString('domicilio_fiscal');

        $validator
            ->scalar('correo')
            ->maxLength('correo', 255)
            ->notEmptyString('correo');

        $validator
            ->scalar('celular')
            ->maxLength('celular', 255)
            ->notEmptyString('celular');

        $validator
            ->scalar('telefono')
            ->maxLength('telefono', 255)
            ->notEmptyString('telefono');

        $validator
            ->scalar('whatsapp')
            ->maxLength('whatsapp', 255)
            ->notEmptyString('whatsapp');

        $validator
            ->scalar('notas')
            ->allowEmptyString('notas');

        $validator
            ->scalar('ubigeo')
            ->maxLength('ubigeo', 6)
            ->notEmptyString('ubigeo');

        $validator
            ->scalar('ubigeo_dpr')
            ->maxLength('ubigeo_dpr', 255)
            ->notEmptyString('ubigeo_dpr');

        $validator
            ->scalar('info_busqueda')
            ->allowEmptyString('info_busqueda');

        $validator
            ->scalar('estado')
            ->maxLength('estado', 16)
            ->notEmptyString('estado');

        $validator
            ->dateTime('fecha_creacion')
            ->allowEmptyDateTime('fecha_creacion');

        $validator
            ->scalar('asesor')
            ->maxLength('asesor', 255)
            ->allowEmptyString('asesor');

        */
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
        /*
        $rules->add($rules->existsIn(['usuario_id'], 'Usuarios'), ['errorField' => 'usuario_id']);
        $rules->add($rules->existsIn(['cliente_tipo_id'], 'ClienteTipos'), ['errorField' => 'cliente_tipo_id']);
        $rules->add($rules->existsIn(['canal_llegada_id'], 'CanalLlegadas'), ['errorField' => 'canal_llegada_id']);
        */
        return $rules;
    }

    public function beforeSave($event, $entity, $options) {
        $entity->info_busqueda = "{$entity->ruc} - {$entity->razon_social} - {$entity->nombre_comercial}";
    }
}
