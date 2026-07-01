<?php

class PessoasController
{
    private PDO $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function listar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $stmt = $this->pdo->query(
            'SELECT id, nome, documento, telefone, email, curso, periodo, status, criado_em
             FROM pessoas
             ORDER BY nome ASC'
        );

        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function buscar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID obrigatório.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $stmt = $this->pdo->prepare(
            'SELECT id, nome, documento, telefone, email, curso, periodo, observacoes, status, criado_em
             FROM pessoas WHERE id = :id'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $pessoa = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pessoa) {
            http_response_code(404);
            echo json_encode(['erro' => 'Pessoa não encontrada.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        echo json_encode($pessoa, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function criar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $nome       = trim($_POST['nome']       ?? '');
        $documento  = trim($_POST['documento']  ?? '');
        $email      = trim($_POST['email']      ?? '');
        $telefone   = trim($_POST['telefone']   ?? '');
        $curso      = trim($_POST['curso']      ?? '');
        $periodo    = trim($_POST['periodo']    ?? '');
        $observacoes = trim($_POST['observacoes'] ?? '');
        $status     = $_POST['status']          ?? 'ativo';

        if ($nome === '' || $documento === '' || $email === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'Nome, documento e e-mail são obrigatórios.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['erro' => 'E-mail inválido.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        if (!in_array($status, ['ativo', 'inativo'], true)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Status inválido.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO pessoas (nome, documento, telefone, email, curso, periodo, observacoes, status)
                 VALUES (:nome, :documento, :telefone, :email, :curso, :periodo, :observacoes, :status)'
            );
            $stmt->bindValue(':nome',        $nome);
            $stmt->bindValue(':documento',   $documento);
            $stmt->bindValue(':telefone',    $telefone);
            $stmt->bindValue(':email',       $email);
            $stmt->bindValue(':curso',       $curso);
            $stmt->bindValue(':periodo',     $periodo);
            $stmt->bindValue(':observacoes', $observacoes);
            $stmt->bindValue(':status',      $status);
            $stmt->execute();

            http_response_code(201);
            echo json_encode([
                'mensagem' => 'Pessoa cadastrada com sucesso.',
                'id'       => $this->pdo->lastInsertId()
            ], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                http_response_code(409);
                echo json_encode(['erro' => 'Documento já cadastrado.'], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(500);
                echo json_encode(['erro' => 'Erro ao cadastrar pessoa.'], JSON_UNESCAPED_UNICODE);
            }
        }
    }

    public function atualizar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id         = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
                      ?: filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
        $nome       = trim($_POST['nome']       ?? '');
        $documento  = trim($_POST['documento']  ?? '');
        $email      = trim($_POST['email']      ?? '');
        $telefone   = trim($_POST['telefone']   ?? '');
        $curso      = trim($_POST['curso']      ?? '');
        $periodo    = trim($_POST['periodo']    ?? '');
        $observacoes = trim($_POST['observacoes'] ?? '');
        $status     = $_POST['status']          ?? 'ativo';

        if (!$id || $nome === '' || $documento === '' || $email === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'ID, nome, documento e e-mail são obrigatórios.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['erro' => 'E-mail inválido.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $stmt = $this->pdo->prepare(
                'UPDATE pessoas
                 SET nome = :nome, documento = :documento, telefone = :telefone,
                     email = :email, curso = :curso, periodo = :periodo,
                     observacoes = :observacoes, status = :status
                 WHERE id = :id'
            );
            $stmt->bindValue(':nome',        $nome);
            $stmt->bindValue(':documento',   $documento);
            $stmt->bindValue(':telefone',    $telefone);
            $stmt->bindValue(':email',       $email);
            $stmt->bindValue(':curso',       $curso);
            $stmt->bindValue(':periodo',     $periodo);
            $stmt->bindValue(':observacoes', $observacoes);
            $stmt->bindValue(':status',      $status);
            $stmt->bindValue(':id',          $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Pessoa atualizada com sucesso.'], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar pessoa.'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function inativar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID obrigatório.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $stmt = $this->pdo->prepare('UPDATE pessoas SET status = :status WHERE id = :id');
            $stmt->bindValue(':status', 'inativo');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Pessoa inativada com sucesso.'], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao inativar pessoa.'], JSON_UNESCAPED_UNICODE);
        }
    }
}