<?php

require __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Marcos\Spedefd;
// Todos os dados devem ser atribuidos sem formatacao, apenas o dado em si e valido.
$sped = new Spedefd();
$sped->razaoContribuente = 'Teste de arquivo';
$sped->cnpjContribuente = '000000000000';
echo $sped;