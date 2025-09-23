           ┌───────────────┐
           │     Rol       │
           │ id_rol (PK)   │
           │ nombre_rol    │
           └───────┬───────┘
                   │ 1:N
                   │
           ┌───────▼─────────┐
           │    Usuario      │
           │ id_usuario (PK) │
           │ nombre          │
           │ email           │
           │ password        │
           │ id_rol (FK)     │
           └───────┬─────────┘
                   │ 1:N (cliente hace pedidos)
                   │
           ┌───────▼─────────┐
           │     Pedido      │
           │ id_pedido (PK)  │
           │ monto_total     │
           │ fecha           │
           │ id_cliente (FK) │
           └───────┬─────────┘
                   │ N:M (con productos)
                   │
           ┌───────▼────────────┐
           │  Pedido_Producto   │
           │ id_pedido (FK)     │
           │ id_producto (FK)   │
           │ cantidad           │
           │ subtotal           │
           └───────┬────────────┘
                   │
                   │ N:1
           ┌───────▼─────────┐
           │    Producto     │
           │ id_producto(PK) │
           │ nombre          │
           │ descripcion     │
           │ precio          │
           │ id_tipo (FK)    │
           └───────┬─────────┘
                   │ 1:N
           ┌───────▼────────────┐
           │   TipoProducto     │
           │ id_tipo (PK)       │
           │ nombre_tipo        │
           └────────────────────┘

