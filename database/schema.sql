-- =============================================
-- Sistema de Reserva de Salas - Script do banco
-- Aula 1 - Criar banco e tabelas
-- =============================================

CREATE DATABASE IF NOT EXISTS reserva_salas
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE reserva_salas;

-- ---------------------------------------------
-- Tabela: usuarios (para autenticação - Aula 3)
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------
-- Tabela: salas
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS salas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(80) NOT NULL,
  capacidade INT UNSIGNED NOT NULL DEFAULT 1,
  andar VARCHAR(50) NOT NULL,
  recursos VARCHAR(255) DEFAULT NULL,
  status ENUM('disponivel', 'em_uso', 'manutencao') DEFAULT 'disponivel',
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------
-- Tabela: reservas
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS reservas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNSIGNED NOT NULL,
  sala_id INT UNSIGNED NOT NULL,
  data_reserva DATE NOT NULL,
  hora_inicio TIME NOT NULL,
  hora_fim TIME NOT NULL,
  observacoes TEXT DEFAULT NULL,
  status ENUM('pendente', 'confirmada', 'cancelada') DEFAULT 'pendente',
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (sala_id) REFERENCES salas(id) ON DELETE CASCADE,
  INDEX idx_reserva_data (data_reserva),
  INDEX idx_reserva_sala (sala_id)
) ENGINE=InnoDB;

-- ---------------------------------------------
-- Dados iniciais: salas (exemplo)
-- ---------------------------------------------
INSERT INTO salas (nome, capacidade, andar, recursos, status) VALUES
('Sala 101', 10, '1º andar', 'Projetor, Quadro branco', 'disponivel'),
('Sala 102', 8, '1º andar', 'TV, Videoconferência', 'disponivel'),
('Sala 201', 20, '2º andar', 'Projetor, Quadro, Ar condicionado', 'disponivel'),
('Sala 202', 12, '2º andar', 'Quadro branco', 'disponivel'),
('Sala 203', 6, '2º andar', 'Videoconferência', 'disponivel'),
('Auditório A', 50, 'Térreo', 'Projetor, Som, Ar condicionado', 'disponivel');
