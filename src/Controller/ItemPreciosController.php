<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ItemPrecios Controller
 *
 * @property \App\Model\Table\ItemPreciosTable $ItemPrecios
 * @method \App\Model\Entity\ItemPrecio[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ItemPreciosController extends AppController
{
    public function preciosProducto( $item_id = 0){
        $resp = [
            'success'   =>  false,
            'data'      =>  null,
            'message'   =>  'Item no encontrado o sin productos'
        ];
        try {
            $data = $this->ItemPrecios->find()->where(['item_id' => $item_id]);
            $resp = [
                'success'   =>  true,
                'data'      =>  $data,
                'message'   =>  'Consulta exitosa'
            ];
        } catch (\Throwable $th) {
            $resp = [
                'success'   =>  false,
                'data'      =>  null,
                'message'   =>  'Ocurrio un error. Sin registros'
            ];
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($resp));
    }
    public function nuevoPrecio( $item_id = 0 ){
        try {
            $data = $this->request->getData();
            $precio = $this->ItemPrecios->newEntity([
                'precio' => $data['item_precio'] ?? 0,
                'descripcion' => $data['item_descripcion'] ?? '',
                'item_id' => $item_id
            ]);
            $precio = $this->ItemPrecios->save($precio);
            $resp = [
                'success'   =>  true,
                'data'      =>  $precio,
                'message'   =>  'Registro exitoso.'
            ];
        } catch (\Throwable $th) {
            $resp = [
                'success'   =>  false,
                'data'      =>  null,
                'message'   =>  'Ocurrio un error. Sin registros'
            ];
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($resp));
    }
}
