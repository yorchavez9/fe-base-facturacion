<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConfiguracionesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConfiguracionesTable Test Case
 */
class ConfiguracionesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ConfiguracionesTable
     */
    protected $Configuraciones;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Configuraciones',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Configuraciones') ? [] : ['className' => ConfiguracionesTable::class];
        $this->Configuraciones = $this->getTableLocator()->get('Configuraciones', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Configuraciones);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ConfiguracionesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
