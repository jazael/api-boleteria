<?php
/**
 * Created by PhpStorm.
 * User: jazael.faubla
 * Date: 10/09/19
 * Time: 14:24
 */

namespace App\Core\Utilities;
use Firebase\JWT\JWT, Exception;
class AuthJWT {
    private static $secret_key = 'P*QMkP|Fzg-:vRJZ#r?GxH,fAv]g16wTZoB[+5R;0L`~AKw*Yecu+em~wTn69Q>';
    private static $encrypt = array('HS256');
    private static $aud = null;
    private static $minutes = 120;
    // Crea un nuevo token guardando la información del usuario que hemos autenticado
    public static function signIn($data, $hasAuthority = false) {
        $time = time();
        $minutos = self::$minutes;
        if ($hasAuthority) {
            $minutos = 525600;
        }

        $token = [
            'exp'  => $time + (60 * $minutos),
            'aud'  => self::auditMachine(),
            'data' => $data
        ];
        return JWT::encode($token, self::$secret_key);
    }
    // Verifica si el token ingresado es válido
    public static function checkIfUserValid($token) {
        if(empty($token)) {
            throw new Exception("Invalid token supplied.");
        }
        $decode = JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        );

        if($decode->aud !== self::auditMachine()) {
            throw new Exception("Invalid user logged in.");
        }
    }
    // Refresca el limite de tiempo del token
    public static function refreshToken($token) {
        if(empty($token)) {
            throw new Exception("Invalid token supplied.");
        }
        $decode = JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        );
        if($decode->aud !== self::auditMachine()) {
            throw new Exception("Invalid user logged in.");
        } else {
            $decode->exp = time() + (60 * self::$minutes);
        }
        return JWT::encode($decode, self::$secret_key);
    }

    // Obtiene la información del usuario guardada en el token
    public static function getInformationUserLogged($token) {
        return JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        )->data;
    }

    private static function auditMachine() {
        $machine = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $machine = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $machine = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $machine = $_SERVER['REMOTE_ADDR'];
        }
        $machine .= @$_SERVER['HTTP_USER_AGENT'];
        $machine .= gethostname();
        return sha1($machine);
    }

    // Crea un nuevo token para ingreso y validacion a los eventos creados desde la APP
    public static function signInEvents($data, int $seconds) {
        $time = time();
        $token = [
            'exp'  => $time + $seconds,
            'aud'  => self::auditMachine(),
            'data' => $data
        ];

        return JWT::encode($token, self::$secret_key);
    }
}