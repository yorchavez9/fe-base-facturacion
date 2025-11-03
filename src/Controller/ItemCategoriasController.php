<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\AppController;
use Cake\Event\EventInterface;
/**
 * ItemCategorias Controller
 *
 * @property \App\Model\Table\ItemCategoriasTable $ItemCategorias
 * @method \App\Model\Entity\ItemCategoria[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ItemCategoriasController extends AppController
{
    protected $Usuarios = null;
    protected $ItemCategorias = null;
    public function initialize():void {
        parent::initialize();
        $this->Usuarios = $this->fetchTable("Usuarios");
        $this->ItemCategorias = $this->fetchTable("ItemCategorias");
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
        $this->loadTemplateFiles();
    }

    public function getList(){
        $cats = $this->ItemCategorias->find()->order(['orden' => 'ASC']);
        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode($cats));
    }

    public function getJson($id){
        $cat = $this->ItemCategorias->find()->where([ 'id' => $id ])->first();
        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode($cat->toArray()));
    }

    public function guardarCategoriaAjax(){

        $data = $this->request->getData(); 
        $cat = $this->ItemCategorias->newEmptyEntity();
        if ($data['id'] != ""){
            $cat = $this->ItemCategorias->find()->where(['id' => $data['id']])->first();
        }
        $cat = $this->ItemCategorias->patchEntity($cat, $data);

        $response = [];
        if ($this->ItemCategorias->save($cat)){
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

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $opt_nombre = $this->request->getQuery("opt_nombre");
        $items = $this->ItemCategorias->find()->where(['nombre LIKE' => "%{$opt_nombre}%"])->orderBy(['orden' => 'ASC']);
        $categorias = $this->paginate($items , ['maxLimit' => 10]);
        $top_acciones = [
            'title'  =>  "Categorías",
            'links' =>  [
                'btnItems'  =>  [
                    'name'    =>     "<i class='fas fa-link fa-fw'></i> Servicios",
                    'params'    =>     [
                        'controller' =>  'Items',
                        'action'     =>  'servicios',
                    ]
                ],
                'btnNuevo'  =>  [
                    'name'    =>     "<i class='fas fa-plus fa-fw'></i> Nuevo",
                    'params'    =>     [
                        'controller' =>  'ItemCategorias',
                        'action'     =>  'add',
                    ]

                ]
            ]
        ];


        $this->set("opt_nombre", $opt_nombre);
        $this->set("view_title", "Listado de Categorías");
        $this->set("top_links", $top_acciones);
        $this->set("categorias", $categorias);
        $this->set('_serialize', ['grupos']);

    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $item = $this->ItemCategorias->get($id);
        if ($this->ItemCategorias->delete($item)) {
            $this->Flash->success("La categoría de Productos/Servicios se ha eliminado con éxito");
        } else {
            $this->Flash->error("Ha ocurrido un error, por favor, intente nuevamente");
        }

        return $this->redirect(['action' => 'index']);
    }

    public function ordenamientoMasivo(){
        $data = $this->request->getData('items');
        //print_r($data);    exit;
        foreach ($data as $obj){
            $cat = $this->ItemCategorias->get($obj['id']);
            $cat->orden = $obj['orden'];
            $cat = $this->ItemCategorias->save($cat);
        }
        //print_r($data);    exit;
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(true));
    }


    public function borradoMasivo(){
        $data = $this->request->getData();

        $this->ItemCategorias->deleteAll(['id IN '=> $data['checks']]);

        $result = [];
        $result['status'] = 'ok';


        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }

}
