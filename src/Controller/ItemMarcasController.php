<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\AppController;
use Cake\Event\EventInterface;
/**
 * ItemMarcas Controller
 *
 * @property \App\Model\Table\ItemMarcasTable $ItemMarcas
 * @method \App\Model\Entity\ItemMarca[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ItemMarcasController extends AppController
{

    protected $Usuarios = null;
    protected $ItemMarcas = null;
    public function initialize(): void {
        parent::initialize();
        $this->Usuarios = $this->fetchTable("Usuarios");
        $this->ItemMarcas = $this->fetchTable("ItemMarcas");
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
        $this->loadTemplateFiles();
    }

    public function getJson($id){
        $cat = $this->ItemMarcas->find()->where([ 'id' => $id ])->first();
        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode($cat->toArray()));
    }

    public function getList(){
        $cats = $this->ItemMarcas->find()->order(['orden' => 'ASC']);
        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode($cats));
    }


    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $opt_nombre = $this->request->getQuery("opt_nombre");

        $marcas = $this->ItemMarcas->find()->where(['nombre LIKE' => "%{$opt_nombre}%"])->orderBy(['orden' => 'ASC']);
        $marcas = $this->paginate( $marcas, ['maxLimit' => 10]);
        $top_acciones = [
            'title'  =>  "Marcas de Productos",
            'links' =>  [
                'btnItems'  =>  [
                    'name'    =>     "<i class='fas fa-link fa-fw'></i> Productos/Servicios",
                    'params'    =>     [
                        'controller' =>  'Items',
                        'action'     =>  'index',
                    ]
                ],
                'btnNuevo'  =>  [
                    'name'    =>     "<i class='fas fa-plus fa-fw'></i> Nueva Marca",
                    'params'    =>     [
                        'controller' =>  'Mercaderia',
                        'action'     =>  'MarcaAdd',
                    ]

                ],
            ]
        ];

        $this->set("opt_nombre", $opt_nombre);

        $this->set("web_titulo", "Marcas");
        $this->set("top_links", $top_acciones);

        $this->set("items", $marcas);
        $this->set('_serialize', ['grupos']);

        $this->set("view_title", "Gestión de Marcas");
        $this->set("act_titulo", "Marcas");
    }

    public function guardarJsonAjax(){

        $data = $this->request->getData();

        $cat = $this->ItemMarcas->newEmptyEntity();
        if ($data['id'] != ""){
            $cat = $this->ItemMarcas->find()->where(['id' => $data['id']])->first();
        }
        $cat = $this->ItemMarcas->patchEntity($cat, $data);
        $cat->orden = 0;

        $response = [];
        if ($this->ItemMarcas->save($cat)){
            $response['success'] = true;
            $response['data'] = $cat->toArray();
        }else {
            $response['success'] = false;
            $response['data'] = 'Ha ocurrido un error';
        }

        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode($response));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $item = $this->ItemMarcas->get($id);
        if ($this->ItemMarcas->delete($item)) {
            $this->Flash->success("El Grupo se ha eliminado con éxito");
        } else {
            $this->Flash->error("Ha ocurrido un error, por favor, intente nuevamente");
        }

        return $this->redirect(['action' => 'index']);
    }


    public function borradoMasivo(){
        $data = $this->request->getData();

        $this->ItemMarcas->deleteAll(['id IN '=> $data['checks']]);

        $result = [];
        $result['status'] = 'ok';


        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }

    public function ordenamientoMasivo(){
        $data = $this->request->getData('items');
        //print_r($data);    exit;
        foreach ($data as $obj){
            $cat = $this->ItemMarcas->get($obj['id']);
            $cat->orden = $obj['orden'];
            $cat = $this->ItemMarcas->save($cat);
        }
        //print_r($data);    exit;
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(true));
    }
}
