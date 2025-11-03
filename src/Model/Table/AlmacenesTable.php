<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Almacenes Model
 *
 * @method \App\Model\Entity\Almacene newEmptyEntity()
 * @method \App\Model\Entity\Almacene newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Almacene> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Almacene get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Almacene findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Almacene patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Almacene> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Almacene|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Almacene saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Almacene>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Almacene>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Almacene>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Almacene> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Almacene>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Almacene>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Almacene>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Almacene> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AlmacenesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('almacenes');
        $this->setDisplayField('id');
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
            ->scalar('nombre')
            ->maxLength('nombre', 255)
            ->allowEmptyString('nombre');

        $validator
            ->scalar('correo')
            ->maxLength('correo', 128)
            ->allowEmptyString('correo');

        $validator
            ->scalar('departamento')
            ->maxLength('departamento', 128)
            ->allowEmptyString('departamento');

        $validator
            ->scalar('provincia')
            ->maxLength('provincia', 128)
            ->allowEmptyString('provincia');

        $validator
            ->scalar('distrito')
            ->maxLength('distrito', 128)
            ->allowEmptyString('distrito');

        $validator
            ->scalar('urbanizacion')
            ->maxLength('urbanizacion', 255)
            ->allowEmptyString('urbanizacion');

        $validator
            ->scalar('codigo')
            ->maxLength('codigo', 4)
            ->allowEmptyString('codigo');

        $validator
            ->scalar('direccion')
            ->allowEmptyString('direccion');

        $validator
            ->scalar('telefono')
            ->maxLength('telefono', 16)
            ->allowEmptyString('telefono');

        $validator
            ->scalar('map_lat')
            ->maxLength('map_lat', 255)
            ->allowEmptyString('map_lat');

        $validator
            ->scalar('map_len')
            ->maxLength('map_len', 255)
            ->allowEmptyString('map_len');

        $validator
            ->scalar('ubigeo')
            ->maxLength('ubigeo', 6)
            ->allowEmptyString('ubigeo');

        $validator
            ->scalar('ubigeo_dpr')
            ->maxLength('ubigeo_dpr', 255)
            ->allowEmptyString('ubigeo_dpr');

        $validator
            ->scalar('es_tienda')
            ->maxLength('es_tienda', 1)
            ->allowEmptyString('es_tienda');

        $validator
            ->scalar('es_publico')
            ->maxLength('es_publico', 1)
            ->allowEmptyString('es_publico');

        $validator
            ->scalar('info_busqueda')
            ->allowEmptyString('info_busqueda');

        return $validator;
    }
}
