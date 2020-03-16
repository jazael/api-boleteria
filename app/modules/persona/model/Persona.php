<?php
/**
 * Created by PhpStorm.
 * User: miguel.faubla
 * Date: 01/02/20
 * Time: 12:37
 */
namespace App\Modules\Persona\Model;

use App\Core\Utilities\Response,
    DateTimeZone, DateTime,
    \Exception;

class Persona {
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
                ->insertInto('personas', $data)
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

    public function findPersonaByCedula(String $cedula){
        $countPerson = null;
        try{
            $queryExecute = $this->db
                ->from('personas')
                ->select(null)
                ->select('count(id) as counter')
                ->where('cedula', $cedula)
                ->orderBy('id ASC');
            $countPerson = $queryExecute->fetch();
            $this->status = 'success';
            $this->result = true;
        }catch (Exception $ex){
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }

        return ['count' => (int) $countPerson->counter, 'status' => $this->status];
    }

    public function registroInterno($data = []) {
        $id = null;
        try {
            $data['createdat'] = $this->currentDate->format("Y-m-d H:i:s");
            $id = $this->db
                ->insertInto('personas', $data)
                ->execute();
            $this->status = 'success';
            $this->result = true;

        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }
        return ['id' => $id, 'status' => $this->status];
    }

    public function listar(String $estado) {
        $arrPersonas = [];
        try {
            $queryExecute = $this->db
                ->from('personas')
                ->select(null)
                ->select(['id','cedula','concat(nombres," ",apellidos) as nombrecompleto','direccion'])
                ->where('estado', $estado)
                ->orderBy('id ASC');
            $arrPersonas = $queryExecute->fetchAll();
            $this->status = 'success';
            $this->result = true;
        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }

        return $this->response->SetResponse($arrPersonas, ['status' => $this->status, 'result' => $this->result]);
    }

    public function actualizar($data = []) {
        try {
            $this->db->update('personas', $data)
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

    public function actualizarInterno($data = []) {
        try {
            $this->db->update('personas', $data)
                ->where('id', $data['id'])
                ->execute();
            $this->status = 'success';
            $this->result = true;
        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }

        return ['status' => $this->status];
    }

    public function eliminar(int $id) {
        try {
            $this->db->deleteFrom('personas')
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

