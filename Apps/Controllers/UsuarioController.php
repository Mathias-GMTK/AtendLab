<?php


class UsuarioController
{
    private PDO $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function listar(): void
    {
        header('Content-type: aplication/json; charset=utf-8');

        $sql = 'SELECT id, nome, email, perfil, status, criado_em
                FROM  usuarios
                ORDER BY id DESC';

        $stmt = $this->pdo->query($sql);
        $usuarios = $stmt->fetchAll(pdo::FETCH_ASSOC);

        echo json_encode($usuarios, JSON_PRETTY_PRINT |  JSON_UNISCAPED_UNICODE);
    }
}