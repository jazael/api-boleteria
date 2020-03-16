<?php
/**
 * Created by PhpStorm.
 * User: jazael.faubla
 * Date: 14/03/20
 * Time: 22:58
 */

use App\Core\Middleware\AuthMiddleware;

$app->group('/ruta/', function () {
    $this->post('registrar', function ($req, $res, $args) {
        $data = $req->getParsedBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->ruta->registrar($data))
            );
    });

    $this->put('actualizar', function ($req, $res, $args) {
        $data = $req->getParsedBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->ruta->actualizar($data))
            );
    });

    $this->delete('eliminar/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->ruta->eliminar($args['id']))
            );
    });
});