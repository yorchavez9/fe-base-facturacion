<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UbiDistritos Model
 *
 * @method \App\Model\Entity\UbiDistrito newEmptyEntity()
 * @method \App\Model\Entity\UbiDistrito newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\UbiDistrito> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UbiDistrito get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\UbiDistrito findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\UbiDistrito patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\UbiDistrito> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UbiDistrito|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\UbiDistrito saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\UbiDistrito>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UbiDistrito>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UbiDistrito>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UbiDistrito> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UbiDistrito>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UbiDistrito>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UbiDistrito>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UbiDistrito> deleteManyOrFail(iterable $entities, array $options = [])
 */
class UbiDistritosTable extends Table
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

        $this->setTable('ubi_distritos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->scalar('info_busqueda')
            ->maxLength('info_busqueda', 255)
            ->allowEmptyString('info_busqueda');

        $validator
            ->scalar('provincia_id')
            ->maxLength('provincia_id', 6)
            ->allowEmptyString('provincia_id');

        $validator
            ->scalar('region_id')
            ->maxLength('region_id', 6)
            ->allowEmptyString('region_id');

        return $validator;
    }
}
