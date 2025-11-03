<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * DirectorioCategoriaRel Controller
 *
 * @property \App\Model\Table\DirectorioCategoriaRelTable $DirectorioCategoriaRel
 * @method \App\Model\Entity\DirectorioCategoriaRel[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DirectorioCategoriaRelController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Empresas', 'Categorias'],
        ];
        $directorioCategoriaRel = $this->paginate($this->DirectorioCategoriaRel);

        $this->set(compact('directorioCategoriaRel'));
    }

    /**
     * View method
     *
     * @param string|null $id Directorio Categoria Rel id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $directorioCategoriaRel = $this->DirectorioCategoriaRel->get($id, [
            'contain' => ['Empresas', 'Categorias'],
        ]);

        $this->set(compact('directorioCategoriaRel'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $directorioCategoriaRel = $this->DirectorioCategoriaRel->newEmptyEntity();
        if ($this->request->is('post')) {
            $directorioCategoriaRel = $this->DirectorioCategoriaRel->patchEntity($directorioCategoriaRel, $this->request->getData());
            if ($this->DirectorioCategoriaRel->save($directorioCategoriaRel)) {
                $this->Flash->success(__('The directorio categoria rel has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The directorio categoria rel could not be saved. Please, try again.'));
        }
        $empresas = $this->DirectorioCategoriaRel->Empresas->find('list', ['limit' => 200]);
        $categorias = $this->DirectorioCategoriaRel->Categorias->find('list', ['limit' => 200]);
        $this->set(compact('directorioCategoriaRel', 'empresas', 'categorias'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Directorio Categoria Rel id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $directorioCategoriaRel = $this->DirectorioCategoriaRel->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $directorioCategoriaRel = $this->DirectorioCategoriaRel->patchEntity($directorioCategoriaRel, $this->request->getData());
            if ($this->DirectorioCategoriaRel->save($directorioCategoriaRel)) {
                $this->Flash->success(__('The directorio categoria rel has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The directorio categoria rel could not be saved. Please, try again.'));
        }
        $empresas = $this->DirectorioCategoriaRel->Empresas->find('list', ['limit' => 200]);
        $categorias = $this->DirectorioCategoriaRel->Categorias->find('list', ['limit' => 200]);
        $this->set(compact('directorioCategoriaRel', 'empresas', 'categorias'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Directorio Categoria Rel id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $directorioCategoriaRel = $this->DirectorioCategoriaRel->get($id);
        if ($this->DirectorioCategoriaRel->delete($directorioCategoriaRel)) {
            $this->Flash->success(__('The directorio categoria rel has been deleted.'));
        } else {
            $this->Flash->error(__('The directorio categoria rel could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
