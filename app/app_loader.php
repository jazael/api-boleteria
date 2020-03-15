<?php
$base = __DIR__ . '/../app/';
$folders = [
    'core/authentication/route',
    'core/authentication/model',
    'core/middleware',
    'core/resource',
    'core/utilities',
    'modules/empresa/route',
    'modules/empresa/model',
    'modules/agencia/route',
    'modules/agencia/model',
    'modules/bus/route',
    'modules/bus/model',
    'modules/persona/route',
    'modules/persona/model',
    'modules/usuario/route',
    'modules/usuario/model',
    'modules/cliente/route',
    'modules/cliente/model',
    'modules/lugar/route',
    'modules/lugar/model',
    'modules/ruta/route',
    'modules/ruta/model',
    'modules/ticket/route',
    'modules/ticket/model',
];

foreach($folders as $f) {
    foreach (glob($base . "$f/*.php") as $k => $filename) {
        require $filename;
    }
}