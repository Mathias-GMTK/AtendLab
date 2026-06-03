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
    <title>AtendLab - Sistema de Atendimento</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .logo {
            font-size: 2rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 6px;
        }

        .logo span { color: #64748b; font-weight: 400; font-size: 1rem; }

        h2 {
            font-size: 1.2rem;
            color: #374151;
            margin-bottom: 28px;
        }

        .status {
            display: inline-block;
            background: #dcfce7;
            color: #16a34a;
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 0.85rem;
            margin-bottom: 28px;
        }

        label {
            display: block;
            text-align: left;
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 4px;
        }

        input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            margin-bottom: 16px;
            outline: none;
            transition: border 0.2s;
        }

        input:focus { border-color: #2563eb; }

        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        button:hover { background: #1d4ed8; }

        .footer {
            margin-top: 20px;
            font-size: 0.75rem;
            color: #9ca3af;
        }
    </style>
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
<?php $conn->close(); ?>
