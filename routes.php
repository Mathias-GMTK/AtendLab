<?php
require_once __DIR__ . '/Apps/Controllers/UsuarioController.php';
require_once __DIR__ . '/Apps/Controllers/AuthController.php';
require_once __DIR__ . '/Apps/Controllers/Auth.php';

$controller = $_GET['controller'] ?? 'auth';
$action     = $_GET['action']     ?? 'login';

switch ($controller ) {
    case 'auth':
        $authController = new UsuarioController();
    

        switch ($action) {
            case 'listar':
                $authController->exibirLogin();
                break;
            case 'buscar':
                $authController->entrar();
                break;
            case 'criar':
                $authController->dashboard();
                break;
            case 'atualizar':
                $authController->logout();
                break;
            default:
                http_response_code(404);
                echo 'Ação de autenticação não encontrada.';
            }
            break;

        case 'usuario':
            exigirUsuarioAutenticado();
            $usuarioController = new UsuarioController();

            switch ($action) {
            case 'listar':
                $usuarioController->listar();
                break;
            case 'buscar':
                $usuarioController->buscarPorId();
                break;
            case 'criar':
                $usuarioController->criar();
                break;
            case 'atualizar':
                $usuarioController->atualizar();
                break;
            case 'deletar':
                $usuarioController->deletar();
                break;
            default:
                http_response_code(404);
                echo 'Ação de usuarios não encontrada.';
            }
            break;

            default:
                http_response_code(404);
                echo 'Controller não encontrado.';
} 