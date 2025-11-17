<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SunatFeComunicacionBajas Controller
 *
 * Controlador para gestionar comunicaciones de baja a SUNAT
 */
class SunatFeComunicacionBajasController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('view_title', 'Comunicación de Bajas');
    }

    /**
     * Listar comunicaciones de baja vía AJAX
     *
     * @return \Cake\Http\Response
     */
    public function listar()
    {
        $this->autoRender = false;

        $flag_dev = $this->getConfig('flag_dev', true);
        $api_base = $flag_dev ? 'http://localhost/fe-api/' : 'https://fepi.apuuraydev.com/fe-api';

        $url = $api_base . 'sunat-fe-comunicacion-bajas/listar';

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'ignore_errors' => true
                ]
            ]);

            $json_response = @file_get_contents($url, false, $context);

            if ($json_response !== false) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody($json_response);
            } else {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Error al conectar con la API'
                    ]));
            }
        } catch (\Exception $e) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]));
        }
    }

    /**
     * Ver detalle de una comunicación de baja
     *
     * @param string|null $id ID de la comunicación de baja
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function view($id = null)
    {
        $flag_dev = $this->getConfig('flag_dev', true);
        $api_base = $flag_dev ? 'http://localhost/fe-api/' : 'https://fepi.apuuraydev.com/fe-api';

        $url = $api_base . 'sunat-fe-comunicacion-bajas/view/' . $id;

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'ignore_errors' => true
                ]
            ]);

            $json_response = @file_get_contents($url, false, $context);

            if ($json_response !== false) {
                $response = json_decode($json_response, true);

                if (isset($response['success']) && $response['success']) {
                    $comunicacion = $response['data'];
                    $this->set('comunicacion', $comunicacion);
                    $this->set('view_title', 'Detalle Comunicación de Baja');
                } else {
                    $this->Flash->error('No se encontró la comunicación de baja');
                    return $this->redirect(['action' => 'index']);
                }
            } else {
                $this->Flash->error('Error al conectar con la API');
                return $this->redirect(['action' => 'index']);
            }
        } catch (\Exception $e) {
            $this->Flash->error('Error: ' . $e->getMessage());
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Descargar archivo XML de comunicación de baja
     *
     * @param string|null $id ID de la comunicación de baja
     * @return \Cake\Http\Response
     */
    public function descargarXml($id = null)
    {
        $flag_dev = $this->getConfig('flag_dev', true);
        $api_base = $flag_dev ? 'http://localhost/fe-api/' : 'https://fepi.apuuraydev.com/fe-api';

        $url = $api_base . 'sunat-fe-comunicacion-bajas/descargar-xml/' . $id;

        return $this->redirect($url);
    }

    /**
     * Descargar archivo CDR de comunicación de baja
     *
     * @param string|null $id ID de la comunicación de baja
     * @return \Cake\Http\Response
     */
    public function descargarCdr($id = null)
    {
        $flag_dev = $this->getConfig('flag_dev', true);
        $api_base = $flag_dev ? 'http://localhost/fe-api/' : 'https://fepi.apuuraydev.com/fe-api';

        $url = $api_base . 'sunat-fe-comunicacion-bajas/descargar-cdr/' . $id;

        return $this->redirect($url);
    }
}
