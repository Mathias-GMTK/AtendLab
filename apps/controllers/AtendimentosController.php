<?php

class AtendimentosController
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
            'SELECT a.id,
                    p.nome        AS pessoa,
                    t.nome        AS tipo,
                    u.nome        AS responsavel,
                    a.descricao,
                    a.status,
                    a.data_atendimento,
                    a.horario_atendimento,
                    a.observacao_final,
                    a.criado_em
             FROM atendimentos a
             JOIN pessoas            p ON p.id = a.pessoa_id
             JOIN tipos_atendimentos t ON t.id = a.tipo_atendimento_id
             JOIN usuarios           u ON u.id = a.usuario_id
             ORDER BY a.data_atendimento DESC, a.horario_atendimento DESC'
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
            'SELECT a.id,
                    a.pessoa_id, p.nome AS pessoa,
                    a.tipo_atendimento_id, t.nome AS tipo,
                    a.usuario_id, u.nome AS responsavel,
                    a.descricao, a.status,
                    a.data_atendimento, a.horario_atendimento,
                    a.observacao_final, a.criado_em
             FROM atendimentos a
             JOIN pessoas            p ON p.id = a.pessoa_id
             JOIN tipos_atendimentos t ON t.id = a.tipo_atendimento_id
             JOIN usuarios           u ON u.id = a.usuario_id
             WHERE a.id = :id'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $atendimento = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$atendimento) {
            http_response_code(404);
            echo json_encode(['erro' => 'Atendimento não encontrado.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $atendimento['protocolo'] = 'ATD-' . str_pad($atendimento['id'], 4, '0', STR_PAD_LEFT);

        echo json_encode($atendimento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function criar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $pessoa_id           = filter_var($_POST['pessoa_id']           ?? 0, FILTER_VALIDATE_INT);
        $tipo_atendimento_id = filter_var($_POST['tipo_atendimento_id'] ?? 0, FILTER_VALIDATE_INT);
        $usuario_id          = filter_var($_POST['usuario_id']          ?? 0, FILTER_VALIDATE_INT);
        $descricao           = trim($_POST['descricao']                 ?? '');
        $data_atendimento    = trim($_POST['data_atendimento']          ?? '');
        $horario_atendimento = trim($_POST['horario_atendimento']       ?? '');
        $status              = $_POST['status']                         ?? 'aberto';

        if (!$pessoa_id || !$tipo_atendimento_id || !$usuario_id || $descricao === '' || $data_atendimento === '' || $horario_atendimento === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'Todos os campos obrigatórios devem ser preenchidos.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        if (!in_array($status, ['aberto', 'em_andamento', 'concluido'], true)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Status inválido.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Valida pessoa
        $s = $this->pdo->prepare('SELECT id FROM pessoas WHERE id = :id AND status = :status');
        $s->execute([':id' => $pessoa_id, ':status' => 'ativo']);
        if (!$s->fetch()) {
            http_response_code(400);
            echo json_encode(['erro' => 'Pessoa não encontrada ou inativa.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Valida tipo
        $s = $this->pdo->prepare('SELECT id FROM tipos_atendimentos WHERE id = :id AND status = :status');
        $s->execute([':id' => $tipo_atendimento_id, ':status' => 'ativo']);
        if (!$s->fetch()) {
            http_response_code(400);
            echo json_encode(['erro' => 'Tipo de atendimento não encontrado ou inativo.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Valida usuario
        $s = $this->pdo->prepare('SELECT id FROM usuarios WHERE id = :id AND status = :status');
        $s->execute([':id' => $usuario_id, ':status' => 'ativo']);
        if (!$s->fetch()) {
            http_response_code(400);
            echo json_encode(['erro' => 'Usuário não encontrado ou inativo.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO atendimentos (pessoa_id, tipo_atendimento_id, usuario_id, descricao, status, data_atendimento, horario_atendimento)
                 VALUES (:pessoa_id, :tipo_atendimento_id, :usuario_id, :descricao, :status, :data_atendimento, :horario_atendimento)'
            );
            $stmt->bindValue(':pessoa_id',           $pessoa_id,           PDO::PARAM_INT);
            $stmt->bindValue(':tipo_atendimento_id', $tipo_atendimento_id, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id',          $usuario_id,          PDO::PARAM_INT);
            $stmt->bindValue(':descricao',           $descricao);
            $stmt->bindValue(':status',              $status);
            $stmt->bindValue(':data_atendimento',    $data_atendimento);
            $stmt->bindValue(':horario_atendimento', $horario_atendimento);
            $stmt->execute();

            http_response_code(201);
            echo json_encode([
                'mensagem'  => 'Atendimento registrado com sucesso.',
                'id'        => $this->pdo->lastInsertId(),
                'protocolo' => 'ATD-' . str_pad($this->pdo->lastInsertId(), 4, '0', STR_PAD_LEFT)
            ], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao registrar atendimento.'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function alterarStatus(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id               = filter_var($_POST['id']     ?? 0, FILTER_VALIDATE_INT);
        $status           = $_POST['status']            ?? '';
        $observacao_final = trim($_POST['observacao_final'] ?? '');

        if (!$id || $status === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'ID e status são obrigatórios.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        if (!in_array($status, ['aberto', 'em_andamento', 'concluido'], true)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Status inválido.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        if ($status === 'concluido' && $observacao_final === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'Observação final é obrigatória para concluir.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $stmt = $this->pdo->prepare(
                'UPDATE atendimentos SET status = :status, observacao_final = :obs WHERE id = :id'
            );
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':obs',    $observacao_final);
            $stmt->bindValue(':id',     $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Status atualizado com sucesso.'], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar status.'], JSON_UNESCAPED_UNICODE);
        }
    }
}