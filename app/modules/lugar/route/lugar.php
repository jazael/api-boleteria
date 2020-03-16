<?php
/**
 * Created by PhpStorm.
 * User: jazael.faubla
 * Date: 14/03/20
 * Time: 23:08
 */

use App\Core\Middleware\AuthMiddleware;

$app->group('/lugar/', function () {
    $this->post('registrar', function ($req, $res, $args) {
        $data = $req->getParsedBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->lugar->registrar($data))
            );
    });

    $this->get('listar/{estado}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->lugar->listar($args['estado']))
            );
    });

    $this->put('actualizar', function ($req, $res, $args) {
        $data = $req->getParsedBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->lugar->actualizar($data))
            );
    });

    $this->delete('eliminar/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->lugar->eliminar($args['id']))
            );
    });
});