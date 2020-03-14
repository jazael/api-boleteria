<?php
/**
 * Created by PhpStorm.
 * User: miguel.faubla
 * Date: 01/02/20
 * Time: 12:37
 */
namespace App\Modules\cliente\Model;

use App\Core\Utilities\Response,
    DateTimeZone, DateTime,
    \Exception,
    App\Modules\Persona\Model\Persona;

class Cliente {
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
        $arrPersona = [];
        $existePersona = null;
        $personaEntity = new Persona($this->db, $this->logger);
        try {
            $existePersona = $personaEntity->findPersonaByCedula($data['cedula']);
            if ($existePersona && !$existePersona['count']){
                $arrPersona['cedula'] = $data['cedula'];
                $arrPersona['nombres'] = $data['nombres'];
                $arrPersona['apellidos'] = $data['apellidos'];
                $arrPersona['direccion'] = $data['direccion'];
                $arrPersona['estado'] = $data['estado'];
                $arrPersona['createdby'] = $data['createdby'];
                $result = $personaEntity->registroInterno($arrPersona);
                if($result['status'] === 'success'){
                    $idpersona = $result['id'];
                    $arrCliente['idpersona'] = $idpersona;
                    $arrCliente['estado'] = $data['estado'];
                    $arrCliente['createdby'] = $data['createdby'];
                    $arrCliente['createdat'] = $this->currentDate->format("Y-m-d H:i:s");
                    $this->db
                        ->insertInto('cliente', $arrCliente)
                        ->execute();
                    $this->status = 'success';
                    $this->result = true;
                }else{
                    $this->status = $result['status'];
                    $this->result = false;
                }
            }else{
                $this->status = 'repeat';
                $this->result = false;
            }
        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }

        return $this->response->setResponse($this->result, ['status' => $this->status]);
    }

    public function listar(String $estado) {
        $arrUsuario = [];
        try {
            $queryExecute = $this->db
                ->from('cliente')
                ->leftJoin('personas ON personas.id = cliente.idpersona')
                ->select(null)
                ->select(['cliente.id','cedula','concat(nombres," ",apellidos) as nombrecompleto'])
                ->where('cliente.estado', $estado)
                ->orderBy('cliente.id ASC');
            $arrUsuario = $queryExecute->fetchAll();
            $this->status = 'success';
            $this->result = true;
        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }

        return $this->response->SetResponse($arrUsuario, ['status' => $this->status, 'result' => $this->result]);
    }

    public function actualizar($data = []) {
        $arrPersona = [];
        $personaEntity = new Persona($this->db, $this->logger);
        try {
            $arrPersona['id'] = $data['idpersona'];
            $arrPersona['cedula'] = $data['cedula'];
            $arrPersona['nombres'] = $data['nombres'];
            $arrPersona['apellidos'] = $data['apellidos'];
            $arrPersona['direccion'] = $data['direccion'];
            $arrPersona['estado'] = $data['estado'];
            $arrPersona['updatedby'] = $data['updatedby'];
            $arrPersona['updatedat'] = $this->currentDate->format("Y-m-d H:i:s");
            $result = $personaEntity->actualizarInterno($arrPersona);
            if($result['status'] === 'success'){
                $arrCliente['id'] = $data['id'];
                $arrCliente['idpersona'] = $data['idpersona'];;
                $arrCliente['estado'] = $data['estado'];
                $arrCliente['updatedby'] = $data['updatedby'];
                $arrCliente['updatedat'] = $this->currentDate->format("Y-m-d H:i:s");
                $this->db->update('cliente', $arrCliente)
                    ->where('id', $arrCliente['id'])
                    ->execute();
                $this->status = 'success';
                $this->result = true;
            }else{
                $this->status = $result['status'];
                $this->result = false;
            }
        } catch (Exception $ex) {
            $this->status = $ex->getMessage();
            $this->result = false;
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
        }

        return $this->response->setResponse($this->result, ['status' => $this->status]);
    }
}

