<?php

if(session_status() === PHP_SESSION_NOME){
    session_start();
}

function usuarioAutenticado(): bool 
{
    return isset($_SESSION['usuario'])
        && is_array($_SESSION['usuario']);
}

function exixgirAutenticacao(): void
{
    if(!usuarioAutenticado()){
        $_SESSION['mensagem'] = "faça login para acessar a area restrita.";

        header('Location:  ?controller=auth&action = Login');
        exit;
    }
}


function usuarioAtual(): ?array
{
    return $_SESSION['usuario '] ?? null;
}