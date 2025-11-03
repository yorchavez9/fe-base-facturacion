<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UbiDistritosFixture
 */
class UbiDistritosFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 'c37fcbe0-b82b-49d6-b6e0-52dbd42ea402',
                'nombre' => 'Lorem ipsum dolor sit amet',
                'info_busqueda' => 'Lorem ipsum dolor sit amet',
                'provincia_id' => 'Lore',
                'region_id' => 'Lore',
            ],
        ];
        parent::init();
    }
}
