-- ============================================
-- Banco de dados: AtendLab - Univille 2026.1
-- ============================================

CREATE DATABASE IF NOT EXISTS atendelab
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE atendelab;

-- Tabela de usuários do sistema
CREATE TABLE IF NOT EXISTS usuarios (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nome         VARCHAR(100)  NOT NULL,
    email        VARCHAR(100)  NOT NULL UNIQUE,
    senha        VARCHAR(255)  NOT NULL,
    perfil       ENUM('admin','atendente') DEFAULT 'atendente',
    status       ENUM('ativo','inativo')   DEFAULT 'ativo',
    criado_em    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de pessoas atendidas
CREATE TABLE IF NOT EXISTS pessoa (
    id_pessoa  INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(100) NOT NULL,
    documento  VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    telefone   VARCHAR(100) NOT NULL,
    curso      VARCHAR(100) NOT NULL,
    periodo    VARCHAR(100) NOT NULL,
    status     VARCHAR(100) DEFAULT 'ativo'
);

-- Usuário admin padrão (senha: admin123)
INSERT INTO usuarios (nome, email, senha, perfil, status) VALUES
('Administrador', 'admin@atendelab.com', MD5('admin123'), 'admin', 'ativo');
