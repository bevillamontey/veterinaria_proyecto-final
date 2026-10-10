<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/conexion.php';

function q_all($pdo, $sql, $params = []) {
    $st = $pdo->prepare($sql); $st->execute($params); return $st->fetchAll();
}
function q_one($pdo, $sql, $params = []) {
    $st = $pdo->prepare($sql); $st->execute($params); return $st->fetch();
}
function q_exec($pdo, $sql, $params = []) {
    $st = $pdo->prepare($sql); $st->execute($params); return $st->rowCount();
}

function nombre_propietario($pdo, $id) {
    $r = q_one($pdo, "SELECT nombre FROM propietarios WHERE id_propietario=?", [$id]);
    return $r['nombre'] ?? '—';
}
function nombre_mascota($pdo, $id) {
    $r = q_one($pdo, "SELECT nombre FROM mascotas WHERE id_mascota=?", [$id]);
    return $r['nombre'] ?? '—';
}

function require_login() {
    if (empty($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }
}
function require_rol($roles) {
    require_login();
    if (!in_array($_SESSION['rol'], (array)$roles)) { header('Location: dashboard.php'); exit; }
}