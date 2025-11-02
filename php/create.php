<?php
require_once "../includes/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'titulo' => $_POST['titulo'],
        'autor' => $_POST['autor'],
        'isbn' => $_POST['isbn'] ?? '',
        'genero' => $_POST['genero'] ?? '',
        'anio' => isset($_POST['anio']) ? (int)$_POST['anio'] : null,
        'editorial' => $_POST['editorial'] ?? '',
        'paginas' => isset($_POST['paginas']) ? (int)$_POST['paginas'] : null
    ];

    $librosCollection->insertOne($data);
}

header("Location: ../index.php");
exit;
?>
