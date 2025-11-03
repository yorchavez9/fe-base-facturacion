<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * MontosManager component
 */
class MontosManagerComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected array $_defaultConfig = [];



    public function redondearMontos($numero,$dec)
    {
        $d = 1;
        for($i = 0; $i < $dec;$i++)
        {
            $d .= "0";
        }

        return round(floatval($numero) * intval($d)) / intval($d);

    }
}
