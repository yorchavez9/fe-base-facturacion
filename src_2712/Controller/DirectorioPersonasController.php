<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * DirectorioPersonas Controller
 *
 * @property \App\Model\Table\DirectorioPersonasTable $DirectorioPersonas
 * @method \App\Model\Entity\DirectorioPersona[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DirectorioPersonasController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        $this->loadModel('Personas');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['DirectorioEmpresas', 'Personas'],
        ];
        $directorioPersonas = $this->paginate($this->DirectorioPersonas);

        $this->set(compact('directorioPersonas'));
    }

    public function conductores() {
        $this->paginate = [
            'contain' => ['DirectorioEmpresas', 'Personas'],
        ];

        $directorioPersonas = $this->DirectorioPersonas->find()->where([
            'rol' => 'USR_COND',
        ])->orderDesc('DirectorioPersonas.id');

        $directorioPersonas = $this->paginate($directorioPersonas);

        $this->set(compact('directorioPersonas'));

        $top_links = [
            'title' => 'Conductores',
            'links' => [
                'btnAdd' => [
                    'name' => '<i class="fa fa-fw fa-plus"></i> Nuevo Conductor',
                    'params' => 'javascript:FormPersona.Nuevo(1)',
                ],
            ],
        ];
        $this->set(compact('top_links'));
        $this->set('view_title', 'Conductores');
    }

    /**
     * View method
     *
     * @param string|null $id Directorio Persona id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $directorioPersona = $this->DirectorioPersonas->get($id, [
            'contain' => ['DirectorioEmpresas', 'Personas'],
        ]);

        $this->set(compact('directorioPersona'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $directorioPersona = $this->DirectorioPersonas->newEmptyEntity();
        if ($this->request->is('post')) {
            $directorioPersona = $this->DirectorioPersonas->patchEntity($directorioPersona, $this->request->getData());
            if ($this->DirectorioPersonas->save($directorioPersona)) {
                $this->Flash->success(__('The directorio persona has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The directorio persona could not be saved. Please, try again.'));
        }
        $empresas = $this->DirectorioPersonas->DirectorioEmpresas->find('list', ['limit' => 200]);
        $personas = $this->DirectorioPersonas->Personas->find('list', ['limit' => 200]);
        $this->set(compact('directorioPersona', 'empresas', 'personas'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Directorio Persona id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $directorioPersona = $this->DirectorioPersonas->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $directorioPersona = $this->DirectorioPersonas->patchEntity($directorioPersona, $this->request->getData());
            if ($this->DirectorioPersonas->save($directorioPersona)) {
                $this->Flash->success(__('The directorio persona has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The directorio persona could not be saved. Please, try again.'));
        }
        $empresas = $this->DirectorioPersonas->Empresas->find('list', ['limit' => 200]);
        $personas = $this->DirectorioPersonas->Personas->find('list', ['limit' => 200]);
        $this->set(compact('directorioPersona', 'empresas', 'personas'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Directorio Persona id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $directorioPersona = $this->DirectorioPersonas->get($id);
        if ($this->DirectorioPersonas->delete($directorioPersona)) {
            $this->Flash->success(__('The directorio persona has been deleted.'));
        } else {
            $this->Flash->error(__('The directorio persona could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getOne($id = "") {
        try {
            $item = $this->DirectorioPersonas->find()->where([
                'DirectorioPersonas.id' => $id,
            ])->contain(['Personas'])->first();

            if ($item) {
                $respuesta = [
                    'success' => true,
                    'data' => $item,
                    'message' => 'Busqueda exitosa',
                ];
            } else {
                $respuesta = [
                    'success' => false,
                    'data' => '',
                    'message' => 'No se encontro registro',
                ];
            }

        } catch (\Throwable $e) {
            $respuesta = [
                'success' => false,
                'data' => '',
                'message' => 'ERROR: ' . $e->getMessage(),
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }
}
