<?php

require_once __DIR__ './../../config/database.php';

require_once __DIR__ './../Models/Auth.php';


class AuthController
{
    private PDO $pdo;

    public function __construct()
    {
        global $pdo;

        $this->pdo = $pdo;
    }

    public function exibirLogin(): void 
    {
         if(usuarioAutenticado()){
            header('Location: ?controller=auth&action=dashboard');
            exit;
         }

         $erro = $_SESSION['erro_login'] ?? null;
         $mensagem = $_SESSION['mensagem'] ?? null;

         unset{$_SESSION['erro_login'], $_SESSION['mensagem']};

         require_once __DIR__ './../Views/Auth/login.php';
    }

    public function entrar(): void
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Location: ?controller=auth&action=login');
            exit;
        }

        $email = $trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if($email === '' || $senha === ''){
            $_SESSION['erro_login'] = 'Informe o seu email e senha';

            header('Location: ?controller=auth&action=login');
            exit;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['erro_login'] = 'Informe um e-mail válido';

            header('Location: ?controller=auth&action=login');
            exit;
        }

        $sql = 'SELECT id, nome, email, senha, perfil, status
                FROM usuarios
                WHERE email = :email
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue('email', $email);

        $stmt->execute();
    }
}