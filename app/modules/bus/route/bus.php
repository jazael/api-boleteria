<?php
/**
 * Created by PhpStorm.
 * User: jazael.faubla
 * Date: 01/02/20
 * Time: 16:43
 */

use App\Core\Middleware\AuthMiddleware;

$app->group('/bus/', function () {

    $this->post('registrar', function ($req, $res, $args) {
        $data = $req->getParsedBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bus->registrar($data))
            );
    });

    $this->get('listar/{fechainicio}/{fechafin}/{estado}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bus->listar($args['fechainicio'], $args['fechafin'], $args['estado']))
            );
    });

    $this->put('actualizar', function ($req, $res, $args) {
        $data = $req->getBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bus->actualizar($data))
            );
    });

    $this->delete('eliminar/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bus->eliminar($args['id']))
            );
    });
});