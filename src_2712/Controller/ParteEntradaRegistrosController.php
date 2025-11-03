<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ParteEntradaRegistros Controller
 *
 * @property \App\Model\Table\ParteEntradaRegistrosTable $ParteEntradaRegistros
 * @method \App\Model\Entity\ParteEntradaRegistro[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ParteEntradaRegistrosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParteEntradas', 'Items'],
        ];
        $parteEntradaRegistros = $this->paginate($this->ParteEntradaRegistros);

        $this->set(compact('parteEntradaRegistros'));
    }

    /**
     * View method
     *
     * @param string|null $id Parte Entrada Registro id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $parteEntradaRegistro = $this->ParteEntradaRegistros->get($id, [
            'contain' => ['ParteEntradas', 'Items'],
        ]);

        $this->set(compact('parteEntradaRegistro'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $parteEntradaRegistro = $this->ParteEntradaRegistros->newEmptyEntity();
        if ($this->request->is('post')) {
            $parteEntradaRegistro = $this->ParteEntradaRegistros->patchEntity($parteEntradaRegistro, $this->request->getData());
            if ($this->ParteEntradaRegistros->save($parteEntradaRegistro)) {
                $this->Flash->success(__('The parte entrada registro has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The parte entrada registro could not be saved. Please, try again.'));
        }
        $parteEntradas = $this->ParteEntradaRegistros->ParteEntradas->find('list', ['limit' => 200]);
        $items = $this->ParteEntradaRegistros->Items->find('list', ['limit' => 200]);
        $this->set(compact('parteEntradaRegistro', 'parteEntradas', 'items'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Parte Entrada Registro id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $parteEntradaRegistro = $this->ParteEntradaRegistros->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $parteEntradaRegistro = $this->ParteEntradaRegistros->patchEntity($parteEntradaRegistro, $this->request->getData());
            if ($this->ParteEntradaRegistros->save($parteEntradaRegistro)) {
                $this->Flash->success(__('The parte entrada registro has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The parte entrada registro could not be saved. Please, try again.'));
        }
        $parteEntradas = $this->ParteEntradaRegistros->ParteEntradas->find('list', ['limit' => 200]);
        $items = $this->ParteEntradaRegistros->Items->find('list', ['limit' => 200]);
        $this->set(compact('parteEntradaRegistro', 'parteEntradas', 'items'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Parte Entrada Registro id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $parteEntradaRegistro = $this->ParteEntradaRegistros->get($id);
        if ($this->ParteEntradaRegistros->delete($parteEntradaRegistro)) {
            $this->Flash->success(__('The parte entrada registro has been deleted.'));
        } else {
            $this->Flash->error(__('The parte entrada registro could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function saveRegistro($data=[]){
        $registro = $this->ParteEntradaRegistros->newEmptyEntity();
        $registro = $this->ParteEntradaRegistros->patchEntity($registro, $data);
        return $this->ParteEntradaRegistros->save($registro);
    }
}
