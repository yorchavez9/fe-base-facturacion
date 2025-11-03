<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UbiProvincias Model
 *
 * @method \App\Model\Entity\UbiProvincia newEmptyEntity()
 * @method \App\Model\Entity\UbiProvincia newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\UbiProvincia> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UbiProvincia get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\UbiProvincia findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\UbiProvincia patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\UbiProvincia> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UbiProvincia|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\UbiProvincia saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\UbiProvincia>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UbiProvincia>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UbiProvincia>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UbiProvincia> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UbiProvincia>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UbiProvincia>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UbiProvincia>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UbiProvincia> deleteManyOrFail(iterable $entities, array $options = [])
 */
class UbiProvinciasTable extends Table
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

        $this->setTable('ubi_provincias');
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
            ->scalar('region_id')
            ->maxLength('region_id', 6)
            ->allowEmptyString('region_id');

        return $validator;
    }
}
