<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CuentaTipos Controller
 *
 * @property \App\Model\Table\CuentaTiposTable $CuentaTipos
 *
 * @method \App\Model\Entity\CuentaCanalLlegada[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CuentaTiposController extends AppController
{

    public function getOne($id = ''){
        $respuesta = [
            'success'  => false,
            'data' => '',
            'message' => 'Sin Resultados'
        ];

        $item = $this->CuentaTipos->find()->where(['id' => $id])->first();
        if ($item) {
            $respuesta = [
                'success'  => true,
                'data' => $item,
                'message' => 'éxito'
            ];

        }
        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode($respuesta));

    }

    public function getAll(){
        $items = $this->CuentaTipos->find()->order(['nombre' => 'ASC']);

        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode($items));

    }

    public function save(){
        $respuesta = [
            'success'  => false,
            'data' => '',
            'message' => 'Sin Resultados'
        ];

        if ($this->request->is('POST')) {
            $data = $this->request->getData();

            $canal = $this->CuentaTipos->find()->where(['id' => $data['id']])->first();
            if (!$canal)
            {
                $canal = $this->CuentaTipos->newEmptyEntity();
            }

            $canal = $this->CuentaTipos->patchEntity($canal, $data);

            if ($this->CuentaTipos->save($canal)) {

                $respuesta = [
                    'success'  => true,
                    'data' => $canal,
                    'message' => 'Canal de llegada guardado exitosamente'
                ];
            } else {
                $respuesta = [
                    'success'  => false,
                    'data' => '',
                    'message' => 'Error al guardar'
                ];
            }

        }

        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode($respuesta));
    }



    /**
     * Delete method
     *
     * @param string|null $id Cuenta Canal Llegada id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function del($id = null)
    {
        $respuesta = [
            'success'  => false,
            'data' => '',
            'message' => 'Sin Resultados'
        ];
        if ($this->request->is(['post', 'delete'])){
            $data = $this->request->getData();

            $this->CuentaTipos->deleteAll(['id in' => $data['ids']]);

            $respuesta = [
                'success'  => true,
                'data' => $data,
                'message' => 'eliminado correctamente'
            ];
        }
        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode($respuesta));
    }
}
