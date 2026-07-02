DROP DATABASE IF EXISTS atendelab;
CREATE DATABASE atendelab CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE atendelab;

CREATE TABLE IF NOT EXISTS usuarios (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(100) NOT NULL,
    email         VARCHAR(100) NOT NULL UNIQUE,
    senha         VARCHAR(255) NOT NULL,
    perfil        ENUM('admin','atendente') DEFAULT 'atendente',
    status        ENUM('ativo','inativo') DEFAULT 'ativo',
    criado_em     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pessoas (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(150) NOT NULL,
    documento     VARCHAR(30) NOT NULL UNIQUE,
    telefone      VARCHAR(20),
    email         VARCHAR(150) NOT NULL,
    curso         VARCHAR(120),
    periodo       VARCHAR(20),
    observacoes   TEXT,
    status        ENUM('ativo','inativo') DEFAULT 'ativo',
    criado_em     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tipos_atendimentos (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(100) NOT NULL,
    descricao     TEXT,
    status        ENUM('ativo','inativo') DEFAULT 'ativo',
    criado_em     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS relatorio_atendimento (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    periodo_inicial DATE,
    periodo_final   DATE,
    status          ENUM('ativo','inativo') DEFAULT 'ativo',
    criado_em       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS atendimentos (
    id                    INT AUTO_INCREMENT PRIMARY KEY,
    pessoa_id             INT NOT NULL,
    tipo_atendimento_id   INT NOT NULL,
    usuario_id            INT NOT NULL,
    descricao             TEXT NOT NULL,
    status                ENUM('aberto','em_andamento','concluido') DEFAULT 'aberto',
    data_atendimento      DATE NOT NULL,
    horario_atendimento   TIME NOT NULL,
    observacao_final      TEXT,
    criado_em             TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em         TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pessoa_id)           REFERENCES pessoas(id),
    FOREIGN KEY (tipo_atendimento_id) REFERENCES tipos_atendimentos(id),
    FOREIGN KEY (usuario_id)          REFERENCES usuarios(id)
);

INSERT INTO usuarios (nome, email, senha, perfil, status) VALUES
('Administrador', 'admin@atendelab.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'ativo');