<?php
/**
 * includes/header.php
 * Encabezado modularizado con metadatos y título dinámico SSR
 * Estudiante: Frias Abigail
 */
require_once __DIR__ . '/../config/env.php';

$page_title_render = isset($titulo_pagina) 
    ? htmlspecialchars($titulo_pagina) . " | " . htmlspecialchars(APP_NAME) 
    : htmlspecialchars(APP_NAME);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title_render; ?></title>
    <meta name="description" content="Sitio Oficial de TINI Stoessel - Fan Club Oficial, Tour Dates 2026, Discografía y Tienda de Merchandising.">
    
    <!-- Hojas de Estilo CSS -->
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Soporte FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php require_once __DIR__ . '/nav.php'; ?>
