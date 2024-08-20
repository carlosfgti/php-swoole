<?php

use Swoole\WebSocket\Server;

$server = new Server("0.0.0.0", 9502);

$clients = [];

$server->on("Start", function(Server $server)
{
    echo "Swoole WebSocket Server is started at http://127.0.0.1:9502\n";
});

$server->on('Open', function(Server $server, Swoole\Http\Request $request) use (&$clients)
{
    echo "Open: {$request->fd} connected\n";
    $clients[] = $request->fd;
    $server->push($request->fd, "Hello, WebSocket!");
});

$server->on('Message', function(Server $server, Swoole\WebSocket\Frame $frame) use (&$clients)
{
    echo "received message: {$frame->data}\n";

    foreach ($clients as $client) {
        $server->push($client, $frame->data);
    }
});

$server->on('Close', function(Server $server, int $fd) use (&$clients)
{
    unset($clients[$fd]);
    echo "connection close: {$fd}\n";
});

$server->on('Disconnect', function(Server $server, int $fd)
{
    echo "connection disconnect: {$fd}\n";
});

$server->start();
