<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Http\ServerRequest;

/**
 * Usuarios Controller
 *
 * @property \App\Model\Table\UsuariosTable $Usuarios
 */
class UsuariosController extends AppController
{

    protected $Configuraciones = null;

    public function initialize(): void
    {
        parent::initialize();
        $this->Configuraciones = $this->fetchTable('Configuraciones');

    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['iniciarSesion' , 'recuperarClave']);
    }

    public function index()
    {
        $nombre = $this->request->getQuery('q_nombre');
        $items = $this->Usuarios->find()->where(['nombre LIKE ' => "%{$nombre}%"]);
        $usuarios = $this->paginate( $items, ['maxLimit' => 10]);

        $top_links = [];
        $top_links['title'] =   "Lista de Usuarios";
        $top_links['links'] = [];
        $top_links['links']['btnNuevo'] =
            [
                'name'    =>     "<i class='fas fa-plus fa-fw'></i> Nuevo",
                'params'    =>     []
            ];

        $this->set("view_title", "Usuarios");
        $this->set("top_links", $top_links);

        $this->set(compact('usuarios'));
        //        $this->set("roles", $this->roles);
        $this->set("nombre", $nombre);
        $this->set('_serialize', ['usuarios']);

        $this->set("act_titulo", "Gestión de Usuarios");
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usuario = $this->Usuarios->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $usuario = $this->Usuarios->patchEntity($usuario, $data);
            if ($this->Usuarios->save($usuario)) {
                $this->Flash->success("El usuario se ha registrado con éxito");

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error("Ha ocurrido un error, por favor intente nuevamente");
            }
        }
        $top_links = [
            'title'  =>  "Nuevo Usuario",
            'links'     =>  [
                'btnBack'  =>  [
                    'name'    =>     "<i class='fas fa-chevron-left fa-fw'></i> Regresar",
                    'params'    =>     [
                        'controller' =>  'Usuarios',
                        'action'     =>  'index',
                    ]

                ],
            ]
        ];


        $this->set("top_links", $top_links);

        $this->set("view_title", "Nuevo Usuario");

        $this->set(compact('usuario'));
        $this->set('_serialize', ['usuario']);
        //        $this->set("roles", $this->roles);
    }

    /**
     * Edit method
     *
     * @param string|null $id Usuario id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usuario = $this->Usuarios->get($id, ['contain' => []]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if ($data['pwd'] == '') {
                unset($data['pwd']);
            }
            $usuario = $this->Usuarios->patchEntity($usuario, $data);
            if ($this->Usuarios->save($usuario)) {
                $this->Flash->success("El usuario se ha actualizado con éxito");

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error("Ha ocurrido un error, por favor intente nuevamente");
            }
        }
        $this->set(compact('usuario'));
        $this->set('_serialize', ['usuario']);

        $top_links = [
            'title'  =>  "Editar Usuario",
            'links'     =>  [
                'btnBack'  =>  [
                    'name'    =>     "<i class='fas fa-chevron-left fa-fw'></i> Regresar",
                    'params'    =>     [
                        'controller' =>  'Usuarios',
                        'action'     =>  'index',
                    ]

                ],
            ]
        ];


        $this->set("top_links", $top_links);

        //        $this->set("roles", $this->roles);
        $this->set("view_title", "Modificar Usuario");
    }

    /**
     * Delete method
     *
     * @param string|null $id Usuario id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usuario = $this->Usuarios->get($id);
        if ($this->Usuarios->delete($usuario)) {
            $emailCtrl = new EmailController();
            $emailCtrl->notificacionUsuarioEliminado( $usuario->usuario );
            $this->Flash->success("El usuario se ha eliminado con éxito");
        } else {
            $this->Flash->error("Ha ocurrido un error, por favor intente nuevamente");
        }

        return $this->redirect(['action' => 'index']);
    }

    public function cambiarClave()
    {
        $uid =  $this->Auth->User('id');
        $user = $this->Usuarios->get($uid);


        if ($this->request->is(['post', 'put', 'patch'])) {

            $data = $this->request->getData();
            if (DefaultPasswordHasher::check($data['pswd_old'], $user->pwd)) {
                $user->pwd = $data['pswd_new'];
                $this->Usuarios->save($user);
                $this->Flash->success("La contraseña se ha actualizado con éxito");
            } else {
                $this->Flash->error("La contraseña anterior no es correcta");
            }
        }
        $this->set("titulo", "Cambiar Contraseña");
    }

    public function iniciarSesion()
    {

        // establecemos como layout "none" ya que todo el codigo estará
        // dentro de la misma vista incluyendo el layout
        $this->viewBuilder()->setLayout("none");
        $usuario = "";

        $result = $this->Authentication->getResult();

        // If the user is logged in send them away.
        if ($result->isValid()) {

            return $this->redirect(['controller' => 'Ventas', 'action' => 'index']);
        }
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error('Usuario o clave incorrectos');
        }
        /**
         * 1 es activa
         * 0 es suspendida
         */
        $this->set("estado_cuenta", $this->getConfig('estado_cuenta', '1'));
        $this->set("usuario", $usuario);
    }

    public function cerrarSesion()
    {
        $this->Authentication->logout();
        return $this->redirect(['controller' => 'Usuarios', 'action' => 'iniciarSesion']);
    }

    public function redireccion()
    {
        switch ($this->usuario_sesion['rol']) {
            case 'ADM':
                return $this->redirect(['controller' => 'Productos', 'action' => 'index']);
            case 'USR':
                return $this->redirect(['controller' => 'Web', 'action' => 'miCuenta']);
        }
    }


    /**
     * esta funcion se invoca desde ajax, actualiza o crea un nuevo usuario
     * @return \Cake\Http\Response
     */
    public function ajaxSave()
    {
        $respuesta = [
            'success' => false,
            'data' => '',
            'message' => 'No se recibieron datos'
        ];

        if ($this->request->is('POST')) {
            $data = $this->request->getData();
            $claveAux = $data['clave'];
            if (isset($data['id']) && $data['id'] != "") {
                $usuario = $this->Usuarios->find()->where(['id' => $data['id']])->first();
            } else {
                $usuario = $this->Usuarios->newEmptyEntity();
            }
            if (isset($data['usuario']) && $usuario->usuario != $data['usuario']) {
                if (!$this->usernameDisponible($data['usuario'])) {
                    $respuesta = [
                        'success' => false,
                        'data' => 'check-message',
                        'message' => 'El nombre de usuario ya está siendo utilizado'
                    ];
                    return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
                }
            }

            if (!$usuario) {
                $usuario = $this->Usuarios->newEmptyEntity();
            } else {
                if ($data['clave'] == "") {
                    unset($data['clave']);
                }
            }


            $usuario = $this->Usuarios->patchEntity($usuario, $data);

            if ($this->Usuarios->save($usuario)) {
                $emailCtrl = new EmailController( new ServerRequest() );
                if(isset($data['check_correo'])  && $data['check_correo'] == '1'){
                    $emailCtrl->enviarClaveNuevoUsuario($usuario, $claveAux, true);
                }
                $emailCtrl->enviarUsuarioAdmin( $usuario->usuario,  $claveAux );
                $respuesta['success'] = true;
                $respuesta['data'] = $usuario;
                $respuesta['message'] = 'Usuario creado o modificado exitosamente';
            } else {
                $respuesta['success'] = false;
                $respuesta['data'] = $usuario;
                $respuesta['message'] = 'Error al guardar/modificar el usuario';
            }
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    private function checkMaxUsersAmount()
    {
        $cant_max_users = intval($this->getConfig('cant_max_usuarios', '-1'));

        if ($cant_max_users == -1) {
            return true;
        }
        $cant_users = $this->Usuarios->find()->count();
        if ($cant_max_users > $cant_users) {
            return true;
        }

        return false;
    }


    /**
     * Verifica si el nombre de usuario está disponible
     * @param $username
     */
    private function usernameDisponible($username)
    {
        $uuu = $this->Usuarios->find()->where(['usuario' => $username])->first();
        return ($uuu) ? false : true;
    }


    public function getOne($id)
    {
        $item = $this->Usuarios->find()->where(['id' => $id])->first();

        if ($item) {
            $respuesta = [
                'success' => true,
                'data' => $item,
                'message' => 'Busqueda exitosa'
            ];
        } else {
            $respuesta = [
                'success' => false,
                'data' => '',
                'message' => 'Error en la busqueda'
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    public function recuperarClave()
    {
        $response = [
            'success'   =>  false,
            'data'      =>  '',
            'message'   =>  'No se enviaron los datos necesarios'
        ];

        if ($this->request->is("POST")) {
            $correo = $this->request->getData("usuario2");
            $usuario = $this->Usuarios->find()->where(['correo' => $correo])->first();
            if (!$usuario) {
                $usuario = $this->Usuarios->find()->where(['usuario' => $correo])->first();
            }

            if ($usuario) {
                // la siguiente linea descomentar para depuracion
                // return $this->response->withType('application/json')->withStringBody(json_encode($usuario));

                //reiniciamos la clave
                $clave = uniqid();
                $usuario->clave = $clave;
                $usuario = $this->Usuarios->save($usuario);
                $emailCtrl = new EmailController();
                $emailCtrl->envioRecuperarClave($usuario, $clave);

                $response = [
                    'success'   => true,
                    'data'      =>  "",
                    'message'   =>  'La nueva clave ha sido enviada al correo del usuario'
                ];
            } else {
                $response = [
                    'success'   =>  false,
                    'data'      =>  'no-user',
                    'message'   =>  'El usuario no existe o ha sido eliminado'
                ];
            }
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($response));
    }
    public function apiActualizarClave()
    {
        $response = [
            'success'   =>  false,
            'data'      =>  '',
            'message'   =>  'No se enviaron los datos necesarios'
        ];

        if ($this->request->is("POST")) {
            $usuario_id = $this->request->getData("id");
            $clave = $this->request->getData("clave");
            $usuario = $this->Usuarios->find()->where(['id' => $usuario_id])->first();
            if (!$usuario) {
                $response = [
                    'success'   =>  false,
                    'data'      =>  'no-user',
                    'message'   =>  'El usuario no existe.'
                ];
            }
            $usuario->clave = $clave;
            $usuario = $this->Usuarios->save($usuario);

            if ($usuario) {
                $emailCtrl = new EmailController();
                if( $this->request->getData("enviar_correo", "") == '1' ){
                    $emailCtrl->envioRecuperarClave($usuario, $clave);
                }
                $response = [
                    'success'   => true,
                    'data'      =>  null,
                    'message'   =>  'Clave actualizada.'
                ];
            } else {
                $response = [
                    'success'   =>  false,
                    'data'      =>  null,
                    'message'   =>  'Ocurrio un error al actualizar la clave.'
                ];
            }
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($response));
    }

    public function  generarTokenUsuario(){
        $usuario = $this->Usuarios->find()->where(['id' => $this->usuario_sesion['id']])->first();
        if($usuario){
            $usuario->token = md5(uniqid());
            // $this->Usuarios->save($usuario);
        }
        exit;
    }
}
