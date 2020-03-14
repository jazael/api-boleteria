<?php
$container = $app->getContainer();

//$app->add(function ($req, $res, $next) {
//    $response = $next($req, $res);
//    return $response
//            ->withHeader('Access-Control-Allow-Origin', '*')
//            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
//            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
//});
// View renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};
// Monolog logger
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};
// Database connection instance
$container['db'] = function($c) {
    $connection = null;
    try {
        $connectionString = $c->get('settings')['connectionString'];
        $pdo = new PDO($connectionString['dns'], $connectionString['user'], $connectionString['pass']);
        //$pdo->setAttribute(PDO::MY);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $connection = new \Envms\FluentPDO\Query($pdo);
        $c->logger->info(date('Y-m-d h:i:s') .' - Conectado a MYSQL 5.8.');
    } catch (Exception $ex) {
        $c->logger->error(date('Y-m-d h:i:s') .' - '. $ex->getCode() .' - '. $ex->getMessage());
    }

    return $connection;
};
// Models
$container['model'] = function($c) {
    return (object) [
        'auth' => new App\Core\Authentication\Model\Authentication($c->db, $c->logger),
        'empresa' => new App\Modules\Empresa\Model\Empresa($c->db, $c->logger),
        'agencia' => new App\Modules\Agencia\Model\Agencia($c->db, $c->logger),
        'bus' => new App\Modules\Bus\Model\Bus($c->db, $c->logger),
        'persona' => new App\Modules\persona\Model\persona($c->db, $c->logger),
        'usuario' => new App\Modules\usuario\Model\usuario($c->db, $c->logger),
    ];
};