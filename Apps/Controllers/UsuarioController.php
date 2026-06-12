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

public function Criar(): void
{
    header ('Content-type: aplication/json; charset=utf-8');


    $nome = trim($_POST['nome'?? '']);
    $email = trim($_POST['email' ?? '']);
    $senha = $_POST['senha'] ?? '';
    $perfil = $_POST['perfil'] ?? 'atendente';
    $status = $_POST['status'] ?? 'ativo';

    if ($nome === '' || $email === '' || $senha === ''){
        http_response_code(400);
        echo json_encode(['erro ' => 'Nome, e-mail são obrigatórios.']);
        return;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        http_response_code(400);
        echo json_encode(['erro' => 'E-mail inválido.']);
        return;
    }

    if(!in_array($perfil, ['admin', 'atendente', 'aluno'], true)){
        http_response_code(400);
        echo json_encode(['erro' => 'Perfil Inválido']);
        return;
    }

    if(!in_array($status, ['ativo', 'inativo'], true)){
        http_response_code(400);
        echo json_encode(['erro' => 'Status Inválido.']);
        return;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try{
        $sql = 'INSERT INTO  usuarios (nome, email, senha, perfil, status)
        VALUES (:nome, :email, :senha, :perfil, :status)';

        $stmt = $this->pdo->prepare($sql);
        $stmt = bindValue(':nome', $nome);
        $stmt = bindValue(':email', $email);
        $stmt = bindValue(':senha', $senhaHash);
        $stmt = bindValue(':perfil', $perfil);
        $stmt = bindValue(':status', $status);
        $stmt->execute();


        http_response_code(201);
        echo json_encode(['mensagem'=> 'Usuario cadastrado com Sucesso.'
        'id' =>  $this->pdo->lastInsertId()
        ], JSON_UNESCAPED_UNICODE);
    }catch(PDOException $e){
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao cadastrar usuario. ']);
    }

public function atualizar(): void
{
    header ('Content-Type: aplication/json; charset=utf-8');

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome'?? '']);
    $email = trim($_POST['email' ?? '']);
    $perfil = $_POST['perfil'] ?? 'atendente';
    $status = $_POST['status'] ?? 'ativo';

    if(!$id || $nome === '' || $email === ''){
        http_response_code(400);
        echo json_encode(['erro' => 'ID, nome e e-mail são obrigatórios.']);
        return;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        http_response_code(400);
        echo json_encode(['erro' => 'E-mail inválido.']);
        return;
    }

    if(!in_array($perfil, ['admin', 'atendente', 'aluno'], true)){
        http_response_code(400);
        echo json_encode(['erro' => 'Perfil Inválido']);
        return;
    }

    if(!in_array($status, ['ativo', 'inativo'], true)){
        http_response_code(400);
        echo json_encode(['erro' => 'Status Inválido.']);
        return;
    }

    try{
        $sql = 'UPDATE usuarios
                SET nome = :nome, email = :email, perfil = :perfil, status = :status
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':perfil', $perfil);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['mensagem' => 'Usuario atualizado com Sucesso.'], JSON_UNESCAPED_UNICODE);
    }catch(PDOException $e){
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao atualizar usuario.']);
    }

public function deletar(): void
{
    header ('Content-Type: aplication/json; charset=utf-8');

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if(!$id){
        http_response_code(400);
        echo json_encode(['erro' => 'ID é obrigatório.']);
        return;
    }

    try{
        $sql = 'DELETE FROM usuarios WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['mensagem' => 'Usuario deletado com Sucesso.'], JSON_UNESCAPED_UNICODE);
    }catch(PDOException $e){
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao deletar usuario.']);
    }
}
}