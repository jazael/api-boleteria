<?php
/**
 * Created by PhpStorm.
 * User: jazael.faubla
 * Date: 10/09/19
 * Time: 14:30
 */

namespace App\Core\Authentication\Model;
use App\Core\Utilities\AuthJWT,
    App\Core\Utilities\Response,
    \Exception;
use App\Core\Utilities\Utilities;

class Authentication
{
    private $db;
    private $response;
    private $logger;
    private $status;
    private $result;

    public function __construct($db, $logger) {
        $this->db = $db;
        $this->logger = $logger;
        $this->response = new Response();
    }

    public function authentication(String $user, String $password) {
        $resulSet = null;
        try {
            $resulSet = $this->db->from('usuario')
                ->select(null)
                ->select(array('id', 'username', 'idpersona', 'createdat'))
                ->where('username', $user)
                ->where('password', $password)
                ->where('estado', 'A');

            $objectUser = $resulSet->fetch();

            if($objectUser != null || $objectUser) {
                $token = AuthJWT::SignIn(
                    [
                        'codigo' => $objectUser->id,
                        'username' => $objectUser->username,
                        'idpersona' => $objectUser->idpersona,
                        'createdat' => $objectUser->createdat
                    ]
                );

                $this->response->result = $token;
                return $this->response->SetResponse(true);
            } else {
                return $this->response->SetResponse(false, 'Credenciales no validas');
            }
        } catch (Exception $ex) {
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
            return $this->response->SetResponse(false, 'Credenciales no validas');
        }
    }

    public function refreshToken(String $token) {
        $jwtRefresh = null;

        try{
            AuthJWT::checkIfUserValid($token);
        } catch (Exception $ex){
            $this->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
            $jwtRefresh = AuthJWT::refreshToken($token);
            $this->status = 'success';
            $this->result = true;

            return $this->response->SetResponse(['APP-TOKEN' => $jwtRefresh], ['status' => $this->status, 'result' => $this->result]);
        }

        return $this->response->SetResponse(['APP-TOKEN' => $jwtRefresh], ['status' => $this->status, 'result' => $this->result]);
    }

}