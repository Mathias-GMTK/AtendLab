<?php

class DashboardController
{
    private PDO $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function resumo(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $totalPessoas = $this->pdo
            ->query('SELECT COUNT(*) FROM pessoas')
            ->fetchColumn();

        $totalTipos = $this->pdo
            ->query('SELECT COUNT(*) FROM tipos_atendimentos')
            ->fetchColumn();

        $totalAtendimentos = $this->pdo
            ->query('SELECT COUNT(*) FROM atendimentos')
            ->fetchColumn();

        $recentes = $this->pdo->query(
            'SELECT a.id,
                    p.nome AS pessoa,
                    t.nome AS tipo,
                    u.nome AS responsavel,
                    a.status,
                    a.data_atendimento
             FROM atendimentos a
             JOIN pessoas            p ON p.id = a.pessoa_id
             JOIN tipos_atendimentos t ON t.id = a.tipo_atendimento_id
             JOIN usuarios           u ON u.id = a.usuario_id
             ORDER BY a.criado_em DESC
             LIMIT 5'
        )->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'indicadores' => [
                'total_pessoas'      => (int) $totalPessoas,
                'total_tipos'        => (int) $totalTipos,
                'total_atendimentos' => (int) $totalAtendimentos,
            ],
            'atendimentos_recentes' => $recentes,
        ], JSON_UNESCAPED_UNICODE);
    }
}