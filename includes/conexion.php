<?php
$host = 'localhost';
$puerto = '3307'; // Mi puerto 3307 en caso el tuyo no  cambia esto a 3306
$basedatos = 'animal_life';
$usuario = 'root';
$clave = '';

$dsn = "mysql:host=$host;port=$puerto;dbname=$basedatos;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $usuario, $clave, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}