--INSERT ROL--
INSERT INTO Rol (nombre_rol) VALUES ('Admin');
INSERT INTO Rol (nombre_rol) VALUES ('Cliente');
--INSERT ESTADO--
INSERT INTO Estado (nombre_estado) VALUES ('Pendiente');
INSERT INTO Estado (nombre_estado) VALUES ('Entregado');
INSERT INTO Estado (nombre_estado) VALUES ('Cancelado');

INSERT INTO Usuario (nombre, apellido, dni, email, password, id_rol)
VALUES ('Juan', 'Pérez', '12345678', 'juan.perez@example.com', '1234', 1);

INSERT INTO Usuario (nombre, apellido, dni, email, password, id_rol)
VALUES ('María', 'Gómez', '87654321', 'maria.gomez@example.com', 'abcd', 2);

INSERT INTO TipoProducto (nombre_tipo) VALUES ('Bebida');
INSERT INTO TipoProducto (nombre_tipo) VALUES ('Comida');

INSERT INTO Producto (nombre, descripcion, precio, id_tipo)
VALUES ('Café Latte', 'Café con leche y espuma', 250, 1);

INSERT INTO Producto (nombre, descripcion, precio, id_tipo)
VALUES ('Sándwich de Jamón', 'Pan integral con jamón y queso', 350, 2);

INSERT INTO Pedido (id_cliente, fecha, monto_total, id_estado)
VALUES (1, '2025-09-23', 600, 1);

INSERT INTO Pedido (id_cliente, fecha, monto_total, id_estado)
VALUES (2, '2025-09-23', 250, 1);


INSERT INTO Pedido_Producto (id_pedido, id_producto, cantidad, subtotal)
VALUES (1, 1, 2, 2*250);
INSERT INTO Pedido_Producto (id_pedido, id_producto, cantidad, subtotal)
VALUES (1, 2, 1, 1*350);

INSERT INTO Pedido_Producto (id_pedido, id_producto, cantidad, subtotal)
VALUES (2, 1, 1, 1*250);


