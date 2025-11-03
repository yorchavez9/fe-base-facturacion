<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UbiRegiones Model
 *
 * @method \App\Model\Entity\UbiRegione newEmptyEntity()
 * @method \App\Model\Entity\UbiRegione newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\UbiRegione> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UbiRegione get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\UbiRegione findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\UbiRegione patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\UbiRegione> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UbiRegione|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\UbiRegione saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\UbiRegione>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UbiRegione>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UbiRegione>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UbiRegione> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UbiRegione>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UbiRegione>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UbiRegione>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UbiRegione> deleteManyOrFail(iterable $entities, array $options = [])
 */
class UbiRegionesTable extends Table
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

        $this->setTable('ubi_regiones');
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

        return $validator;
    }
}
