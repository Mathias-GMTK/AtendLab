<?php


require_once 'conexao.php';

$conn = conectar();

echo "✅ Conexão com o banco de dados realizada com sucesso!<br>";
echo "🗄️ Banco: " . DB_NAME . "<br>";
echo "🖥️ Host: " . DB_HOST . "<br>";
echo "📦 Charset: " . DB_CHARSET . "<br>";


$result = $conn->query("SHOW TABLES");
echo "<br><strong>Tabelas encontradas:</strong><ul>";
while ($row = $result->fetch_array()) {
    echo "<li>" . $row[0] . "</li>";
}
echo "</ul>";

$conn->close();
