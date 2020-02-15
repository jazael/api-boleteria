<?php
/**
 * Created by PhpStorm.
 * User: jazael.faubla
 * Date: 01/02/20
 * Time: 16:06
 */

use App\Core\Middleware\AuthMiddleware;

$app->group('/agencia/', function () {

    $this->post('registrar', function ($req, $res, $args) {
        $data = $req->getParsedBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->agencia->registrar($data))
            );
    });

    $this->get('listar/{fechainicio}/{fechafin}/{estado}/{idempresa}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->agencia->listar($args['fechainicio'], $args['fechafin'], $args['estado'], $args['idempresa']))
            );
    });

    $this->put('actualizar', function ($req, $res, $args) {
        $data = $req->getBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->agencia->actualizar($data))
            );
    });

    $this->delete('eliminar/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->agencia->eliminar($args['id']))
            );
    });
});