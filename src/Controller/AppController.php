<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use DateTime;
use Cake\Controller\ControllerFactory;
use Cake\Http\ServerRequest;
use Cake\Http\Response;
use Cake\Core\Configure; 
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public $usuario_sesion = false;
    public $tipo_plan = false;

    public $datos_empresa = false;

    protected $Usuarios = null;
    protected $UbiDistritos = null;
    protected $version = false;

    public $metodos_pago =
    [
        'EFECTIVO'      =>  'Efectivo',
        'BCP'           =>  'Abono en BCP',
        'IBK'           =>  'Abono en Interbank',
        'BN'            =>  'Abono en Banco de la Nación',
        'YAPE'          =>  'Yape',
        'PLIN'          =>  'Plin',
        'OTRO'          =>  'Otro'
    ];

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Flash');

        // establecemos el usuario actual
        $this->usuario_sesion = $this->Authentication->getIdentity();
        $this->tipo_plan = $this->getConfig('fe_plan', "LITE");
        $this->Usuarios = $this->fetchTable('Usuarios');
        $this->UbiDistritos = $this->fetchTable('UbiDistritos');

        $this->datos_empresa = [
            "ruc"   =>  "10459277124",
            "razon_social"  =>  "Chinita Diseño y Confeccion",
        ];

        // seteamos la version
        $this->version = $this->getCurrentVersion();
        $this->set('version', $this->version);
        
        $this->set('flag_dev', Configure::read('debug'));
    }

    public function beforeFilter(EventInterface $event)
    {

        return parent::beforeFilter($event);
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
        $this->loadTemplateFiles();

        $brand_data = [
            'global_logo'   =>  $this->getConfig("global_logo"),
            'global_fondo'  =>  $this->getConfig("global_fondo"),
            'global_color_bg1'  =>  $this->getConfig("global_color_bg1", "#edf3fc"),
            'global_color_txt1' =>  $this->getConfig("global_color_txt1", "#000000"),
            'global_color_bg2'  =>  $this->getConfig("global_color_bg2", "#5f0a6a"),
            'global_color_txt2' =>  $this->getConfig("global_color_txt2", "#FFFFFF"),
            'global_color_titulo_bg'    =>  $this->getConfig("global_color_titulo_bg", "#bba3bf"),
            'global_color_titulo_txt'   =>  $this->getConfig("global_color_titulo_txt", "#FFFFFF"),
            'default_favicon' => $this->getConfig("default_favicon"),
            'favicon' => $this->getConfig("favicon")
        ];

        $whatsapp_active = $this->getConfig('whatsapp_active', "0");
        $this->set(compact('whatsapp_active'));
        // enviamos a la vista
        $this->set("usuario_sesion", $this->usuario_sesion);
        $this->set("global_brand_data", $brand_data);
        $this->set('fe_servidor', $this->getConfig('fe_servidor'));



        $this->set("datos_empresa", $this->datos_empresa);
    }

    public function getConfigs($varname_array)
    {
        $this->fetchTable("Configuraciones");
        $orgvals = $this->fetchTable("Configuraciones")->find()->where(['varname IN' => $varname_array]);
        $values = [];
        foreach ($orgvals as $oval) {
            $values[$oval->varname] = $oval->varvalue;
        }

        $variables = [];
        foreach ($varname_array as $var) {
            $variables[$var] = isset($values[$var]) ? $values[$var] : false;
        }
        return $variables;
    }


    /**
     * Establece una variable global
     * @param string $varname
     * @param string $varvalue
     * @return type
     */
    public function setConfig($varname, $varvalue)
    {
        $this->fetchTable("Configuraciones");
        // extraemos la variable segun nombre y oid
        $orgval = $this->fetchTable("Configuraciones")->find()->where(['varname' => $varname])->first();

        // si no existe, creamos y establecemos el nombre
        if (!$orgval) {
            $orgval = $this->fetchTable("Configuraciones")->newEmptyEntity();
            $orgval->varname = $varname;
        }

        // establecemos el valor
        $orgval->varvalue = $varvalue;

        // guardamos
        $orgval = $this->fetchTable("Configuraciones")->save($orgval);

        //  por si a caso retornamos
        return $orgval;
    }


    public function getConfig($varname, $valor_por_defecto = false)
    {
        $this->fetchTable("Configuraciones");
        // falta poner la condicion OID para filtrar por organizacion
        $orgval = $this->fetchTable("Configuraciones")->find()->where(['varname' => $varname])->first();
        if (!$orgval) {
            return $valor_por_defecto;
        } else {
            return $orgval->varvalue;
        }
    }

    /**
     * carga las ubicaciones (ubigeo)
     */
    public function getUbicaciones()
    {
        $distritos = $this->UbiDistritos->find();
        foreach ($distritos as $d) {
            $d->value = $d->info_busqueda;
        }
        return $distritos;
    }

    /**
     * Devuelve la extensión del archivo
     * ésta funcion es util para renombrar archivos y que no se pierda la posicion
     * @param $filename
     * @return false|string
     */
    public function getFileExt($filename)
    {
        $pos = strrpos($filename, '.');
        return substr($filename, $pos + 1);
    }

    protected function loadTemplateFiles()
    {
        $controller = $this->request->getParam("controller");
        $action = $this->request->getParam("action");

        if (!$action){
            return false;
        }
        $action = \Cake\Utility\Inflector::underscore($action);

        //echo $script_filepath = APP . "Template" . DS . $controller . DS . $action . ".js";
        //echo $styles_filepath = APP . "Template" . DS . $controller . DS . $action . ".css";

        $script_filepath = APP;
        $script_filepath = $script_filepath . "templates" . DS . $controller . DS . $action . ".js";

        $styles_filepath = APP;
        $styles_filepath = $styles_filepath . "templates" . DS . $controller . DS . $action . ".css";

        $script = file_exists($script_filepath) ? file_get_contents($script_filepath) : "";
        $styles = file_exists($styles_filepath) ? file_get_contents($styles_filepath) : "";

        $this->set("script", $script);
        $this->set("styles", $styles);
    }

    public function generateUniqueFileName($filename)
    {

        $newname = microtime(true) . $filename;
        $newname = str_replace([' ', '.'], ["_"], $newname);
        return $newname;

    }

    /**
     * Obtiene la version de la aplicacion
     * @return false|string
     */
    public function getCurrentVersion(){
        $version = file_get_contents(WWW_ROOT . "version.txt");
        return $version;
    }

    /**
     * Escribe un archivo en la carpeta webroot/public
     * @param $uploaded \Laminas\Diactoros\UploadedFile
     * @param $nombre
     * @param $myfolder
     * @param $is_public si se almacena en una ruta interna o externa
     * @return string
     */
    public function saveUploadedFile($uploaded, $nombre, $myfolder, $is_public = true)
    {
        try {
            if ($uploaded->getError() == 0) {
                $ext = pathinfo($uploaded->getClientFilename(), PATHINFO_EXTENSION);

                // si es publico, la ruta incluye el WWW
                // si es privado la ruta incluye el ROOT y ruta absoluta del servidor

                $ruta = ($is_public) ? WWW_ROOT . $myfolder : ROOT . "/" . 'appdata' . "/" . $myfolder;

                if (!is_dir($ruta)){ mkdir($ruta); chmod($ruta, 0777);  }

                $full_file_path = $ruta . "/" . $nombre . '.' . $ext;

                $uploaded->moveTo($full_file_path);

                return   $myfolder . "/" . $nombre . '.' . $ext;

            } else {
                return "";
            }
        } catch (\Throwable $e) {
            return "";
        }
    }

    public function changeDefaultDatabase($db)
    {
        $db_username = \Cake\Datasource\ConnectionManager::getConfig('default')['username'];
        $db_password = \Cake\Datasource\ConnectionManager::getConfig('default')['password'];
        \Cake\Datasource\ConnectionManager::drop('default');
        \Cake\Datasource\ConnectionManager::setConfig('default', [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => 'localhost',
            'username'  =>  $db_username,
            'password'  =>  $db_password,
            'database' => $db,
            'encoding' => 'utf8mb4',
            'timezone' => 'UTC',
            'cacheMetadata' => true,
        ]);
    }

    public function getAdviceMessage(){
        $mensaje = substr(file_get_contents(WWW_ROOT . 'nuevo_aviso.txt'),26);
        return $mensaje;
    }

    public function incrementarConteo(){
        $conteo_actual = intval($this->getConfig('contador_comprobantes',0));
        $yearMonth = date("Ym");
        $conteo_mes_actual = $this->getConfig('contador_'. $yearMonth, 0);
        $this->setConfig('contador_comprobantes', $conteo_actual +1);
        $this->setConfig('contador_'. $yearMonth, $conteo_mes_actual +1);
    }
    public function checkContador(){
        $conteo_actual  = intval($this->getConfig('contador_comprobantes',0));
        $max_cant       = intval($this->getConfig('cant_max_comprobantes_fact',0));

        return $conteo_actual < $max_cant;
    }


    public function cargarEstadosComprobante($data)
    {
        $estados =
            [
                'estadocpe' => "",
                'estadoruc' =>  "",
                'condomiruc'=>  "",
            ];


        $estadocpe  =   "";
        $estadoruc  =   "";
        $condomiruc =   "";

        switch ($data->getEstadoCp()) {
            case '0': $estadocpe = 'NO EXISTE'; break;
            case '1': $estadocpe = 'ACEPTADO'; break;
            case '2': $estadocpe = 'ANULADO'; break;
            case '3': $estadocpe = 'AUTORIZADO'; break;
            case '4': $estadocpe = 'NO AUTORIZADO'; break;
        }

        $estados['estadocpe'] = $estadocpe;


        return $estados;

    }

    /**
     * Metodos para nueva implementacion
     * con Vue
     */
    public function responseWithJson($data = null, $message = '', $success = true){
        $respuesta =
        [
            'success'   =>  $success,
            'data'      =>  $data,
            'message'   =>  $message
        ];

        if($this->getPaginatorData()){
            $respuesta['pagination']    =   $this->getPaginatorData();
        }

        return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode($respuesta));

    }

    public function responseWithError($message,$data= null){
        $respuesta =
        [
            'success'   =>  false,
            'data'      =>  $data,
            'message'   =>  $message
        ];

        return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode($respuesta));

    }

    public function getPaginatorData()
    {
        $paginator = isset($this->paginator) ? $this->paginator : null;

        if(!$paginator){
            return null;
        }

        return [
            'current' => $paginator->getPage(),
            'total' => $paginator->getPageCount(),
            'items' => $paginator->getItemCount(),
            'items_per_page' => $paginator->getItemsPerPage(),
            'is_first' => $paginator->isFirst(),
            'is_last' => $paginator->isLast(),
        ];
    }

    public function prepareData($model, $keys, $data, $required = false)
    {
        foreach ($keys as $key) {

            if ($required && !isset($data[$key])) {
                $this->responseWithError('El campo ' . $key . ' es requerido', false);
            }

            $model->{$key} = isset($data[$key]) ? $data[$key] : $model->{$key};
        }

        return $model;
    }

    public function checkPermision($method ='GET'){
        if ($method) {
            if(!$this->request->is($method)) {
                $this->responseWithError('Metodo no autorizado', false);
            }
        }
    }


    function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function getPathPdf($myfolder) {
        $ruta =  ROOT . "/" . 'appdata' . "/" . $myfolder . "/";
        if (!is_dir($ruta)){ mkdir($ruta); chmod($ruta, 0777);  }
        return $ruta;
    }
    function getPathBarCode() {
        $ruta =  WWW_ROOT . 'media' . DS . 'barcode' . DS ;
        if (!is_dir($ruta)){ mkdir($ruta); chmod($ruta, 0777);  }
        return $ruta;
    }
}
