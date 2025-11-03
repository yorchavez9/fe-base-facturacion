<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * DirectorioEmpresas Controller
 *
 * @property \App\Model\Table\DirectorioEmpresasTable $DirectorioEmpresas
 * @method \App\Model\Entity\DirectorioEmpresa[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DirectorioEmpresasController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Categorias', 'Empresas'],
        ];
        $directorioEmpresas = $this->paginate($this->DirectorioEmpresas);

        $this->set(compact('directorioEmpresas'));
    }

    /**
     * View method
     *
     * @param string|null $id Directorio Empresa id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $directorioEmpresa = $this->DirectorioEmpresas->get($id, [
            'contain' => ['Categorias', 'Empresas'],
        ]);

        $this->set(compact('directorioEmpresa'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $directorioEmpresa = $this->DirectorioEmpresas->newEmptyEntity();
        if ($this->request->is('post')) {
            $directorioEmpresa = $this->DirectorioEmpresas->patchEntity($directorioEmpresa, $this->request->getData());
            if ($this->DirectorioEmpresas->save($directorioEmpresa)) {
                $this->Flash->success(__('The directorio empresa has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The directorio empresa could not be saved. Please, try again.'));
        }
        $categorias = $this->DirectorioEmpresas->Categorias->find('list', ['limit' => 200]);
        $empresas = $this->DirectorioEmpresas->Empresas->find('list', ['limit' => 200]);
        $this->set(compact('directorioEmpresa', 'categorias', 'empresas'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Directorio Empresa id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $directorioEmpresa = $this->DirectorioEmpresas->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $directorioEmpresa = $this->DirectorioEmpresas->patchEntity($directorioEmpresa, $this->request->getData());
            if ($this->DirectorioEmpresas->save($directorioEmpresa)) {
                $this->Flash->success(__('The directorio empresa has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The directorio empresa could not be saved. Please, try again.'));
        }
        $categorias = $this->DirectorioEmpresas->Categorias->find('list', ['limit' => 200]);
        $empresas = $this->DirectorioEmpresas->Empresas->find('list', ['limit' => 200]);
        $this->set(compact('directorioEmpresa', 'categorias', 'empresas'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Directorio Empresa id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $directorioEmpresa = $this->DirectorioEmpresas->get($id);
        if ($this->DirectorioEmpresas->delete($directorioEmpresa)) {
            $this->Flash->success(__('The directorio empresa has been deleted.'));
        } else {
            $this->Flash->error(__('The directorio empresa could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function apiConsultaPorRuc(){
        $q = $this->request->getQuery("query");

        $listado = [];

        $empresas = $this->DirectorioEmpresas->find()->where([
            'ruc LIKE' => "%{$q}%"
        ]);
        foreach ($empresas as $c) {
            $listado[] = [
                'id' => $c['id'],
                'value' => $c['ruc'] . " " . $c['razon_social'],
                'proveedor_ruc' => $c["ruc"],
                'proveedor_razon_social' => $c['razon_social'],
                'proveedor_domicilio_fiscal' => $c['domicilio_fiscal'],
            ];
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                "query" =>  $q,
                "suggestions"   =>  $listado
            ]));
    }

    public function save() {
        $respuesta = [
            'success' => false,
            'data' => '',
            'message' => "No se recibieron datos",
        ];

        if ($this->request->is('POST')) {
            $empresa = $this->DirectorioEmpresas->newEntity($this->request->getData());
            $empresa->info_busqueda = $empresa->ruc . " " . $empresa->razon_social;
            $empresa = $this->DirectorioEmpresas->save($empresa);

            if ($empresa) {
                $respuesta = [
                    'success' => true,
                    'data' => $empresa,
                    'message' => "Guardado exitoso",
                ];
            } else {
                $respuesta = [
                    'success' => false,
                    'data' => $empresa,
                    'message' => "Error al guardar",
                ];
            }


        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($respuesta));
    }

}
