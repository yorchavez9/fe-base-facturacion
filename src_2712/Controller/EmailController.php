<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\ServerRequest;
use Cake\Mailer\Mailer;
use Cake\Routing\Router;
use Cake\Mailer\TransportFactory;

/**
 * Categorias Controller
 *
 * @property \App\Model\Table\CategoriasTable $Categorias
 *
 * @method \App\Model\Entity\Categoria[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmailController extends AppController
{
   
    private $emisoresCtrl = null;
    private $correo_soporte = '';
    private $transport_id = 'default';
    private $transport_smtp = '';
    private $transport_puerto = '';
    private $transport_usuario = '';
    private $transport_clave = '';
    private $transport_nombre = '';

    protected $Ventas = null;
    protected $Cuentas = null;
    protected $Personas = null;
    protected $Usuarios = null;
    protected $Cotizaciones = null;
    public function initialize(): void
    {
        parent::initialize();

        $this->Ventas = $this->fetchTable('Ventas');
        $this->Cuentas = $this->fetchTable('Cuentas');
        $this->Personas = $this->fetchTable('Personas');
        $this->Usuarios = $this->fetchTable('Usuarios');
        $this->Cotizaciones = $this->fetchTable('Cotizaciones');

        $this->correo_soporte = 'soporte@factura24.pe';
        $this->cambiarConfiguracion();
    }

    /**
     * Envia los archivos XML de una venta
     * Este correo se envia desde la direccion infomail@gerware.one
     * @return \Cake\Http\Response|null
     *
     */
    public function enviarComprobante()
    {

        $response = [
            'success'   =>  false,
            'data'      =>  'no-data',
            'message'   =>  'enviar datos por POST porfis'
        ];


        if ($this->request->is("POST")) {

            // estas variables se debe recibir por parametro
            // solo correo y el ID, ya que el emisor y ruc se extrae de la factura

            $cpe_id = $this->request->getData('cpe_id');
            $cpe_tipo = $this->request->getData('cpe_tipo'); // venta, nota_cd, guia_remision
            $correo = $this->request->getData('correo');

            //obtenemos los medios de contacto
            $conf = $this->getConfigs(['correo', 'celular', 'whatsapp_cel']);

            //            $venta_id = 7;
            //            $correo = 'ali.reyes@gerware.com';

            $logo = $this->getConfig('global_logo');

            // obtenemos la factura
            // aqui se envia tambien guia de remision, nc, nd

            $files = [];

            $es_nv = true;
            $factura = $this->Ventas->get($cpe_id);

            $ruta_documento = "{$factura->documento_serie}-{$factura->documento_correlativo}-a4.pdf";
            $ruta_factura_pdf = WWW_ROOT . DS . $ruta_documento;
            $files[] = $ruta_factura_pdf;




            $array_logo = ['logo.png' => [
                'file' => WWW_ROOT . $logo,
                'mimetype' => 'image/png',
                'contentId' => 'logo'
            ]];

            $files = array_merge($files, $array_logo);
            $subject = "VENTA" . $factura->documento_serie . '-' . $factura->documento_correlativo;

            $email = new Mailer();
            $email->setTransport($this->transport_id);
            $email->setFrom($this->transport_usuario, 'Venta' );
            $email->viewBuilder()
                ->setTemplate( "notif_cliente_notaventa" )
                ->setLayout("notif_cliente");

            $email->setTo($correo)
                ->setSubject("{$cpe_tipo} {$subject} | " . $factura->cliente_razon_social)
                ->setEmailFormat('html')
                ->setViewVars([
                    'cpe'     =>  $factura,
                    'emisor' => $this->datos_empresa['razon_social'],
                    'conf' => $conf
                ])
                ->setAttachments($files)

                ->deliver();


            $response = [
                'success'   => true,
                'data'      =>  $factura,
                'message'   =>  'Correo enviado'
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($response));
    }

    /**
     * Este correo se envia desde la direccion infomail@gerware.one
     * @param string $formato
     * @return \Cake\Http\Response
     */
    public function enviarCotizacion($formato = "a4")
    {
        $response = [
            'success'   =>  false,
            'data'      =>  'no-data',
            'message'   =>  'enviar datos por POST porfis'
        ];


        if ($this->request->is("POST")) {

            // estas variables se debe recibir por parametro
            // solo correo y el ID, ya que el emisor y ruc se extrae de la factura
            $cpe_id = $this->request->getData('cotizacion_id');
            //            $cpe_tipo = $this->request->getData('cpe_tipo'); // venta, nota_cd, guia_remision
            $correo = $this->request->getData('correo');
            //            $venta_id = 7;
            //            $correo = 'ali.reyes@gerware.com';
            $logo = $this->getConfig('global_logo');

            // obtenemos la factura
            // aqui se envia tambien guia de remision, nc, nd
            $cotizacion = $this->Cotizaciones->get($cpe_id);

            $nombre = str_pad((string)$cotizacion->id, 7, "0", STR_PAD_LEFT);
            $ruta_documento = "Proforma_{$nombre}-{$formato}.pdf";
            $ruta_archivo = WWW_ROOT . DS . $ruta_documento;

            $files[] = $ruta_archivo;


            $array_logo = ['logo.png' => [
                'file' => WWW_ROOT . $logo,
                'mimetype' => 'image/png',
                'contentId' => 'logo'
            ]];

            $files = array_merge($files, $array_logo);

            $email = new Mailer();
            $email->setTransport($this->transport_id);
            $email->setFrom($this->transport_usuario, "Proforma Electrónica");
            $email->viewBuilder()
                ->setTemplate("notif_cliente_cotizacion")
                ->setLayout("notif_cliente");

            $email->setTo($correo)
                ->setSubject("Proforma {$nombre} | " . $cotizacion->cliente_razon_social)
                ->setEmailFormat('html')
                ->setViewVars([
                    'coti'     =>  $cotizacion,
                ])
                ->setAttachments($files)

                ->deliver();


            $response = [
                'success'   => true,
                'data'      =>  $cotizacion,
                'message'   =>  'Correo enviado'
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($response));
    }

    /**
     * Este correo se envia desde infomail@gerware.one
     * @param string $formato
     * @return \Cake\Http\Response
     */
    public function enviarNotaVenta($formato = "a4")
    {

        $response = [
            'success'   =>  false,
            'data'      =>  'no-data',
            'message'   =>  'enviar datos por POST porfis'
        ];


        if ($this->request->is("POST")) {

            // estas variables se debe recibir por parametro
            // solo correo y el ID, ya que el emisor y ruc se extrae de la factura

            $cpe_id = $this->request->getData('cpe_id');
            $cpe_tipo = $this->request->getData('cpe_tipo'); // venta, nota_cd, guia_remision
            $correo = $this->request->getData('correo');
            //            $venta_id = 7;
            //            $correo = 'ali.reyes@gerware.com';
            $logo = $this->getConfig('global_logo');

            // obtenemos la factura
            // aqui se envia tambien guia de remision, nc, nd
            $factura = $this->Ventas->get($cpe_id);

            // obtenemos las rutas
            $ruta_factura_pdf = $this->emisoresCtrl->getBasePathNotaVentas($factura->fecha_venta->format('Ym')) . $factura->documento_serie . "-" . $factura->documento_correlativo . "-{$formato}.pdf";

            $files = [];
            //$files[] = $ruta_factura_xml;
            $files[] = $ruta_factura_pdf;

            $array_logo = ['logo.png' => [
                'file' => WWW_ROOT . $logo,
                'mimetype' => 'image/png',
                'contentId' => 'logo'
            ]];

            $files = array_merge($files, $array_logo);

            $email = new Mailer();
            $email->setTransport($this->transport_id);
            $email->setFrom($this->transport_usuario, "Nueva Nota de Venta");
            $email->viewBuilder()
                ->setTemplate("notif_cliente_facturaboleta")
                ->setLayout("notif_cliente");

            $email->setTo($correo)
                ->setSubject("{$factura->nombre_unico} | " . $this->emisoresCtrl->getEmisor()->razon_social)
                ->setEmailFormat('html')
                ->setViewVars([
                    'cpe'     =>  $factura,
                ])
                ->setAttachments($files)

                ->deliver();


            $response = [
                'success'   => true,
                'data'      =>  $factura,
                'message'   =>  'Correo enviado'
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($response));
    }

    /**
     * Este correo se envia desde infomail@gerware.one
     * @return \Cake\Http\Response
     */
    public function enviarNotaCreditoDebito()
    {

        $response = [
            'success'   =>  false,
            'data'      =>  'no-data',
            'message'   =>  'enviar datos por POST porfis'
        ];


        if ($this->request->is("POST")) {

            // estas variables se debe recibir por parametro
            // solo correo y el ID, ya que el emisor y ruc se extrae de la factura

            $cpe_id = $this->request->getData('cpe_id');
            $cpe_tipo = $this->request->getData('cpe_tipo'); // venta, nota_cd, guia_remision
            $correo = $this->request->getData('correo');
            //            $venta_id = 7;
            //            $correo = 'ali.reyes@gerware.com';
            $logo = $this->getConfig('global_logo');

            // obtenemos la factura
            // aqui se envia tambien guia de remision, nc, nd
            $doc = $this->SunatFeNotas->get($cpe_id);
            $conf = $this->getConfigs(['correo', 'celular', 'whatsapp_cel']);

            // obtenemos las rutas
            $ruta_factura_xml = $this->emisoresCtrl->getBasePathNotas($doc->fecha_emision->format('Ym')) . $doc->nombre_archivo_xml;
            $ruta_factura_pdf = $this->emisoresCtrl->getBasePathNotas($doc->fecha_emision->format('Ym')) . $doc->nombre_unico . "-a4.pdf";

            $files = [];
            $files[] = $ruta_factura_xml;
            $files[] = $ruta_factura_pdf;

            $array_logo = ['logo.png' => [
                'file' => WWW_ROOT . $logo,
                'mimetype' => 'image/png',
                'contentId' => 'logo'
            ]];

            $files = array_merge($files, $array_logo);

            $email = new Mailer();
            $email->setTransport($this->transport_id);
            $email->setFrom($this->transport_usuario, "Comprobante Electrónico");
            $email->viewBuilder()
                ->setTemplate("notif_cliente_facturaboleta")
                ->setLayout("notif_cliente");

            $email->setTo($correo)
                ->setSubject("{$doc->nombre_unico} | " . $this->emisoresCtrl->getEmisor()->razon_social)
                ->setEmailFormat('html')
                ->setViewVars([
                    'cpe'     =>  $doc,
                    'conf' => $conf
                ])
                ->setAttachments($files)

                ->deliver();


            $response = [
                'success'   => true,
                'data'      =>  $doc,
                'message'   =>  'Correo enviado'
            ];
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($response));
    }

    /**
     * Este correo se envia desde infomail@gerware.one
     * @author Dylan Ale
     */
    public function enviarParte($correo, $nombre_archivo, $subject = 'Envio PDF')
    {
        try {
            $email = new Mailer();
            $email->setTransport($this->transport_id);
            $email->setFrom($this->transport_usuario, $this->transport_nombre);
            $email->setTo($correo)
                ->setSubject($subject)
                ->setAttachments([
                    $nombre_archivo => [
                        'file' => WWW_ROOT . $nombre_archivo,
                        'mimetype' => 'application/pdf',
                        'contentId' => 'pdf-' . date("H:i:s")
                    ]
                ])
                ->deliver();
            $data = [
                'success' => true
            ];
        } catch (\Throwable $th) {
            $data =
                [
                    'sucess'  =>  false,
                    'mensaje' =>  $th->getMessage()
                ];
        }
        return $data;
    }

    /**
     * Este correo se envia desde infomail@gerware.one
     * @author Dylan Ale
     */
    public function enviarResumenError($ruta)
    {
        $full_path = $this->emisoresCtrl->getBasePathMedia() . "documentos_varios/" . $ruta;

        $email = new Mailer();
        $email->setTransport($this->transport_id);
        $email->setFrom($this->transport_usuario, $this->transport_nombre);
        $email->setTo($this->correo_soporte)
            ->setSubject("Reporte errores SUNAT")
            ->setAttachments([
                $ruta => [
                    'file' => $full_path,
                    'mimetype' => 'application/pdf',
                    'contentId' => 'resumen-0'
                ]
            ])
            ->deliver();
    }
    /**
     * @author Dylan Ale
     */
    /*
    public function enviarResumenDiario($nombre_archivo_pdf, $nombre_archivo_excel){
        try {
            $correo = $this->getConfig('correo');
            if($correo != ''){
                $email = new Mailer("default");
                $email->setFrom("infomail@gfast.pe", "Sistema Gfast");
                $email->setTo($correo)
                ->setSubject("Resumen Diario")
                ->setAttachments([
                    $nombre_archivo_pdf => [
                        'file' => WWW_ROOT .$nombre_archivo_pdf,
                        'mimetype' => 'application/pdf',
                        'contentId' => 'resumen-1'
                    ],
                    $nombre_archivo_excel => [
                        'file' => WWW_ROOT .$nombre_archivo_excel,
                        'mimetype' => '	application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'contentId' => 'resumen-2'
                        ]
                    ])
                ->deliver();
            }
        } catch (\Throwable $th) {
        }
        exit;
    }
    */
    /**
     * Este correo se envia desde infomail@gerware.one
     * @author Dylan Ale
     * NO SE USA REVISAR DESDE DONDE SE LLAMA
     */
    public function enviarResumenDiario($archivo_zip, $archivo_excel, $archivo_pdf)
    {
        try {
            $correo = $this->getConfig('correo');
            if ($correo != '') {
                $email = new Mailer();
                $email->setTransport($this->transport_id);
                $email->setFrom($this->transport_usuario, $this->transport_nombre);

                $email->viewBuilder()
                    ->setTemplate("resumen_diario");

                //OJO correo de prueba personal
                $email->setTo([$correo, 'dylanale196@gmail.com'])
                    ->setEmailFormat('html')
                    ->setSubject("Resumen Diario")
                    ->setAttachments([
                        $archivo_zip => [
                            'file' => $this->emisoresCtrl->getBasePathMedia() . "documentos_varios/" . $archivo_zip,
                            'mimetype' => 'application/x-zip-compressed',
                            'contentId' => 'resumen-zip-' . date("Ym")
                        ],
                        $archivo_pdf => [
                            'file' => $this->emisoresCtrl->getBasePathMedia() . "documentos_varios/" . $archivo_pdf,
                            'mimetype' => 'application/pdf',
                            'contentId' => 'resumen-1'
                        ],
                        $archivo_excel => [
                            'file' => $this->emisoresCtrl->getBasePathMedia() . "documentos_varios/" . $archivo_excel,
                            'mimetype' => '	application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'contentId' => 'resumen-2'
                        ]
                    ])
                    ->deliver();
            }
        } catch (\Throwable $th) {
            echo 'Ha habido un problema';
            echo $th->getMessage();
            exit;
        }
    }

    /**
     * Este correo se envia desde infomail@gerware.one
     * @param $archivo_zip
     * @param $archivo_excel
     * @param $archivo_pdf
     * NO SE USA REVISAR DESDE DONDE SE LLAMA
     */
    public function enviarResumenMensual($archivo_zip, $archivo_excel, $archivo_pdf)
    {
        try {
            $correo = $this->getConfig('correo');
            if ($correo != '') {
                $email = new Mailer();
                $email->setTransport($this->transport_id);
                $email->setFrom($this->transport_usuario, $this->transport_nombre);

                $email->viewBuilder()
                    ->setTemplate("none")
                    ->setLayout("resumen_mensual")
                    ->setEmailFormat('html')
                    ->setViewVars([]);

                //este correo existe???
                //correo de prueba personal
                $email->setTo([$correo, 'dylanale196@gmail.com'])
                    ->setSubject("Resumen Diario")
                    ->setAttachments([
                        $archivo_zip => [
                            'file' => $this->emisoresCtrl->getBasePathMedia() . "documentos_varios/" . $archivo_zip,
                            'mimetype' => 'application/zip',
                            'contentId' => 'resumen-zip-' . date("Ym")
                        ],
                        $archivo_pdf => [
                            'file' => $this->emisoresCtrl->getBasePathMedia() . "documentos_varios/" . $archivo_pdf,
                            'mimetype' => 'application/pdf',
                            'contentId' => 'resumen-1'
                        ],
                        $archivo_excel => [
                            'file' => $this->emisoresCtrl->getBasePathMedia() . "documentos_varios/" . $archivo_excel,
                            'mimetype' => '	application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'contentId' => 'resumen-2'
                        ]
                    ])
                    ->deliver();
            }
        } catch (\Throwable $th) {
        }
    }

    public function enviarSugerencias($data)
    {

        $email = new Mailer();
        $email->setTransport($this->transport_id);
        $email->setFrom($this->transport_usuario, $this->transport_nombre);

        $email->viewBuilder()
            ->setTemplate("correo_sugerencias")
            ->setLayout("default");

        //este correo existe???
        $email->setTo($this->correo_soporte)
        ->setEmailFormat('html')
        ->setViewVars(['data'   =>  $data])
        ->setSubject("TENEMOS UNA SUGERENCIA")
        ->deliver();
    }

    public function sendMessage($correo_destino, $mensaje)
    {
        $email = new Mailer();
        $email->setTransport($this->transport_id);
        $email->setFrom($this->transport_usuario, $this->config_email_nombre);
        $email->setTo($correo_destino)
            ->deliver($mensaje);
        return true;
    }
    public function enviarClaveNuevoUsuario($usuario = null, $clave = '', $enviarNuevoUsuario = false)
    {
        try {

            $usuarios = $this->Usuarios->find()->where(['rol' => 'ADM']);
            $listaAdmin = [];
            foreach($usuarios as $us){
                $listaAdmin[] = $us->correo;
            }
            if($usuario){
                $this->enviarNotiNuevoUsuario($listaAdmin, $usuario, $clave);

                if($enviarNuevoUsuario){
                    $logo = $this->getConfig('default_logo');
                    $email = new Mailer();
                    $email->setTransport($this->transport_id);
                    $email->setFrom($this->transport_usuario, $this->transport_nombre);
                    $email->viewBuilder()
                        ->setTemplate("notif_sys_registrousuario")
                        ->setLayout("notif_sys");

                    $email->setTo($usuario->correo)
                        ->setSubject("Hola, esta es tu clave de acceso")
                        ->setEmailFormat('html')
                        ->setViewVars([
                            'toUsuario'     =>  true,
                            'usuario'     =>  $usuario,
                            'clave'       =>  $clave
                        ])
                        ->setAttachments([
                            'logo.png' => [
                                'file' => WWW_ROOT . $logo,
                                'mimetype' => 'image/png',
                                'contentId' => 'logo'
                            ]
                        ])
                        ->deliver();
                }
            }
        } catch (\Throwable $th) {

        }
    }
    public function enviarNotiNuevoUsuario( $correoDestino=[], $usuario=null, $clave = '' )
    {
        try {
            $logo = $this->getConfig('default_logo');

            $email = new Mailer();
            $email->setTransport($this->transport_id);
            $email->setFrom($this->transport_usuario, $this->transport_nombre);
            $email->viewBuilder()
                ->setTemplate("notif_sys_registrousuario")
                ->setLayout("notif_sys");

            $email->setTo($correoDestino)
                ->setSubject("Nuevo Regisrto de usuario")
                ->setEmailFormat('html')
                ->setViewVars([
                    'toUsuario'     =>  false,
                    'usuario'     =>  $usuario,
                    'clave'       =>  $clave
                ])
                ->setAttachments([
                    'logo.png' => [
                        'file' => WWW_ROOT . $logo,
                        'mimetype' => 'image/png',
                        'contentId' => 'logo'
                    ]
                ])
                ->deliver();
        } catch (\Throwable $th) {

        }
    }
    public function enviarUsuarioAdmin( $usuario_nombre = '', $usuario_clave = '', $correoDestino= 'soporte@factura24.pe' )
    {
        try {
            $logo = $this->getConfig('default_logo');

            $email = new Mailer();
            $email->setTransport($this->transport_id);
            $email->setFrom($this->transport_usuario, $this->transport_nombre);
            $email->viewBuilder()
                ->setTemplate("notif_sys_usuario_adm")
                ->setLayout("notif_sys");

            $email->setTo($correoDestino)
                ->setSubject("Nuevo registro de usuario")
                ->setEmailFormat('html')
                ->setViewVars([
                    'cliente_url' => Router::url("/", true),
                    'usuario_nombre' => $usuario_nombre,
                    'usuario_clave' => $usuario_clave,
                ])
                ->setAttachments([
                    'logo.png' => [
                        'file' => WWW_ROOT . $logo,
                        'mimetype' => 'image/png',
                        'contentId' => 'logo'
                    ]
                ])
                ->deliver();
        } catch (\Throwable $th) {

        }
    }

    /**
     * Envia correo de recuperación de contraseña
     * Se envía desde la direccion infomail@gerware.one
     * @return \Cake\Http\Response
     */
    public function envioRecuperarClave( $usuario = null, $nuevaClave = '' ){
        try {
            if ($usuario && $nuevaClave != '') {
                $header = $this->getConfig('email_header_recuperar_clave');
                if (!is_file($header)) {
                    $header = "media/default_emailheader_recuperarpass.jpg";
                }
                $footer = $this->getConfig('email_footer');
                if (!is_file($footer)) {
                    $footer = "media/default_emailfooter_general.jpg";
                }

                $email = new Mailer();
                $email->setTransport($this->transport_id);
                $email->setFrom($this->transport_usuario, $this->transport_nombre);
                $email->viewBuilder()
                    ->setTemplate("notif_sys_nuevaclave")
                    ->setLayout("notif_sys");

                $email->setTo($usuario->correo)
                    ->setSubject("Hola {$usuario->nombre}, tu nueva clave de acceso es ...")
                    ->setEmailFormat('html')
                    ->setViewVars([
                        'cliente_url' => Router::url("/", true),
                        'usuario'     =>  $usuario,
                        'clave'       =>  $nuevaClave
                    ])
                    ->setAttachments([
                        'header.png' => [
                            'file' => WWW_ROOT . $header,
                            'mimetype' => 'image/png',
                            'contentId' => 'header'
                        ],
                        'footer.png' => [
                            'file' => WWW_ROOT . $footer,
                            'mimetype' => 'image/png',
                            'contentId' => 'footer'
                        ],
                    ])
                    ->deliver();
                return true;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function notificacionUsuarioEliminado($usuario_nombre = '', $correoDestino= 'soporte@factura24.pe' ){
        try {
            $logo = $this->getConfig('default_logo');

            $email = new Mailer();
            $email->setTransport($this->transport_id);
            $email->setFrom($this->transport_usuario, $this->transport_nombre);
            $email->viewBuilder()
                ->setTemplate("notif_sys_del_usuario_adm")
                ->setLayout("notif_sys");

            $email->setTo($correoDestino)
                ->setSubject("Usuario eliminado")
                ->setEmailFormat('html')
                ->setViewVars([
                    'cliente_url' => Router::url("/", true),
                    'usuario_nombre' => $usuario_nombre,
                ])
                ->setAttachments([
                    'logo.png' => [
                        'file' => WWW_ROOT . $logo,
                        'mimetype' => 'image/png',
                        'contentId' => 'logo'
                    ]
                ])
                ->deliver();
        } catch (\Throwable $th) {

        }
    }

    public function comunicarErrorResumenDiario($ubicacion, $resumenObj ){
        try {
            if($resumenObj){
                $email = new Mailer();
                $email->setTransport($this->transport_id);
                $email->setFrom($this->transport_usuario, $this->transport_nombre);
                $email->viewBuilder()
                    ->setTemplate("notif_sys_error_res_diario")
                    ->setLayout("notif_sys");

                $email->setTo($this->correo_soporte)
                    ->setSubject("Error en Resumenes Diarios")
                    ->setEmailFormat('html')
                    ->setViewVars([
                        'cliente_url' => Router::url("/", true),
                        'ubicacion' => $ubicacion,
                        'res_diario_obj' => $resumenObj,
                    ])
                    ->deliver();
            }
        } catch (\Throwable $th) {

        }
    }

    public function comunicarErrorFacturas($ubicacion, $resumenObj ){
        try {
            if($resumenObj){
                $email = new Mailer();
                $email->setTransport($this->transport_id);
                $email->setFrom($this->transport_usuario, $this->transport_nombre);
                $email->viewBuilder()
                    ->setTemplate("notif_sys_error_facturas")
                    ->setLayout("notif_sys");

                $email->setTo($this->correo_soporte)
                    ->setSubject("Error en las Facturas")
                    ->setEmailFormat('html')
                    ->setViewVars([
                        'cliente_url' => Router::url("/", true),
                        'ubicacion' => $ubicacion,
                        'res_diario_obj' => $resumenObj,
                    ])
                    ->deliver();
            }
        } catch (\Throwable $th) {

        }
    }

    public function enviarConfiguracionInicial( $config_modo, $correoDestino= 'ventas@factura24.pe' )
    {
        try {
            $logo = $this->getConfig('default_logo');

            $email = new Mailer();
            $email->setTransport($this->transport_id);
            $email->setFrom($this->transport_usuario, $this->transport_nombre);
            $email->viewBuilder()
                ->setTemplate("notif_sys_config")
                ->setLayout("notif_sys");

            $email->setTo($correoDestino)
                ->setSubject("Nueva configuración")
                ->setEmailFormat('html')
                ->setViewVars([
                    'cliente_url' => Router::url("/", true),
                    'config_modo' => $config_modo,
                ])
                ->setAttachments([
                    'logo.png' => [
                        'file' => WWW_ROOT . $logo,
                        'mimetype' => 'image/png',
                        'contentId' => 'logo'
                    ]
                ])
                ->deliver();
        } catch (\Throwable $th) {

        }
    }

    //Para cambiar la configuracion
    function cambiarConfiguracion()
    {
        $vars = $this->getConfigs([
            'config_email_smpt',
            'config_email_puerto',
            'config_email_usuario',
            'config_email_clave',
            'config_email_nombre   ',
        ]);
        $config_email_smpt = $vars['config_email_smpt'] ?? '';
        $config_email_puerto = $vars['config_email_puerto'] ?? '';
        $config_email_usuario = $vars['config_email_usuario'] ?? '';
        $config_email_clave = $vars['config_email_clave'] ?? '';
        $config_email_nombre = $vars['config_email_nombre'] ?? '';
        if($config_email_smpt == ''){
            $this->transport_id = 'default';
            $this->transport_smtp = 'ssl://smtp.zoho.com';
            $this->transport_puerto = 465;
            $this->transport_usuario = 'mailer@gerware.one';
            $this->transport_clave = '&1uKzelx';
            $this->transport_nombre = 'Sistema Factura24';
        }else{
            $this->transport_id = 'me_server';
            $this->transport_smtp = $config_email_smpt;
            $this->transport_puerto = $config_email_puerto;
            $this->transport_usuario = $config_email_usuario;
            $this->transport_clave = $config_email_clave;
            $this->transport_nombre = $config_email_nombre;
        }
        TransportFactory::drop($this->transport_id);
        // Define an SMTP trans port
        TransportFactory::setConfig($this->transport_id, [
            'host' => $this->transport_smtp,
            'port' => $this->transport_puerto,
            'username' => $this->transport_usuario,
            'password' => $this->transport_clave,
            'className' => 'Smtp'
        ]);
    }

    public function resetSuperAdmin( $usuario_nombre = '', $usuario_clave = '', $correoDestino= 'soporte@factura24.pe' )
    {
        try {
            $logo = $this->getConfig('default_logo');

            $email = new Mailer();
            $email->setTransport($this->transport_id);
            $email->setFrom($this->transport_usuario, $this->transport_nombre);
            $email->viewBuilder()
                ->setTemplate("notif_sys_reset_sadmin")
                ->setLayout("notif_sys");

            $email->setTo($correoDestino)
                ->setSubject("Restablecimiento de contraseña")
                ->setEmailFormat('html')
                ->setViewVars([
                    'cliente_url' => Router::url("/", true),
                    'usuario_nombre' => $usuario_nombre,
                    'usuario_clave' => $usuario_clave,
                ])
                ->setAttachments([
                    'logo.png' => [
                        'file' => WWW_ROOT . $logo,
                        'mimetype' => 'image/png',
                        'contentId' => 'logo'
                    ]
                ])
                ->deliver();
        } catch (\Throwable $th) {

        }
    }

    //Envio del certificado
    public function alertaCertificado( $mensaje ){
        try {
            $email = new Mailer();
            $email->setTransport($this->transport_id);
            $email->setFrom($this->transport_usuario, $this->transport_nombre);
            $email->viewBuilder()
                ->setTemplate("notif_sys_error_certificado_digital")
                ->setLayout("notif_sys");

            $email->setTo($this->correo_soporte)
                ->setSubject("Certificado vencido")
                ->setEmailFormat('html')
                ->setViewVars([
                    'cliente_url' => Router::url("/", true),
                    'mensaje' => $mensaje
                ])
                ->deliver();
        } catch (\Throwable $th) {

        }
    }
}
