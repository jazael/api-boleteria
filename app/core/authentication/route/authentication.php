<?php
/**
 * Created by PhpStorm.
 * User: jazael.faubla
 * Date: 10/09/2019
 * Time: 14:10 PM
 */
$app->group('/auth/', function () {
    $this->post('authentication', function ($req, $res, $args) {
        $parameters = $req->getParsedBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->auth->authentication($parameters['user'], $parameters['password']))
            );
    });

    $this->post('guests', function ($req, $res, $args) {
        $parameters = $req->getParsedBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->auth->guests($parameters['user'], $parameters['password']))
            );
    });

    $this->post('refreshToken', function ($req, $res, $args) {
        $parameters = $req->getParsedBody();
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->auth->refreshToken($parameters['jwt']))
            );
    });
});