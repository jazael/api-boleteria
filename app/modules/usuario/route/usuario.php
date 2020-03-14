<?php
/**
 * Created by PhpStorm.
 * User: miguel.faubla
 * Date: 01/02/20
 * Time: 11:59
 */

use App\Core\Middleware\AuthMiddleware;

$app->group('/usuario/', function () {

    $this->post('registrar', function ($req, $res, $args) {
        $data = $req->getParsedBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->usuario->registrar($data))
            );
    });

    $this->get('listar/{estado}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->usuario->listar($args['estado']))
            );
    });

    $this->put('actualizar', function ($req, $res, $args) {
        $data = $req->getParsedBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->usuario->actualizar($data))
            );
    });

    /*$this->delete('eliminar/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->persona->eliminar($args['id']))
            );
    });*/
});