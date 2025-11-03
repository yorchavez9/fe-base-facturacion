<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Cotizaciones Model
 *
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\AlmacensTable&\Cake\ORM\Association\BelongsTo $Almacens
 * @property \App\Model\Table\CuentasTable&\Cake\ORM\Association\BelongsTo $Cuentas
 * @property \App\Model\Table\VentasTable&\Cake\ORM\Association\BelongsTo $Ventas
 *
 * @method \App\Model\Entity\Cotizacione newEmptyEntity()
 * @method \App\Model\Entity\Cotizacione newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Cotizacione[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cotizacione get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cotizacione findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Cotizacione patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cotizacione[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cotizacione|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cotizacione saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cotizacione[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Cotizacione[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Cotizacione[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Cotizacione[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CotizacionesTable extends Table
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

        $this->setTable('cotizaciones');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
        ]);
        $this->belongsTo('Cuentas', [
            'foreignKey' => 'cliente_id',
        ]);
        $this->belongsTo('Ventas', [
            'foreignKey' => 'venta_id',
        ]);

        $this->hasMany('CotizacionRegistros', [
            'foreignKey' => 'cotizacion_id'
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
//            ->scalar('estado')
//            ->maxLength('estado', 1)
//            ->requirePresence('estado', 'create')
//            ->notEmptyString('estado');
//
//        $validator
//            ->decimal('subtotal')
//            ->notEmptyString('subtotal');
//
//        $validator
//            ->decimal('total')
//            ->requirePresence('total', 'create')
//            ->notEmptyString('total');
//
//        $validator
//            ->dateTime('fecha_cotizacion')
//            ->requirePresence('fecha_cotizacion', 'create')
//            ->notEmptyDateTime('fecha_cotizacion');
//
//        $validator
//            ->scalar('comentarios')
//            ->requirePresence('comentarios', 'create')
//            ->notEmptyString('comentarios');
//
//        $validator
//            ->scalar('codvendedor')
//            ->maxLength('codvendedor', 64)
//            ->notEmptyString('codvendedor');

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
//        $rules->add($rules->existsIn(['usuario_id'], 'Usuarios'), ['errorField' => 'usuario_id']);
//        $rules->add($rules->existsIn(['almacen_id'], 'Almacens'), ['errorField' => 'almacen_id']);
//        $rules->add($rules->existsIn(['cuenta_id'], 'Cuentas'), ['errorField' => 'cuenta_id']);
//        $rules->add($rules->existsIn(['venta_id'], 'Ventas'), ['errorField' => 'venta_id']);

        return $rules;
    }
}
