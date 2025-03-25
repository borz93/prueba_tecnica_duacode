-- Crear la base de datos (si no existe)
CREATE DATABASE IF NOT EXISTS equipos_db;
USE equipos_db;

-- Tabla 'equipos' (Ejercicio 1)
CREATE TABLE IF NOT EXISTS equipos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    ciudad VARCHAR(50),
    deporte ENUM('Fútbol', 'Baloncesto', 'Tenis') NOT NULL, -- Campo desplegable
    fundacion DATE, -- Campo fecha
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Tabla 'jugadores' (Ejercicio 2)
CREATE TABLE IF NOT EXISTS jugadores (
     id INT PRIMARY KEY AUTO_INCREMENT,
     nombre VARCHAR(100) NOT NULL,
     numero INT CHECK (numero BETWEEN 1 AND 99),
     equipo_id INT NOT NULL,
     es_capitan BOOLEAN DEFAULT FALSE,
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE CASCADE
);

-- Usuario con permisos (No docker)
CREATE USER IF NOT EXISTS 'app_user'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON equipos_db.* TO 'app_user'@'%';
FLUSH PRIVILEGES;