<?php
$startTime = microtime(true);

/** 
$filename = 'output.csv';
$file = fopen($filename, 'w'); // Abre o arquivo para escrita

if ($file === false) {
    die("Não foi possível abrir o arquivo para escrita.");
}

for ($i = 1; $i <= 50000; $i++) {
    $line = "coluna A $i; coluna B $i; coluna C $i\n";
    fwrite($file, $line); // Escreve a linha no arquivo
}

fclose($file); // Fecha o arquivo

echo "Arquivo '$filename' criado com sucesso. \n";
*/

use Swoole\Coroutine as Co;

Co\run(function() {
    $filename = 'output.csv';

    // Abre o arquivo para escrita assíncrona
    $file = fopen($filename, 'w');

    if ($file === false) {
        die("Não foi possível abrir o arquivo para escrita.");
    }

    // Cria um canal para sincronização
    $chan = new Co\Channel();

    // Número de corrotinas a serem usadas
    $coroutines = 10;
    $linesPerCoroutine = 50000 / $coroutines;

    for ($i = 0; $i < $coroutines; $i++) {
        go(function() use ($file, $i, $linesPerCoroutine, $chan) {
            $startLine = $i * $linesPerCoroutine + 1;
            $endLine = ($i + 1) * $linesPerCoroutine;

            for ($j = $startLine; $j <= $endLine; $j++) {
                $line = "coluna A $j; coluna B $j; coluna C $j\n";
                Co::fwrite($file, $line); // Escrita assíncrona
            }

            $chan->push(true); // Sinaliza a conclusão da corrotina
        });
    }

    // Espera todas as corrotinas terminarem
    for ($i = 0; $i < $coroutines; $i++) {
        $chan->pop();
    }

    fclose($file); // Fecha o arquivo

    echo "Arquivo '$filename' criado com sucesso.";
});

$endTime = microtime(true);
$executionTime = $endTime - $startTime;
echo "Tempo de execução: " . number_format($executionTime, 4) . " segundos. \n";
