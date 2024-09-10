<?php

use Swoole\Coroutine as Co;
use Swoole\Coroutine\Channel;

Co\run(function() {
    $filename = 'output.csv';

    if (!file_exists($filename)) {
        die("Arquivo '$filename' não encontrado.");
    }

    $fileSize = filesize($filename);
    $chunkSize = 1024 * 1024; // 1MB por parte
    $numChunks = ceil($fileSize / $chunkSize);

    // Cria um canal para sincronização
    $chan = new Channel();

    for ($i = 0; $i < $numChunks; $i++) {
        go(function() use ($filename, $i, $chunkSize, $fileSize, $chan, $numChunks) {
            $file = fopen($filename, 'r');

            if ($file === false) {
                $chan->push(false);
                return;
            }

            // Calcula o deslocamento e tamanho da parte
            $offset = $i * $chunkSize;
            $length = ($i === $numChunks - 1) ? ($fileSize - $offset) : $chunkSize;

            fseek($file, $offset);
            $chunk = fread($file, $length);

            // Imprime a parte do arquivo
            echo "Parte $i: \n" . $chunk . "\n";

            fclose($file);
            $chan->push(true);
        });
    }

    // Espera todas as corrotinas terminarem
    for ($i = 0; $i < $numChunks; $i++) {
        $chan->pop();
    }

    echo "Leitura e impressão do arquivo '$filename' concluída.";
});
