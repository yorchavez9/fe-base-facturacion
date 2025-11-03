<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\File;
use Cake\Http\Client as httpClient;

use Cake\Core\Configure;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Http\ServerRequest;

use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\XMLSecLibs\Certificate\X509ContentType;

class ConfiguracionesController extends AppController
{

    private $documentos_pdf_ruta_save_file_upload = "";
    protected $Usuarios = null;
    protected $CuentaTipos = null;
    protected $CuentaCanalLlegadas = null;
    protected $Cuentas = null;
    protected $Personas = null;
    protected $CuentaPersonas = null;
    public function initialize(): void
    {
        parent::initialize();
        $this->documentos_pdf_ruta_save_file_upload = WWW_ROOT . '/documentos_pdf';
        $this->Usuarios = $this->fetchTable('Usuarios');
        $this->CuentaTipos = $this->fetchTable('CuentaTipos');
        $this->CuentaCanalLlegadas = $this->fetchTable('CuentaCanalLlegadas');
        $this->Cuentas = $this->fetchTable('Cuentas');
        $this->Personas = $this->fetchTable('Personas');
        $this->CuentaPersonas = $this->fetchTable('CuentaPersonas');

        $this->Authentication->allowUnauthenticated(['reactivacionSuperAdmin','apiSuspenderCuenta','loginSuperAdmin']);

    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function variables()
    {
        $varname = $this->request->getQuery('varname');
        $this->set(compact('varname'));

        $this->paginate = [
            'limit' => 10,
        ];

        $configuraciones = $this->Configuraciones->find()->where([
            'varname LIKE ' => "%$varname%",
        ]);

        $configuraciones = $this->paginate($configuraciones);

        $this->set(compact('configuraciones'));

        $top_links = [
            'title' => 'Variables Globales',
            'links' => [
                'btnNuevo' => [
                    'name' => '<i class="fa fa-plus"></i> Nueva Variable',
                    'params' => [
                        'action' => 'add'
                    ],
                ],
            ],
        ];
        $this->set(compact('top_links'));
        $this->set('view_title', 'Variables Globales');
    }



    /**
     * Delete method
     *
     * @param string|null $id Configuracion id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $configuracion = $this->Configuraciones->get($id);
        if ($this->Configuraciones->delete($configuracion)) {
            $this->Flash->success(__('The configuracion has been deleted.'));
        } else {
            $this->Flash->error(__('The configuracion could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        parent::beforeRender($event);
        $this->loadTemplateFiles();
    }


    /**
     * Elimina un valor de la tabla de configuraciones
     */
    public function ajaxDelConfig()
    {
        $varname = $this->request->getQuery("config");
        $this->setConfig($varname, "");
        exit;
    }

    public function correo()
    {
        $vars = $this->getConfigs([
            'email_header',
            'email_footer',
        ]);

        $ruta_conf = $this->emisorCtrl->getBasePathMedia(true);
        if ($this->request->is(['post', 'put', 'patch'])) {
            $data = $this->request->getData();

            $path_header = $this->saveUploadedFile($data['email_header'] , "pie_correo" , $ruta_conf . "/configuraciones");
            $path_footer = $this->saveUploadedFile($data['email_footer'] , "pie_correo" , $ruta_conf . "/configuraciones");
            if($path_header != ''){
                $this->setConfig("email_header", $path_header);
            }
            if($path_footer != ''){
                $this->setConfig("email_footer", $path_footer);
            }
            return $this->redirect(['controller' => 'Configuraciones', 'action' => 'correo']);
        }
        $this->set("data", $vars);

        $this->set("view_title", "Configuraciones");
        $top_links = [
            'title' => 'Footer de los Correos'
        ];
        $this->set(compact('top_links'));
    }

    public function branding()
    {
        $vars = $this->getConfigs([
            'global_logo',
            'global_fondo',
            'global_color_bg1',
            'global_color_txt1',
            'global_color_bg2',
            'global_color_txt2',
            'global_color_titulo_bg',
            'global_color_titulo_txt',
        ]);

        $favicon = $this->getConfig('favicon', "media/default_favicon.png");
        if (!is_file($favicon)) {
            $favicon = "media/default_favicon.png";
        }
        $this->set(compact('favicon'));

        if ($this->request->is(['post', 'put', 'patch'])) {
            $data = $this->request->getData();

            //$this->setConfig("org_logotipo",$data['color1']);
            $this->setConfig("global_color_bg1",    $data['global_color_bg1']);
            $this->setConfig("global_color_txt1",   $data['global_color_txt1']);
            $this->setConfig("global_color_bg2",    $data['global_color_bg2']);
            $this->setConfig("global_color_txt2",   $data['global_color_txt2']);
            $this->setConfig("global_color_titulo_txt",   $data['global_color_titulo_txt']);
            $this->setConfig("global_color_titulo_bg",   $data['global_color_titulo_bg']);
            $this->setConfig("login_fondo",   $data['login_fondo']);
            $this->setConfig("login_color_txt",   $data['login_color_txt']);


            $ruta_conf = "media";

            $old_logo_ruta = $this->getConfig('global_logo', "");
            $logo_ruta = $this->saveUploadedFile($data['global_logo'], "global_logo", $ruta_conf);

            $logo_ruta = ($logo_ruta == "") ? $old_logo_ruta : $logo_ruta;
            $this->setConfig('global_logo', $logo_ruta);

            $old_fondo_ruta = $this->getConfig('global_fondo', "");
            $fondo_ruta = $this->saveUploadedFile($data['global_fondo'], "global_fondo", $ruta_conf);

            $fondo_ruta = ($fondo_ruta == "") ? $old_fondo_ruta : $fondo_ruta;
            $this->setConfig('global_fondo', $fondo_ruta);

            $old_favicon_ruta = $this->getConfig('favicon', "media/default_favicon.png");
            $favicon_ruta = $this->saveUploadedFile($data['favicon'], "favicon", $ruta_conf);

            $favicon_ruta = ($favicon_ruta == "") ? $old_favicon_ruta : $favicon_ruta;
            $this->setConfig('favicon', $favicon_ruta);

            return $this->redirect(['action' => 'branding']);
        }
        $top_links = [
            'title'     =>  'Identidad Corporativa',
        ];
        $this->set("view_title", 'Configuración de Identidad de Marca');
        $this->set("vars", $vars);
        $this->set("top_links", $top_links);
    }

    public function documentos()
    {
        $top_links = [
            'title' => 'Encabezado y Pie de OS',
            'links' => [
                'btnCotis' => [
                    'name' => '<i class="fa fa-file"></i> Proformas',
                    'params' => [
                        'action' => 'cotizaciones',
                    ],
                ]
            ],
        ];

        if ($this->request->is('POST')) {
            $data = $this->request->getData();
            $doc_cabecera = $data['cabecera'];
            $doc_pie = $data['pie'];

            $this->setConfig('doc_cabecera', $doc_cabecera);
            $this->setConfig('doc_pie', $doc_pie);
        }

        $doc_cabecera = $this->getConfig('doc_cabecera', '');
        $doc_pie = $this->getConfig('doc_pie', '');

        $this->set(compact('doc_cabecera', 'doc_pie'));
        $this->set(compact('top_links'));
        $this->set("view_title", 'Configuración de Documentos');
    }

    public function cotizaciones()
    {
        $top_links = [
            'title' => 'Encabezado y Pie en Proformas',
            'links' => [
                'btnDoc' => [
                    'name' => '<i class="fa fa-file"></i> Venta',
                    'params' => [
                        'action' => 'documentos',
                    ],
                ]
            ],
        ];

        if ($this->request->is('POST')) {
            $data = $this->request->getData();
            $doc_cab = $data['cab'];
            $doc_pie = $data['pie'];


            $this->setConfig('doc_cab_coti', $doc_cab);
            $this->setConfig('doc_pie_coti', $doc_pie);
        }

        $doc_cab = $this->getConfig('doc_cab_coti', '');
        $doc_pie = $this->getConfig('doc_pie_coti', '');

        $this->set(compact('doc_pie'));
        $this->set(compact('doc_cab'));
        $this->set(compact('top_links'));
        $this->set("view_title", 'Configuración de Documentos');
    }

    public function notaVentas()
    {
        $top_links = [
            'title' => 'Footer en Nota de Ventas',
            'links' => [
                'btnDoc' => [
                    'name' => '<i class="fa fa-file"></i> M. Facturas / Boletas',
                    'params' => [
                        'action' => 'documentos',
                    ],
                ],
                'btnCotis' => [
                    'name' => '<i class="fa fa-file"></i> M. Proformas',
                    'params' => [
                        'action' => 'cotizaciones',
                    ],
                ],
            ],
        ];

        if ($this->request->is('POST')) {
            $data = $this->request->getData();
            $doc_pie = $data['pie'];

            $this->setConfig('doc_pie_nv', $doc_pie);
        }

        $doc_pie = $this->getConfig('doc_pie_nv', '');

        $this->set(compact('doc_pie'));
        $this->set(compact('top_links'));
        $this->set("view_title", 'Configuración de Documentos');
    }

    public function clearFondoLogo()
    {
        $current_logo = new File(WWW_ROOT . $this->getConfig('global_logo'));
        $current_fondo = new File(WWW_ROOT . $this->getConfig('global_fondo'));

        $current_logo->delete();
        $current_fondo->delete();

        return true;
    }


    public function eliminarMembrete($varname)
    {

        $membrete = $this->getConfig($varname, "");
        if (isset($membrete) && $membrete != "") {
            if (file_exists(WWW_ROOT . $membrete)) {
                unlink(WWW_ROOT . $membrete);
            }
            $this->setConfig($varname, "");
        }

        return $this->responseWithJson("Eliminación completada");
    }

    public function getMultipleVar(){
        $respuesta = [
            'success' => false,
            'data' => null,
            'message' => 'No se recibieron datos'
        ];
        $data = $this->request->getData('vars', []);
        if($this->request->is('POST')){
            $respuesta = [
                'success' => true,
                'data' => $this->getConfigs($data),
                'message' => 'Busqueda exitosa'
            ];
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }
    public function registrosContactos(){
        if ($this->request->is(['post', 'patch', 'put'])) {
            $data = $this->request->getData();
            if($this->cargarRegistrosJson($data['archivo'])){
                $this->Flash->success('Registros cargados.');
            }else{
                $this->Flash->error("No se pudo cargar los registros, revise si el archivo es correcto.");
            }
        }

        $this->set("view_title", "Configuraciones");

        $top_links = [
            'title' => 'Registro de los Contactos'
        ];
        $this->set(compact('top_links'));
    }
    public function descargarRegistrosContactosJson(){
        $cuentas = $this->Cuentas->find()->contain([ 'CuentaCanalLlegadas','CuentaTipos','Personas']);
        $contenido = json_encode($cuentas);
        header("Content-Type: text/plain");
        header("Content-Disposition: attachment; filename=ContactosJson.txt");
        header("Content-Length: ".strlen($contenido));
        echo $contenido;
        exit;
    }
    public function cargarRegistrosJson($archivo){
        try {
            if($_FILES['archivo']['type'] == 'text/plain') {
                $path = $this->saveUploadedFile($archivo , uniqid(), "tmpText");
                if($path != ''){
                    $archivo = fopen ( WWW_ROOT . $path , 'r');
                    $this->guardarRegistrosJson( fgets ($archivo) );
                    fclose ($archivo);
                    @unlink(WWW_ROOT . $path);
                    return true;
                }
            }
            return null;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function guardarRegistrosJson($jsonStr) {
        try {
            $obj = json_decode($jsonStr);
            foreach ($obj as $cuenta) {
                $tipo = null;
                if( isset($cuenta->cuenta_tipo) && $cuenta->cuenta_tipo ){
                    $tipo = $this->CuentaTipos->find()->where(['nombre' => $cuenta->cuenta_tipo->nombre ])->first();
                    if(!$tipo){
                        $tipo = $this->CuentaTipos->newEntity([
                            'nombre' => $cuenta->cuenta_tipo->nombre,
                            'descripcion' => $cuenta->cuenta_tipo->descripcion,
                        ]);
                        $tipo = $this->CuentaTipos->save($tipo);
                    }
                }
                $canal = null;
                if( isset($cuenta->cuenta_tipo) && $cuenta->cuenta_canal_llegada ){
                    $canal = $this->CuentaCanalLlegadas->find()->where(['nombre' => $cuenta->cuenta_canal_llegada->nombre ])->first();
                    if(!$canal){
                        $canal = $this->CuentaCanalLlegadas->newEntity([
                            'nombre' => $cuenta->cuenta_canal_llegada->nombre,
                            'descripcion' => $cuenta->cuenta_canal_llegada->descripcion,
                        ]);
                        $canal = $this->CuentaCanalLlegadas->save($canal);
                    }
                }

                $cuentaExiste = $this->Cuentas->find()->where(['ruc' => $cuenta->ruc])->first();
                if(!$cuentaExiste){
                    $cuentaSave = $this->Cuentas->newEntity([
                        'cliente_tipo_id' => $tipo ? $tipo->id : 0,
                        'canal_llegada_id' => $canal ? $canal->id : 0,
                        'ruc' => $cuenta->ruc,
                        'razon_social' => $cuenta->razon_social,
                        'domicilio_fiscal' => $cuenta->domicilio_fiscal,
                        'correo' => $cuenta->correo,
                        'celular' => $cuenta->celular,
                        'telefono' => $cuenta->telefono,
                        'whatsapp' => $cuenta->whatsapp,
                        'notas' => $cuenta->notas,
                        'ubigeo' => $cuenta->ubigeo,
                        'ubigeo_dpr' => $cuenta->ubigeo_dpr,
                        'asesor' => $cuenta->asesor,
                        'estado' => $cuenta->estado,
                        'usuario_id' => $this->usuario_sesion['id'] ?? 0
                    ]);
                    $cuentaSave = $this->Cuentas->save($cuentaSave);
                    if($cuentaSave){
                        if(isset($cuenta->personas)){
                            foreach ( $cuenta->personas as $persona ) {
                                $personaSave = $this->Personas->newEntity([
                                    'dni' => $persona->dni,
                                    'nombres' => $persona->nombres,
                                    'apellidos' => $persona->apellidos,
                                    'cargo_empresa' => $persona->cargo_empresa,
                                    'correo' => $persona->correo,
                                    'fecha_nacimiento' => $persona->fecha_nacimiento,
                                    'telefono' => $persona->telefono,
                                    'anexo' => $persona->anexo,
                                    'celular_trabajo' => $persona->celular_trabajo,
                                    'celular_personal' => $persona->celular_personal,
                                    'info_busqueda' => $persona->info_busqueda,
                                    'domicilio' => $persona->domicilio,
                                    'clave' => $persona->clave,
                                    'whatsapp' => $persona->whatsapp,
                                ]);
                                $personaSave = $this->Personas->save($personaSave);
                                if($personaSave){
                                    $rel = $this->CuentaPersonas->newEntity([
                                        'cuenta_id' => $cuentaSave->id,
                                        'persona_id' => $personaSave->id,
                                        'cargo' => isset($persona->_joinData) ? $persona->_joinData->cargo : ''
                                    ]);
                                    $this->CuentaPersonas->save($rel);
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $th) {

        }
    }

    public function sendMailTest(){
        $respuesta = [
            'success' => false,
            'data' => null,
            'message' => 'No se recibieron datos'
        ];
        try {
            $data = $this->request->getData();
            if(
                $this->request->is('POST')
                && isset($data['correo_destino']) && $data['correo_destino'] != ''
                && isset($data['mensaje']) && $data['mensaje'] != ''
            ){
                $emailCtrl = new EmailController();

                if ($emailCtrl->sendMessage($data['correo_destino'], $data['mensaje'])){
                    $respuesta = [
                        'success' => true,
                        'data' => null,
                        'message' => 'Correo enviado.'
                    ];
                }else{
                    $respuesta = [
                        'success' => false,
                        'data' => null,
                        'message' => 'Ocurrió un error.'
                    ];
                }
            }
        } catch (\Throwable $th) {
            $respuesta = [
                'success' => false,
                'data' => $th->getMessage(),
                'message' => 'Ocurrió un error.'
            ];
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    public function verLogs(){

        $log = "";
        $path_log = ROOT. DS . 'logs' . DS . 'error.log' ;
        if($this->request->getQuery('show-log' , '') == '1'){
            if(!file_exists($path_log)){
                $this->Flash->error("No se puede mostrar el archivos, logs eliminados.");
                return $this->redirect(['action' => 'verLogs']);
            }
            $log = file_get_contents( $path_log );
            $log = str_replace("\n", "<br>" ,$log);
        }
        $this->set("view_title", "Configuraciones");
        $top_links = [
            'title' => 'Ver Logs'
        ];
        $this->set('log_str', $log);
        $this->set(compact('top_links'));
    }
    public function delLogs(){
        $dir = ROOT. DS . 'logs';
        if ( $this->borrar_directorio($dir) ) {
            $this->Flash->success('Eliminación exitosa.');
        } else {
            $this->Flash->error("Ocurrió un error eliminando los archivos.");
        }
        return $this->redirect(['action' => 'verLogs']);
    }
    public function eliminarTmp(){
        $this->set("view_title", "Configuraciones");

        $top_links = [
            'title' => 'Eliminar archivos temporales'
        ];
        $this->set(compact('top_links'));
    }
    public function delTmp(){
        $dir = ROOT. DS . 'tmp';
        if ( $this->borrar_directorio($dir) ) {
            $this->Flash->success('Eliminación exitosa.');
        } else {
            $this->Flash->error("Ocurrió un error eliminando los archivos.");
        }
        return $this->redirect(['action' => 'eliminarTmp']);
    }

    function borrar_directorio($dirname) {
        try {
            //si es un directorio lo abro
            if (!is_dir($dirname))
                return true;
            $dir_handle = opendir($dirname);
            //recorro el contenido del directorio fichero a fichero
            while($file = readdir($dir_handle)) {
                if ($file != "." && $file != "..") {
                        //si no es un directorio elemino el fichero con unlink()
                    if (!is_dir($dirname."/".$file))
                        @unlink($dirname."/".$file);
                    else //si es un directorio hago la llamada recursiva con el nombre del directorio
                        $this->borrar_directorio($dirname.'/'.$file);
                }
            }
            closedir($dir_handle);
            //elimino el directorio que ya he vaciado
            //  rmdir($dirname); ERROR ENCONTRADO NO SE ELIMNIRARAN LAS CARPETAS PERO SI SU CONTENIDO
            return true;
        } catch (\Throwable $th) {
            return false;
        }

    }

    public function reactivacionSuperAdmin() {

        $this->viewBuilder()->setLayout("none");
        $data = $this->request->getData();
        if($this->request->is('post') ){
            if ( isset($data['user']) && $data['user']  != '' ) {
                $usuario = $this->Usuarios->find()->where([ 'usuario' => $data['user'] ])->first();
                    if ( $usuario && (new DefaultPasswordHasher)->check( $data['pwd'],  $usuario->clave) && $usuario->rol == 'SUPERADMIN') {
                    $this->setConfig('sistema_activo', $data['activo'] ?? '0' );
                    $this->aumentarPeriodoRenovacion();
                    $this->Flash->success(
                        $data['activo'] ? 'Sistema reactivdao. Inice sesión'
                        : 'Sistema suspendido'
                        );
                    return $this->redirect([ 'controller' => 'usuarios', 'action' => 'iniciarSesion']);
                }else{
                    $this->Flash->error('Usuario o clave incorrectos');
                }
            }else{
                $this->Flash->error('Usuario o clave incorrectos');
            }
            return $this->redirect(['action' => 'reactivacionSuperAdmin']);
        }
    }
    public function apiReactivacionSuperAdmin(){
        $respuesta = [
            'success' => false,
            'data' => '',
            'message' => 'No se enviaron datos',
        ];
        $data = $this->request->getData();
        if ($this->request->is('POST')) {
            try {
                if ( isset($data['user']) && $data['user']  != '' ) {
                    $usuario = $this->Usuarios->find()->where([ 'usuario' => $data['user'] ])->first();
                    if ( $usuario && (new DefaultPasswordHasher)->check( $data['pwd'],  $usuario->clave) && $usuario->rol == 'SUPERADMIN') {
                        $this->setConfig('sistema_activo', $data['activo'] ?? '0' );
                        $respuesta = [
                            'success' => true,
                            'data' => '',
                            'message' => 'Sistema puesto como inactivo',
                        ];
                    }else{
                        $respuesta = [
                            'success' => false,
                            'data' => '',
                            'message' => 'Usuario o clave incorrecto',
                        ];
                    }
                }
            } catch (\Throwable $e) {
                $respuesta = [
                    'success' => false,
                    'data' => '',
                    'message' => 'ERROR: ' . $e->getMessage(),
                ];
            }
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }
    public function loginSuperAdmin(){
        $this->viewBuilder()->setLayout("none");
        $data = $this->request->getData();
        if($this->request->is('post') ){
            if ( isset($data['user']) && $data['user']  != '' ) {
                $usuario = $this->Usuarios->find()->where([ 'usuario' => $data['user'] ])->first();
                if ( $usuario && (new DefaultPasswordHasher)->check( $data['pwd'],  $usuario->clave) && $usuario->rol == 'SUPERADMIN') {
                    $usuario->token = uniqid();
                    $this->Usuarios->save($usuario);
                    $this->Flash->success('Login');
                    // return $this->redirect([ 'controller' => 'usuarios', 'action' => 'iniciarSesion' , 'param' => ['token' => $usuario->token]]);
                    return $this->redirect([ 'action' => 'loginSuperAdmin' , '?' => ['sa-token' => $usuario->token]]);
                }else{
                    $this->Flash->error('Usuario o clave incorrectos');
                }
            }else{
                $this->Flash->error('Usuario o clave incorrectos');
            }
            return $this->redirect(['action' => 'loginSuperAdmin']);
        }
        $this->set('usuario', null);
    }
    public function limpiezaSistema(){
        $this->set("view_title", "Configuraciones");

        $top_links = [
            'title' => 'Limpieza del sistema'
        ];
        $this->set(compact('top_links'));
    }
    private function getTableColumns($collection , $tabla){
        try {
            return $collection->describe($tabla)->columns();
        } catch (\Throwable $th) {
            return null;
        }

    }
    public function resetSAdmin(){
        $usuario = $this->Usuarios->find()->where(['ROL' => 'SUPERADMIN'])->order(['id' => 'ASC'])->first();
        if($usuario){
            $clave = uniqid();
            $usuario->clave = $clave;
            $usuario = $this->Usuarios->save($usuario);
            $emailCtrl = new EmailController();
            $emailCtrl->resetSuperAdmin($usuario->usuario, $clave);
            $this->Flash->success(__('Se envio un correo con la nueva clave.'));
        }else{
            $this->Flash->error(__('No se encontró el usuario.'));
        }
        return $this->redirect(['controller' => 'usuarios', 'action' => 'iniciar-sesion']);
    }
    public function emisores(){
        $this->set("view_title", "Emisores");
        $top_links = [
            'title' => 'Configurar emisores'
        ];
        $this->set(compact('top_links'));
    }

    public function setupPfxCert($ruta = ""){
            // $ruta = ROOT . '\appdata\certificado/certificado.p12';
            
        try {
            $ruta_certificado = ROOT . '/appdata/'. $ruta;
            $pfx = file_get_contents($ruta_certificado);
            $pfx = file_get_contents($ruta_certificado);
            $certificate = new X509Certificate($pfx, $this->getConfig('emisor_certificado_clave',''));
            $pem = $certificate->export(X509ContentType::PEM);
            $cer = $certificate->export(X509ContentType::CER);

            $ruta_pem = "certificado/certificado.pem";
            $ruta_cer = "certificado/certificado.cer";

            file_put_contents(ROOT . '/appdata/' . $ruta_pem, $pem);
            file_put_contents(ROOT . '/appdata/' . $ruta_cer, $cer);

            // obtenemos la fecha de vencimiento
            $certinfo = openssl_x509_parse($cer);
            $this->setConfig("emisor_pem_certificado",   $ruta_pem);
            $this->setConfig("emisor_cer_certificado",   $ruta_cer);
            $this->setConfig("certificado_vencimiento",   date("Y-m-d", $certinfo['validTo_time_t']) );
            return true;
        }catch(\Exception $ex){
            return false;
        }
    }

    public function series(){
        $this->set("view_title", "Series");
        $top_links = [
            'title' => 'Configurar series'
        ];

        $top_links['functions'] = [
            'btnA' => [
                'function'  =>  'Series.add()',
                'name'  =>    "<i class='fas fa-plus fa-fw'></i> Serie" ,
            ]
        ];
        $this->set(compact('top_links'));
    }
}
