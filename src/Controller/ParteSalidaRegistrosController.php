<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ParteSalidaRegistros Controller
 *
 * @property \App\Model\Table\ParteSalidaRegistrosTable $ParteSalidaRegistros
 * @method \App\Model\Entity\ParteSalidaRegistro[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ParteSalidaRegistrosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParteSalidas', 'Items'],
        ];
        $parteSalidaRegistros = $this->paginate($this->ParteSalidaRegistros);

        $this->set(compact('parteSalidaRegistros'));
    }

    /**
     * View method
     *
     * @param string|null $id Parte Salida Registro id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $parteSalidaRegistro = $this->ParteSalidaRegistros->get($id, [
            'contain' => ['ParteSalidas', 'Items'],
        ]);

        $this->set(compact('parteSalidaRegistro'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $parteSalidaRegistro = $this->ParteSalidaRegistros->newEmptyEntity();
        if ($this->request->is('post')) {
            $parteSalidaRegistro = $this->ParteSalidaRegistros->patchEntity($parteSalidaRegistro, $this->request->getData());
            if ($this->ParteSalidaRegistros->save($parteSalidaRegistro)) {
                $this->Flash->success(__('The parte salida registro has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The parte salida registro could not be saved. Please, try again.'));
        }
        $parteSalidas = $this->ParteSalidaRegistros->ParteSalidas->find('list', ['limit' => 200]);
        $items = $this->ParteSalidaRegistros->Items->find('list', ['limit' => 200]);
        $this->set(compact('parteSalidaRegistro', 'parteSalidas', 'items'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Parte Salida Registro id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $parteSalidaRegistro = $this->ParteSalidaRegistros->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $parteSalidaRegistro = $this->ParteSalidaRegistros->patchEntity($parteSalidaRegistro, $this->request->getData());
            if ($this->ParteSalidaRegistros->save($parteSalidaRegistro)) {
                $this->Flash->success(__('The parte salida registro has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The parte salida registro could not be saved. Please, try again.'));
        }
        $parteSalidas = $this->ParteSalidaRegistros->ParteSalidas->find('list', ['limit' => 200]);
        $items = $this->ParteSalidaRegistros->Items->find('list', ['limit' => 200]);
        $this->set(compact('parteSalidaRegistro', 'parteSalidas', 'items'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Parte Salida Registro id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $parteSalidaRegistro = $this->ParteSalidaRegistros->get($id);
        if ($this->ParteSalidaRegistros->delete($parteSalidaRegistro)) {
            $this->Flash->success(__('The parte salida registro has been deleted.'));
        } else {
            $this->Flash->error(__('The parte salida registro could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function saveRegistro($data=[]){
        $registro = $this->ParteSalidaRegistros->newEmptyEntity();
        $registro = $this->ParteSalidaRegistros->patchEntity($registro, $data);
        return $this->ParteSalidaRegistros->save($registro);
    }
}
