<?php

use Swoole\Coroutine as Co;

 
$host = 'db';
$db = 'phpswoole';
$user = 'username';
$password = 'userpass';


$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false 
]);
$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

Co\run(function () use ($pdo) {
    
    $stmt = $pdo->prepare('INSERT INTO example (column_a, column_b) VALUES (:column_a, :column_b)');

    $numRegistros = 1000;
    $batchSize = 100; 
    $values = [];

    
    for ($i = 1; $i <= $numRegistros; $i++) {
        $values[] = [$i, rand(1, 100)];

        
        if (count($values) === $batchSize) {
            Co::create(function () use ($pdo, $stmt, &$values) {
                foreach ($values as $data) {
                    $stmt->execute(['column_a' => $data[0], 'column_b' => $data[1]]);
                    var_dump('inseriu: ', ['column_a' => $data[0], 'column_b' => $data[1]]);
                }
            });
            $values = []; 
        }
    }

    
    if (!empty($values)) {
        Co::create(function () use ($pdo, $stmt, $values) {
            foreach ($values as $data) {
                $stmt->execute(['column_a' => $data[0], 'column_b' => $data[1]]);
            }
        });
    }

    echo "Inserção concluída.";
});
