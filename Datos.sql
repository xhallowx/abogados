-- Crear la base de datos
CREATE DATABASE GabineteAbogados;

-- Usar la base de datos
USE GabineteAbogados;

-- Crear tabla Roles
CREATE TABLE Roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    rol_nombre VARCHAR(50) NOT NULL
);

-- Insertar los roles predeterminados
INSERT INTO Roles (rol_nombre) VALUES ('admin'), ('usuario'), ('procurador');

-- Crear tabla Usuarios
CREATE TABLE Usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT,
    FOREIGN KEY (rol_id) REFERENCES Roles(id)
);

-- Insertar un usuario administrador predeterminado
INSERT INTO Usuarios (username, password, rol_id) 
VALUES ('admin', MD5('admin123'), 1);  -- La contraseña es 'admin123'

-- Crear tabla Cliente
CREATE TABLE Cliente (
    DNI VARCHAR(20) PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Direccion VARCHAR(255) NOT NULL
);

-- Crear tabla Asunto
CREATE TABLE Asunto (
    Numero_expediente INT PRIMARY KEY AUTO_INCREMENT,
    Fecha_inicio DATE NOT NULL,
    Fecha_finalizacion DATE,
    Estado VARCHAR(50) NOT NULL,
    Descripcion TEXT, -- Nuevo campo para la descripción del asunto
    Cliente_DNI VARCHAR(20),
    FOREIGN KEY (Cliente_DNI) REFERENCES Cliente(DNI) ON DELETE CASCADE
);

-- Crear tabla Procurador
CREATE TABLE Procurador (
    ID_procurador INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100) NOT NULL,
    Direccion VARCHAR(255) NOT NULL
);

-- Crear tabla intermedia Asunto_Procurador (Relaciona Asuntos y Procuradores)
CREATE TABLE Asunto_Procurador (
    Numero_expediente INT,
    ID_procurador INT,
    PRIMARY KEY (Numero_expediente, ID_procurador),
    FOREIGN KEY (Numero_expediente) REFERENCES Asunto(Numero_expediente) ON DELETE CASCADE,
    FOREIGN KEY (ID_procurador) REFERENCES Procurador(ID_procurador) ON DELETE CASCADE
);
