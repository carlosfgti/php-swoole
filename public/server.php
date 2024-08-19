<?php
use Swoole\Http\Server;

$http = new Server("0.0.0.0", 8000);

$http->on('request', function ($request, $response) {
    $response->end("Hello, Swoole!");
});

$http->start();