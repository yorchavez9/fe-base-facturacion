<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * StockHistorial Controller
 *
 * @property \App\Model\Table\StockHistorialTable $StockHistorial
 * @method \App\Model\Entity\StockHistorial[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StockHistorialController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Items', 'Usuarios', 'Almacenes'],
        ];
        $stockHistorial = $this->paginate($this->StockHistorial);

        $this->set(compact('stockHistorial'));
    }

    /**
     * View method
     *
     * @param string|null $id Stock Historial id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $stockHistorial = $this->StockHistorial->get($id, [
            'contain' => ['Items', 'Usuarios', 'Almacenes'],
        ]);

        $this->set(compact('stockHistorial'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $stockHistorial = $this->StockHistorial->newEmptyEntity();
        if ($this->request->is('post')) {
            $stockHistorial = $this->StockHistorial->patchEntity($stockHistorial, $this->request->getData());
            if ($this->StockHistorial->save($stockHistorial)) {
                $this->Flash->success(__('The stock historial has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The stock historial could not be saved. Please, try again.'));
        }
        $items = $this->StockHistorial->Items->find('list', ['limit' => 200]);
        $usuarios = $this->StockHistorial->Usuarios->find('list', ['limit' => 200]);
        $almacenes = $this->StockHistorial->Almacenes->find('list', ['limit' => 200]);
        $this->set(compact('stockHistorial', 'items', 'usuarios', 'almacenes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Stock Historial id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $stockHistorial = $this->StockHistorial->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $stockHistorial = $this->StockHistorial->patchEntity($stockHistorial, $this->request->getData());
            if ($this->StockHistorial->save($stockHistorial)) {
                $this->Flash->success(__('The stock historial has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The stock historial could not be saved. Please, try again.'));
        }
        $items = $this->StockHistorial->Items->find('list', ['limit' => 200]);
        $usuarios = $this->StockHistorial->Usuarios->find('list', ['limit' => 200]);
        $almacenes = $this->StockHistorial->Almacenes->find('list', ['limit' => 200]);
        $this->set(compact('stockHistorial', 'items', 'usuarios', 'almacenes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Stock Historial id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $stockHistorial = $this->StockHistorial->get($id);
        if ($this->StockHistorial->delete($stockHistorial)) {
            $this->Flash->success(__('The stock historial has been deleted.'));
        } else {
            $this->Flash->error(__('The stock historial could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function registrarMovimiento($data) {
        $obj = $this->StockHistorial->newEntity($data);
        $obj = $this->StockHistorial->save($obj);

        return $obj;
    }
}
