<?php
$urls = [
    'https://example.com',
    'https://example.org',
    'https://example.net',
];

foreach ($urls as $url) {
    $content = file_get_contents($url);
    echo "Fetched from $url\n";
}
use Swoole\Coroutine\Http\Client;

$urls = [
    'https://webhook.site/a0049908-27ae-4142-a938-bdf1c6505240?prm=1',
    'https://webhook.site/a0049908-27ae-4142-a938-bdf1c6505240?prm=2',
    'https://webhook.site/a0049908-27ae-4142-a938-bdf1c6505240?prm=3',
];
foreach ($urls as $url) {
    go(function () use ($url) {
        $parsed_url = parse_url($url);
        $host = $parsed_url['host'];
        $path = $parsed_url['path'] . (isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '/');

        $client = new Client($host, 80);
        $client->get($path);
        $client->close();
        echo "Fetched from $url\n";
    });
}
Swoole\Event::wait();