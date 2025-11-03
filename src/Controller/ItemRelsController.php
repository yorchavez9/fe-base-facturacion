<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ItemRels Controller
 *
 * @property \App\Model\Table\ItemRelsTable $ItemRels
 * @method \App\Model\Entity\ItemRel[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ItemRelsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Items', 'Item2s'],
        ];
        $itemRels = $this->paginate($this->ItemRels);

        $this->set(compact('itemRels'));
    }

    /**
     * View method
     *
     * @param string|null $id Item Rel id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $itemRel = $this->ItemRels->get($id, [
            'contain' => ['Items', 'Item2s'],
        ]);

        $this->set(compact('itemRel'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $itemRel = $this->ItemRels->newEmptyEntity();
        if ($this->request->is('post')) {
            $itemRel = $this->ItemRels->patchEntity($itemRel, $this->request->getData());
            if ($this->ItemRels->save($itemRel)) {
                $this->Flash->success(__('The item rel has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The item rel could not be saved. Please, try again.'));
        }
        $items = $this->ItemRels->Items->find('list', ['limit' => 200]);
        $item2s = $this->ItemRels->Item2s->find('list', ['limit' => 200]);
        $this->set(compact('itemRel', 'items', 'item2s'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Item Rel id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $itemRel = $this->ItemRels->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $itemRel = $this->ItemRels->patchEntity($itemRel, $this->request->getData());
            if ($this->ItemRels->save($itemRel)) {
                $this->Flash->success(__('The item rel has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The item rel could not be saved. Please, try again.'));
        }
        $items = $this->ItemRels->Items->find('list', ['limit' => 200]);
        $item2s = $this->ItemRels->Item2s->find('list', ['limit' => 200]);
        $this->set(compact('itemRel', 'items', 'item2s'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Item Rel id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $itemRel = $this->ItemRels->get($id);
        if ($this->ItemRels->delete($itemRel)) {
            $this->Flash->success(__('The item rel has been deleted.'));
        } else {
            $this->Flash->error(__('The item rel could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
