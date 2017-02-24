<?php

require __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Marcos\Spedefd;

$sped = new Spedefd();
$sped->razaoContribuente = 'Teste de arquivo';

echo $sped;