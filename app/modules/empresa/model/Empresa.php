<?php
/**
 * Created by PhpStorm.
 * User: miguel.faubla
 * Date: 01/02/20
 * Time: 12:37
 */
namespace App\Modules\Empresa\Model;

use App\Core\Utilities\Response,
    DateTimeZone, DateTime,
    \Exception;

class Empresa {
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
                ->insertInto('empresa', $data)
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
        $arrEmpresas = [];
        try {
            $queryExecute = $this->db
                ->from('empresa')
                ->select(null)
                ->select(['id', 'ruc', 'razonsocial'])
                ->where('createdat >= ?', date('Y-m-d H:i:s', strtotime($fechainicio)))
                ->where('createdat <= ?', date('Y-m-d H:i:s', strtotime($fechafin . ' ' . date('H:i:s'))))
                ->where('estado', $estado)
                ->orderBy('id ASC');
            $arrEmpresas = $queryExecute->fetchAll();
            $this->status = 'success';
            $this->result = true;
        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }

        return $this->response->SetResponse($arrEmpresas, ['status' => $this->status, 'result' => $this->result]);
    }

    public function actualizar($data = []) {
        try {
            $this->db->update('empresa', $data)
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
            $this->db->deleteFrom('empresa')
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

    public function listamultiple(String $params) {
        $arrMuliple = [];
        $arrParams = explode(",", $params);
        try {
            foreach ($arrParams as $value) {
                switch ($value) {
                    case 'empresa':
                        $queryExecute = $this->db
                            ->from('empresa')
                            ->select(null)
                            ->select(['id', 'nombrecomercial'])
                            ->where('estado', "A")
                            ->orderBy('nombrecomercial ASC');
                        $arrMuliple["empresas"] = $queryExecute->fetchAll();
                        break;
                    case 'agencia':
                        $queryExecute = $this->db
                            ->from('agencia')
                            ->select(null)
                            ->select(['id', 'direccion', 'numeroagencia'])
                            ->where('estado', "A")
                            ->orderBy('direccion ASC');
                        $arrMuliple["agencias"] = $queryExecute->fetchAll();
                        break;
                    case 'propietario':
                        $queryExecute = $this->db
                            ->from('propietario')
                            ->leftJoin('personas ON personas.id = propietario.idpersona')
                            ->select(null)
                            ->select(['propietario.id', 'concat(personas.nombres, " ", personas.apellidos) AS nombrecompleto'])
                            ->where('propietario.estado', "A")
                            ->orderBy('personas.nombres ASC');
                        $arrMuliple["propietarios"] = $queryExecute->fetchAll();
                        break;
                    case 'chofer':
                        $queryExecute = $this->db
                            ->from('chofer')
                            ->leftJoin('personas ON personas.id = chofer.idpersona')
                            ->select(null)
                            ->select(['chofer.id', 'concat(personas.nombres, " ", personas.apellidos) AS nombrecompleto'])
                            ->where('chofer.estado', "A")
                            ->orderBy('personas.nombres ASC');
                        $arrMuliple["choferes"] = $queryExecute->fetchAll();
                        break;
                }
            }
            $this->status = 'success';
            $this->result = true;
        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }

        return $this->response->SetResponse($arrMuliple, ['status' => $this->status, 'result' => $this->result]);
    }


}

