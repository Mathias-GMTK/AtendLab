<?php
// public/index.php
require_once '../config/conexao.php';

$conn = conectar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/css/style.css">
    <title>AtendLab - Sistema de Atendimento</title>
</head>

<body>
    <div class="card">
        <div class="logo">AtendLab <span>Univille</span></div>
        <h2>Sistema de Atendimento</h2>

        <div class="status">✅ Conexão com banco: OK</div>

        <form method="POST" action="">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="seu@email.com" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="••••••••" required>

            <button type="submit">Entrar</button>
        </form>

        <p class="footer">AtendLab &copy; 2026 — Univille</p>
    </div>
</body>
</html>
<?php 
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once__DIR__'./../routes.php';