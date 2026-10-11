CREATE DATABASE IF NOT EXISTS animal_life CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE animal_life;

CREATE TABLE roles (
  id_rol INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE usuarios (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(100) UNIQUE NOT NULL,
  contrasena VARCHAR(255) NOT NULL,
  id_rol INT NOT NULL,
  estado TINYINT(1) DEFAULT 1,
  FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
);

CREATE TABLE propietarios (
  id_propietario INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  dni VARCHAR(15) UNIQUE NOT NULL,
  telefono VARCHAR(20),
  correo VARCHAR(100),
  direccion VARCHAR(150)
);

CREATE TABLE mascotas (
  id_mascota INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL,
  especie VARCHAR(50),
  raza VARCHAR(50),
  edad INT,
  id_propietario INT NOT NULL,
  FOREIGN KEY (id_propietario) REFERENCES propietarios(id_propietario) ON DELETE CASCADE
);

CREATE TABLE solicitudes (
  id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
  id_mascota INT NOT NULL,
  motivo VARCHAR(255),
  estado ENUM('Pendiente','Aceptada','Rechazada') DEFAULT 'Pendiente',
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_mascota) REFERENCES mascotas(id_mascota) ON DELETE CASCADE
);

CREATE TABLE citas (
  id_cita INT AUTO_INCREMENT PRIMARY KEY,
  id_mascota INT NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  estado ENUM('Programada','Confirmada','Atendida','Cancelada') DEFAULT 'Programada',
  FOREIGN KEY (id_mascota) REFERENCES mascotas(id_mascota) ON DELETE CASCADE
);

CREATE TABLE atenciones (
  id_atencion INT AUTO_INCREMENT PRIMARY KEY,
  id_mascota INT NOT NULL,
  id_usuario INT NOT NULL,
  diagnostico TEXT,
  tratamiento TEXT,
  observaciones TEXT,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_mascota) REFERENCES mascotas(id_mascota) ON DELETE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE pagos (
  id_pago INT AUTO_INCREMENT PRIMARY KEY,
  id_propietario INT NOT NULL,
  concepto VARCHAR(150),
  monto DECIMAL(10,2),
  metodo VARCHAR(30),
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_propietario) REFERENCES propietarios(id_propietario) ON DELETE CASCADE
);

-- Roles
INSERT INTO roles (nombre) VALUES ('Administrador'),('Recepcionista'),('Veterinario'),('Propietario');

-- IMPORTANTE: reemplaza estos hashes por los que generes con el script generar_hash.php
-- Todos los usuarios de prueba usan la clave "123456" en este ejemplo
INSERT INTO usuarios (nombre, correo, contrasena, id_rol) VALUES
('Administrador', 'admin@animallife.com', '$2y$10$REEMPLAZA_ESTE_HASH_1', 1),
('Ana García', 'recepcion@animallife.com', '$2y$10$REEMPLAZA_ESTE_HASH_2', 2),
('Dr. Carlos Torres', 'veterinario@animallife.com', '$2y$10$REEMPLAZA_ESTE_HASH_3', 3),
('María López', 'propietario@animallife.com', '$2y$10$REEMPLAZA_ESTE_HASH_4', 4);

-- Datos de prueba
INSERT INTO propietarios (nombre, dni, telefono, correo, direccion) VALUES
('María López', '12345678', '987 654 321', 'maria@email.com', 'Av. Lima 123'),
('Juan Pérez', '87654321', '965 123 456', 'juan@email.com', 'Calle Tacna 456');

INSERT INTO mascotas (nombre, especie, raza, edad, id_propietario) VALUES
('Max', 'Perro', 'Labrador', 3, 1),
('Luna', 'Gato', 'Siamesa', 2, 2);

INSERT INTO solicitudes (id_mascota, motivo, estado) VALUES
(1, 'Control general', 'Pendiente'),
(2, 'Vacunación', 'Aceptada');

INSERT INTO citas (id_mascota, fecha, hora, estado) VALUES
(1, '2026-10-10', '09:00:00', 'Confirmada'),
(2, '2026-10-10', '11:00:00', 'Pendiente');

INSERT INTO atenciones (id_mascota, id_usuario, diagnostico, tratamiento, observaciones) VALUES
(1, 3, 'Gastritis leve', 'Dieta y control', 'Reposo 3 días'),
(2, 3, 'Control general', 'Seguimiento', 'Sin novedad');

INSERT INTO pagos (id_propietario, concepto, monto, metodo) VALUES
(1, 'Consulta veterinaria', 80.00, 'Yape'),
(2, 'Vacunación', 60.00, 'Efectivo');