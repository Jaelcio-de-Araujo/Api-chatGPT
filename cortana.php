#!/usr/bin/env php
<?php

// Carrega as configurações do arquivo de configuração
$config = require 'config.php';

// Define a chave da API do OpenAI
$api_key = $config['openai_api_key'];

// Define a pergunta a ser enviada para a API do OpenAI "pode ser passada como um argumento de linha de comando"
$pergunta = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : 'O que é php?';

// Define os cabeçalhos da requisição para a API do OpenAI
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer '. $api_key
];

// Define os dados da requisição para a API do OpenAI
$data = [
    'method' => 'post',
    'model' => 'text-davinci-003',
    'prompt' => $pergunta,
    'max_tokens' => 2048,
    'temperature' => 0.5,
    'n' => 1,
    'stop' => ['\n'],
];

// Inicializa o cURL para fazer uma requisição para a API do OpenAI
$ch = curl_init();

// Define a URL da requisição para a API do OpenAI
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/completions');

// Define o método da requisição como POST
curl_setopt($ch, CURLOPT_POST, 1);

// Define os dados da requisição
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Define os cabeçalhos da requisição
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Define que a resposta deve ser retornada como string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

try {
    // Executa a requisição para a API do OpenAI e armazena a resposta em uma variável
    $response = curl_exec($ch);
    if ($response === false) {
        throw new Exception(curl_error($ch), curl_errno($ch));
    }
} catch (Exception $e) {
    // Exibe uma mensagem de erro se ocorrer um erro ao fazer a requisição para a API do OpenAI
    echo "Ocorreu um erro ao fazer a requisição para a API do OpenAI: {$e->getMessage()}";
} finally {
    // Fecha o cURL
    curl_close($ch);
}

// Verifica se a resposta está vazia
if(empty($response)) {
    // Exibe uma mensagem de erro se a resposta estiver vazia
    echo "Não foi possível obter uma resposta.\n Veja o erro: "."\n var_dump($response)";
    exit;
}

// Exibe a resposta da API do OpenAI
echo $response;
