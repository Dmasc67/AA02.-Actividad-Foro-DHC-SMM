CREATE DATABASE db_foro;
USE db_foro;

CREATE TABLE usuarios (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE preguntas (
    id_pregunta INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    titulo_pregunta VARCHAR(255) NOT NULL,
    contenido_pregunta VARCHAR(500) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES usuarios(id_user) ON DELETE CASCADE
);

CREATE TABLE respuestas (
    id_respuesta INT AUTO_INCREMENT PRIMARY KEY,
    id_pregunta INT NOT NULL,
    id_user INT NOT NULL,
    contenido_respuesta VARCHAR(500) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pregunta) REFERENCES preguntas(id_pregunta) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES usuarios(id_user) ON DELETE CASCADE
);

CREATE TABLE amistades (
    id_amistad INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_amigo INT NOT NULL,
    estado_peticion ENUM('pendiente', 'aceptada', 'rechazada') DEFAULT 'pendiente' COLLATE utf8mb4_general_ci NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES usuarios(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_amigo) REFERENCES usuarios(id_user) ON DELETE CASCADE,
    UNIQUE (id_user, id_amigo)
);

CREATE TABLE mensajes (
    id_message INT AUTO_INCREMENT PRIMARY KEY,
    id_enviar INT NOT NULL,
    id_recibir INT NOT NULL,
    contenido_mensaje varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_enviar) REFERENCES usuarios(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_recibir) REFERENCES usuarios(id_user) ON DELETE CASCADE
);