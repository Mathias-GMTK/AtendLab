

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

CREATE TABLE IF NOT EXISTS pessoas (
    id_pessoa  INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(100) NOT NULL,
    documento  VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    telefone   VARCHAR(100) NOT NULL,
    curso      VARCHAR(100) NOT NULL,
    periodo    VARCHAR(100) NOT NULL,
    status     VARCHAR(100) DEFAULT 'ativo'
);

CREATE TABLE tipo_atendimentos(
    id_tipo_atendimento INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nome VARCHAR (100),
    descricao text,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo'
);

CREATE TABLE atendimento(
    id_atendimento INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_tipo_atendimento INT NOT NULL,
    id_pessoa INT NOT NULL,
    id_dashboard INT NOT NULL,
    id_relatorio_atendimento INT NOT NULL
    id_usuario INT NOT NULL,
    FOREING KEY (id_tipo_atendimento) references tipo_atendimentos(id_tipo_atendimento),
    FOREING KEY (id_pessoa) references pessoas(id_pessoa),
    FOREING KEY(id_dashboard) references dashboard(id_dashboard),
    FOREING KEY(id_relatorio_atendimento) references relatorio_atendimento(id_relatorio_atendimento),
    FOREING KEY(id_usuario) references usuarios(id_usuario)
    data_atendimento date,
    hora TIMESTAMP,
    descricao text,
    observacao_final text,
    status ENUM ('aberto', 'em atendimento', 'concluido', 'cancelado') DEFAULT 'aberto'
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE relatorio_atendimento(
    periodo_inicial date,
    id_relatorio_atendimento INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    periodo_final date,
    status ENUM ('ativo', 'inativ') DEFAULT ativo
);


CREATE TABLE dashboard(
    id_dashboard INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    total_atendimento INT NOT NULL,
    abertos INT NOT NULL,
    concluidos INT NOT NULL,
    atendimento_hoje INT NOT NULL
);





INSERT INTO usuarios (nome, email, senha, perfil, status) VALUES
('Administrador', 'admin@atendelab.com', MD5('admin123'), 'admin', 'ativo');
