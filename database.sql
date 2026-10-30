CREATE DATABASE IF NOT EXISTS catalogo_botanico
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE catalogo_botanico;

CREATE TABLE IF NOT EXISTS administradores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  senha_hash VARCHAR(255) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS plantas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome_popular VARCHAR(180) NOT NULL,
  nome_cientifico VARCHAR(220) NOT NULL,
  familia_botanica VARCHAR(180) DEFAULT '',
  genero VARCHAR(180) DEFAULT '',
  especie VARCHAR(180) DEFAULT '',
  habito VARCHAR(180) DEFAULT '',
  origem VARCHAR(220) DEFAULT '',
  recursos_oferecidos TEXT,
  abelhas_atraidas TEXT,
  produtos_para_abelhas TEXT,
  observacoes TEXT,
  imagem VARCHAR(255) DEFAULT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
