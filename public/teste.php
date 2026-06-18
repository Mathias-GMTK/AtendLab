<?php
header('Content-Type: application/json');
$raw = file_get_contents("php://input");
$dados = json_decode($raw, true);

echo json_encode([
    "raw_recebido" => $raw,
    "decodificado" => $dados,
    "json_error" => json_last_error_msg()
]);