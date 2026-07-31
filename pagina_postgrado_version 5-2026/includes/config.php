<?php

$host     = "localhost";
$port     = "5433";
$dbname   = "postgrado"; 
$user     = "postgres";
$password = "josej2531";

try {
    // El DSN 
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    
    // Creamos la conexión usando PDO
    $pdo = new PDO($dsn, $user, $password, [
        // Esta línea es VITAL: hace que PHP lance un error si el SQL falla
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Trae los datos como arreglos asociativos por defecto
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Desactiva emulaciones para mayor seguridad real
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    $GLOBALS['pdo'] = $pdo;

} catch (PDOException $e) {
    // Si la conexión falla, detenemos todo con un mensaje controlado
    // En un proyecto real, esto se guarda en un log de errores
    error_log("Fallo de conexión: " . $e->getMessage());
    die("Lo sentimos, el sistema de la universidad no está disponible en este momento.");
}