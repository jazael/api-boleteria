<?php
/**
 * Created by PhpStorm.
 * User: miguel.faubla
 * Date: 01/02/20
 * Time: 11:59
 */

use App\Core\Middleware\AuthMiddleware;

$app->group('/empresa/', function () {

    $this->post('registrar', function ($req, $res, $args) {
        $data = $req->getParsedBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->empresa->registrar($data))
            );
    });

    $this->get('listar/{fechainicio}/{fechafin}/{estado}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->empresa->listar($args['fechainicio'], $args['fechafin'], $args['estado']))
            );
    });

    $this->put('actualizar', function ($req, $res, $args) {
        $data = $req->getBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->empresa->actualizar($data))
            );
    });

    $this->delete('eliminar/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->empresa->eliminar($args['id']))
            );
    });

    $this->get('listamultiple/{params}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->empresa->listamultiple($args['params']))
            );
    });
});