<?php
require_once "../includes/conexion.php";

$id = $_GET['id'] ?? '';
if ($id) {
    $objectId = new MongoDB\BSON\ObjectId($id);
    $librosCollection->deleteOne(['_id' => $objectId]);
}

header("Location: ../index.php");
exit;
?>
