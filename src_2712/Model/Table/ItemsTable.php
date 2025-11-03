<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Items Model
 *
 * @property \App\Model\Table\MarcasTable&\Cake\ORM\Association\BelongsTo $Marcas
 * @property \App\Model\Table\CategoriasTable&\Cake\ORM\Association\BelongsTo $Categorias
 * @property \App\Model\Table\SunatTipoTributosTable&\Cake\ORM\Association\BelongsTo $SunatTipoTributos
 * @property \App\Model\Table\CompraRegistrosTable&\Cake\ORM\Association\HasMany $CompraRegistros
 * @property \App\Model\Table\CotizacionRegistrosTable&\Cake\ORM\Association\HasMany $CotizacionRegistros
 * @property \App\Model\Table\ItemFotosTable&\Cake\ORM\Association\HasMany $ItemFotos
 * @property \App\Model\Table\ItemRelsTable&\Cake\ORM\Association\HasMany $ItemRels
 * @property \App\Model\Table\StockTable&\Cake\ORM\Association\HasMany $Stock
 * @property \App\Model\Table\StockHistorialTable&\Cake\ORM\Association\HasMany $StockHistorial
 * @property \App\Model\Table\VentaRegistrosTable&\Cake\ORM\Association\HasMany $VentaRegistros
 * @property \App\Model\Table\WebOrdenPedidoItemsTable&\Cake\ORM\Association\HasMany $WebOrdenPedidoItems
 *
 * @method \App\Model\Entity\Item newEmptyEntity()
 * @method \App\Model\Entity\Item newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Item[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Item get($primaryKey, $options = [])
 * @method \App\Model\Entity\Item findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Item patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Item[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Item|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Item saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Item[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Item[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Item[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Item[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ItemsTable extends Table
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

        $this->setTable('items');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ItemMarcas', [
            'foreignKey'    => 'marca_id',
            'propertyName'  => 'Marca'
        ]);
        $this->belongsTo('ItemCategorias', [
            'foreignKey'    => 'categoria_id',
            //'joinType'      => 'INNER',
            'propertyName'  =>  'Categoria'
        ]);

        $this->hasOne("Stock",[
            'foreignKey'    =>  'item_id'
        ]);

        $this->hasMany("ItemRels",[
            'foreignKey'    =>  'item_id'
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
