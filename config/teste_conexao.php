<?php
// config/teste_conexao.php
// Execute este arquivo para verificar se a conexão está funcionando

require_once 'conexao.php';

$conn = conectar();

echo "✅ Conexão com o banco de dados realizada com sucesso!<br>";
echo "🗄️ Banco: " . DB_NAME . "<br>";
echo "🖥️ Host: " . DB_HOST . "<br>";
echo "📦 Charset: " . DB_CHARSET . "<br>";

// Verifica tabelas criadas
$result = $conn->query("SHOW TABLES");
echo "<br><strong>Tabelas encontradas:</strong><ul>";
while ($row = $result->fetch_array()) {
    echo "<li>" . $row[0] . "</li>";
}
echo "</ul>";

$conn->close();
