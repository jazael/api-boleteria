<?php
/**
 * Created by PhpStorm.
 * User: jazael.faubla
 * Date: 01/02/20
 * Time: 16:43
 */
namespace App\Modules\Bus\Model;

use App\Core\Utilities\Response,
    DateTimeZone, DateTime,
    \Exception;
class Bus
{
    private $db;
    private $logger;
    private $timeZone;
    private $currentDate;
    private $response;
    private $result;
    private $status;

    public function __construct($db, $logger)
    {
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
                ->insertInto('bus', $data)
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

    public function listar(String $fechainicio, String $fechafin, String $estado) {
        $arrBuses = [];
        try {
            $queryExecute = $this->db
                ->from('bus')
                ->select(null)
                ->select(['id', 'numerobus', 'numeromatricula', 'chasis', 'numeroasientos'])
                ->where('createdat >= ?', date('Y-m-d H:i:s', strtotime($fechainicio)))
                ->where('createdat <= ?', date('Y-m-d H:i:s', strtotime($fechafin . ' ' . date('H:i:s'))))
                //->where('estado', $estado)
                //->where('idempresa', $idempresa)
                ->orderBy('id ASC');
            $arrBuses = $queryExecute->fetchAll();
            $this->status = 'success';
            $this->result = true;
        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }

        return $this->response->SetResponse($arrBuses, ['status' => $this->status, 'result' => $this->result]);
    }

    public function actualizar($data = []) {
        try {
            $this->db->update('bus', $data)
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
            $this->db->deleteFrom('bus')
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