<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UbiDistritosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UbiDistritosTable Test Case
 */
class UbiDistritosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UbiDistritosTable
     */
    protected $UbiDistritos;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.UbiDistritos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('UbiDistritos') ? [] : ['className' => UbiDistritosTable::class];
        $this->UbiDistritos = $this->getTableLocator()->get('UbiDistritos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->UbiDistritos);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\UbiDistritosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
