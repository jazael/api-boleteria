<?php
/**
 * Created by PhpStorm.
 * User: miguel.faubla
 * Date: 01/02/20
 * Time: 12:37
 */
namespace App\Modules\usuario\Model;

use App\Core\Utilities\Response,
    DateTimeZone, DateTime,
    \Exception,
    App\Modules\Persona\Model\Persona;

class Usuario {
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
            if(!$existePersona){
                $arrPersona['cedula'] = $data['cedula'];
                $arrPersona['nombres'] = $data['nombres'];
                $arrPersona['apellidos'] = $data['apellidos'];
                $arrPersona['direccion'] = $data['direccion'];
                $arrPersona['estado'] = $data['estado'];
                $arrPersona['createdby'] = $data['createdby'];
                $result = $personaEntity->registroInterno($arrPersona);
                if($result['status'] === 'success'){
                    $idpersona = $result['id'];
                    $arrUsuario['username'] = $data['username'];
                    $arrUsuario['password'] = $data['password'];
                    $arrUsuario['idpersona'] = $idpersona;
                    $arrUsuario['estado'] = $data['estado'];
                    $arrUsuario['createdby'] = $data['createdby'];
                    $arrUsuario['createdat'] = $this->currentDate->format("Y-m-d H:i:s");
                    $this->db
                        ->insertInto('usuario', $arrUsuario)
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
                ->from('usuario')
                ->leftJoin('personas ON personas.id = usuario.idpersona')
                ->select(null)
                ->select(['usuario.id','cedula','concat(nombres," ",apellidos) as nombrecompleto','username'])
                ->where('usuario.estado', $estado)
                ->orderBy('usuario.id ASC');
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
                $arrUsuario['id'] = $data['id'];
                $arrUsuario['username'] = $data['username'];
                $arrUsuario['password'] = $data['password'];
                $arrUsuario['idpersona'] = $data['idpersona'];;
                $arrUsuario['estado'] = $data['estado'];
                $arrUsuario['updatedby'] = $data['updatedby'];
                $arrUsuario['updatedat'] = $this->currentDate->format("Y-m-d H:i:s");
                $this->db->update('usuario', $arrUsuario)
                    ->where('id', $arrUsuario['id'])
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

