-- ==============================
-- TABLAS DE USUARIOS Y ROLES
-- ==============================

CREATE TABLE Rol (
    id_rol INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre_rol TEXT NOT NULL UNIQUE
);

CREATE TABLE Usuario (
    id_usuario INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    apellido TEXT NOT NULL,
    dni TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    id_rol INTEGER NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES Rol(id_rol)
);

-- ==============================
-- TABLAS DE PRODUCTOS
-- ==============================

CREATE TABLE TipoProducto (
    id_tipo INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre_tipo TEXT NOT NULL UNIQUE
);

CREATE TABLE Producto (
    id_producto INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    descripcion TEXT,
    precio REAL NOT NULL,
    id_tipo INTEGER NOT NULL,
    FOREIGN KEY (id_tipo) REFERENCES TipoProducto(id_tipo)
);

-- ==============================
-- TABLAS DE PEDIDOS
-- ==============================
CREATE TABLE Estado (
    id_estado INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre_estado TEXT NOT NULL UNIQUE
);
CREATE TABLE Pedido (
    id_pedido INTEGER PRIMARY KEY AUTOINCREMENT,
    monto_total REAL NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_cliente INTEGER NOT NULL,
    id_estado INTEGER NOT NULL DEFAULT 1,
    FOREIGN KEY (id_estado) REFERENCES Estado(id_estado),
    FOREIGN KEY (id_cliente) REFERENCES Usuario(id_usuario)
);

CREATE TABLE Pedido_Producto (
    id_pedido INTEGER,
    id_producto INTEGER,
    cantidad INTEGER NOT NULL,
    subtotal REAL NOT NULL,
    PRIMARY KEY (id_pedido, id_producto),
    FOREIGN KEY (id_pedido) REFERENCES Pedido(id_pedido),
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto)
);
