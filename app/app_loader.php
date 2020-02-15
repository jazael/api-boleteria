<?php
$base = __DIR__ . '/../app/';
$folders = [
    'core/utilities',
    'modules/empresa/route',
    'modules/empresa/model',
    'modules/agencia/route',
    'modules/agencia/model',
    'modules/bus/route',
    'modules/bus/model',
];

foreach($folders as $f) {
    foreach (glob($base . "$f/*.php") as $k => $filename) {
        require $filename;
    }
}