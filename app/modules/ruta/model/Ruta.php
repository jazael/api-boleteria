<?php
/**
 * Created by PhpStorm.
 * User: jazael.faubla
 * Date: 14/03/20
 * Time: 22:58
 */
namespace App\Modules\Ruta\Model;

use App\Core\Utilities\Response,
    DateTimeZone, DateTime,
    \Exception;

class Ruta {
    private $db;
    private $logger;
    private $timeZone;
    private $currentDate;
    private $response;
    private $result;
    private $status;

    public function __construct($db, $logger) {
        $this->db = $db;
        $this->logger = $logger;
        $this->response = new Response();
        $this->timeZone = new DateTimeZone("America/Guayaquil");
        $this->currentDate = new DateTime("now", $this->timeZone);
    }

    public function registrar($data = []) {
        try {
            $data['createdat'] = $this->currentDate->format("Y-m-d H:i:s");
            $this->db
                ->insertInto('rutas', $data)
                ->execute();

            $this->status = 'success';
            $this->result = true;

        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }

        return $this->response->setResponse($this->result, ['status' => $this->status]);
    }

    public function actualizar($data = []) {
        try {
            $this->db->update('rutas', $data)
                ->where('id', $data['id'])
                ->execute();
            $this->status = 'success';
            $this->result = true;
        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }

        return $this->response->setResponse($this->result, ['status' => $this->status]);
    }

    public function eliminar(int $id) {
        try {
            $this->db->deleteFrom('rutas')
                ->where('id', $id)
                ->execute();
            $this->status = 'success';
            $this->result = true;
        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }
    }
}