CREATE DATABASE IF NOT EXISTS atendelab
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE atendelab;


CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario           INT AUTO_INCREMENT PRIMARY KEY,
    nome         VARCHAR(100)  NOT NULL,
    email        VARCHAR(100)  NOT NULL UNIQUE,
    senha        VARCHAR(255)  NOT NULL,
    perfil       ENUM('admin','atendente') DEFAULT 'atendente',
    status       ENUM('ativo','inativo')   DEFAULT 'ativo',
    criado_em    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tipo_atendimentos (
    id_tipo_atendimento INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nome                VARCHAR(100),
    descricao           TEXT,
    status              ENUM('ativo', 'inativo') DEFAULT 'ativo'
);

CREATE TABLE relatorio_atendimento (
    id_relatorio_atendimento INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    periodo_inicial          DATE,
    periodo_final            DATE,
    status                   ENUM('ativo', 'inativo') DEFAULT 'ativo'
);

CREATE TABLE dashboard (
    id_dashboard        INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    total_atendimento   INT NOT NULL,
    abertos             INT NOT NULL,
    concluidos          INT NOT NULL,
    atendimento_hoje    INT NOT NULL
);

CREATE TABLE atendimento (
    id_atendimento           INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_tipo_atendimento      INT NOT NULL,
    id_pessoa                INT NOT NULL,
    id_dashboard             INT NOT NULL,
    id_relatorio_atendimento INT NOT NULL,
    id_usuario               INT NOT NULL,
    data_atendimento         DATE,
    hora                     TIMESTAMP,
    descricao                TEXT,
    observacao_final         TEXT,
    status                   ENUM('aberto', 'em atendimento', 'concluido', 'cancelado') DEFAULT 'aberto',
    criado_em                TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tipo_atendimento)      REFERENCES tipo_atendimentos(id_tipo_atendimento),
    FOREIGN KEY (id_pessoa)                REFERENCES pessoas(id_pessoa),
    FOREIGN KEY (id_dashboard)             REFERENCES dashboard(id_dashboard),
    FOREIGN KEY (id_relatorio_atendimento) REFERENCES relatorio_atendimento(id_relatorio_atendimento),
    FOREIGN KEY (id_usuario)               REFERENCES usuarios(id_usuario)
);


INSERT INTO usuarios (nome, email, senha, perfil, status) VALUES
('Administrador', 'admin@atendelab.com', MD5('admin123'), 'admin', 'ativo');
