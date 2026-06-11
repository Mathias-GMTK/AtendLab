<?php
// config/conexao.php
// Configurações de conexão com o banco de dados

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // padrão XAMPP: senha vazia
define('DB_NAME', 'atendelab');
define('DB_CHARSET', 'utf8mb4');

function conectar(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("❌ Erro na conexão: " . $conn->connect_error);
    }

    $conn->set_charset(DB_CHARSET);
    return $conn;
}
