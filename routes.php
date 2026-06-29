<?php

require_once __DIR__ . '/Apps/Controllers/AuthController.php';
require_once __DIR__ . '/Apps/Controllers/FrontendController.php';
require_once __DIR__ . '/Apps/Controllers/DashboardController.php';
require_once __DIR__ . '/Apps/Controllers/UsuarioController.php';
require_once __DIR__ . '/Apps/Controllers/PessoasController.php';
require_once __DIR__ . '/Apps/Controllers/TiposAtendimentosController.php';
require_once __DIR__ . '/Apps/Controllers/AtendimentosController.php';
require_once __DIR__ . '/Apps/Middleware/Auth.php';

$controller = $_GET['controller'] ?? 'auth';
$action     = $_GET['action']     ?? 'login';

switch ($controller) {

    case 'auth':
        $authController = new AuthController();
        switch ($action) {
            case 'login':     $authController->exibirLogin(); break;
            case 'entrar':    $authController->entrar();      break;
            case 'dashboard': $authController->dashboard();   break;
            case 'logout':    $authController->logout();      break;
            default:
                http_response_code(404);
                echo 'Ação de autenticação não encontrada.';
        }
        break;

    case 'frontend':
        exigirAutenticacao();
        $frontendController = new FrontendController();
        switch ($action) {
            case 'pessoas':       $frontendController->pessoas();       break;
            case 'tipos':         $frontendController->tipos();         break;
            case 'atendimentos':  $frontendController->atendimentos();  break;
            default:
                http_response_code(404);
                echo 'Página não encontrada.';
        }
        break;

    case 'dashboard':
        exigirAutenticacao();
        $dashboardController = new DashboardController();
        switch ($action) {
            case 'resumo': $dashboardController->resumo(); break;
            default:
                http_response_code(404);
                echo 'Ação de dashboard não encontrada.';
        }
        break;

    case 'usuarios':
        exigirAutenticacao();
        $usuariosController = new UsuarioController();
        switch ($action) {
            case 'listar':      $usuariosController->listar();       break;
            case 'buscarPorId': $usuariosController->buscarPorId();  break;
            case 'criar':       $usuariosController->criar();        break;
            case 'atualizar':   $usuariosController->atualizar();    break;
            case 'deletar':     $usuariosController->deletar();      break;
            default:
                http_response_code(404);
                echo 'Ação de usuários não encontrada.';
        }
        break;

    case 'pessoas':
        exigirAutenticacao();
        $pessoasController = new PessoasController();
        switch ($action) {
            case 'listar':    $pessoasController->listar();    break;
            case 'buscar':
            case 'buscarPorId': $pessoasController->buscar(); break;
            case 'criar':     $pessoasController->criar();     break;
            case 'atualizar': $pessoasController->atualizar(); break;
            case 'inativar':  $pessoasController->inativar();  break;
            default:
                http_response_code(404);
                echo 'Ação de pessoas não encontrada.';
        }
        break;

    case 'tipos':
        exigirAutenticacao();
        $tiposController = new TiposAtendimentosController();
        switch ($action) {
            case 'listar':    $tiposController->listar();    break;
            case 'buscar':
            case 'buscarPorId': $tiposController->buscar(); break;
            case 'criar':     $tiposController->criar();     break;
            case 'atualizar': $tiposController->atualizar(); break;
            case 'inativar':  $tiposController->inativar();  break;
            default:
                http_response_code(404);
                echo 'Ação de tipos não encontrada.';
        }
        break;

    case 'atendimentos':
        exigirAutenticacao();
        $atendimentosController = new AtendimentosController();
        switch ($action) {
            case 'listar':        $atendimentosController->listar();        break;
            case 'buscar':        $atendimentosController->buscar();        break;
            case 'criar':         $atendimentosController->criar();         break;
            case 'alterarStatus': $atendimentosController->alterarStatus(); break;
            default:
                http_response_code(404);
                echo 'Ação de atendimentos não encontrada.';
        }
        break;

    default:
        http_response_code(404);
        echo 'Controller não encontrado.';
}