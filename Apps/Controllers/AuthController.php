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
}