<?php
/**
 * Created by PhpStorm.
 * User: miguel.faubla
 * Date: 01/02/20
 * Time: 16:06
 */
namespace App\Modules\Agencia\Model;

use App\Core\Utilities\Response,
    DateTimeZone, DateTime,
    \Exception;

class Agencia
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
                ->insertInto('agencia', $data)
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

    public function listar(String $fechainicio, String $fechafin, String $estado, int $idempresa) {
        $arrAgencias = [];
        try {
            $queryExecute = $this->db
                ->from('empresa')
                ->select(null)
                ->select(['id', 'direccion', 'numeroagencia'])
                ->where('createdat >= ?', date('Y-m-d H:i:s', strtotime($fechainicio)))
                ->where('createdat <= ?', date('Y-m-d H:i:s', strtotime($fechafin . ' ' . date('H:i:s'))))
                ->where('estado', $estado)
                ->where('idempresa', $idempresa)
                ->orderBy('id ASC');
            $arrAgencias = $queryExecute->fetchAll();
            $this->status = 'success';
            $this->result = true;
        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }

        return $this->response->SetResponse($arrAgencias, ['status' => $this->status, 'result' => $this->result]);
    }

    public function actualizar($data = []) {
        try {
            $this->db->update('agencia', $data)
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
            $this->db->deleteFrom('agencia')
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