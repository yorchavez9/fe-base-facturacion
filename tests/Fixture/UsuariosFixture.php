<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsuariosFixture
 */
class UsuariosFixture extends TestFixture
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
                'id' => 1,
                'perfil_id' => 1,
                'almacen_id' => 1,
                'usuario' => 'Lorem ipsum dolor sit amet',
                'clave' => 'Lorem ipsum dolor sit amet',
                'nombre' => 'Lorem ipsum dolor sit amet',
                'permisos' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'token' => 'Lorem ipsum dolor sit amet',
                'rol' => 'Lorem ipsum do',
                'celular' => 'Lorem ipsum do',
                'whatsapp' => 'Lorem ipsum do',
                'correo' => 'Lorem ipsum dolor sit amet',
                'created' => '2024-10-21 21:23:05',
                'modified' => '2024-10-21 21:23:05',
            ],
        ];
        parent::init();
    }
}
