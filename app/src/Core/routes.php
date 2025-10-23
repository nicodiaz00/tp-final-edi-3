<?php

// Este archivo se convierte en el punto central para cargar todas las rutas.

// Cargamos las rutas de los diferentes módulos.
$userRoutes = require_once __DIR__ . '/../routes/user.routes.php';
// $productRoutes = require_once __DIR__ . '/../routes/product.routes.php'; // Ejemplo para el futuro

// Fusionamos todas las rutas en un solo array.
// Usamos array_merge_recursive para combinar las claves 'GET', 'POST', etc.
return array_merge_recursive(
    $userRoutes
    // , $productRoutes // Ejemplo para el futuro
);