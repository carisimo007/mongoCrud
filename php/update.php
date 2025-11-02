<?php
require_once "../includes/conexion.php";

$id = $_GET['id'] ?? '';
if (!$id) {
    header("Location: ../index.php");
    exit;
}

$objectId = new MongoDB\BSON\ObjectId($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateData = [
        'titulo' => $_POST['titulo'],
        'autor' => $_POST['autor'],
        'isbn' => $_POST['isbn'] ?? '',
        'genero' => $_POST['genero'] ?? '',
        'anio' => isset($_POST['anio']) ? (int)$_POST['anio'] : null,
        'editorial' => $_POST['editorial'] ?? '',
        'paginas' => isset($_POST['paginas']) ? (int)$_POST['paginas'] : null
    ];

    $librosCollection->updateOne(['_id' => $objectId], ['$set' => $updateData]);
    header("Location: ../index.php");
    exit;
}

// Mostrar formulario con datos actuales
$libro = $librosCollection->findOne(['_id' => $objectId]);
if (!$libro) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Libro</title>
<link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<div class="container">
<h1>✏️ Editar Libro</h1>

<form method="POST">
    <label>Título:</label>
    <input type="text" name="titulo" value="<?php echo htmlspecialchars($libro->titulo); ?>" required>

    <label>Autor:</label>
    <input type="text" name="autor" value="<?php echo htmlspecialchars($libro->autor); ?>" required>

    <label>ISBN:</label>
    <input type="text" name="isbn" value="<?php echo htmlspecialchars($libro->isbn ?? ''); ?>">

    <label>Género:</label>
    <input type="text" name="genero" value="<?php echo htmlspecialchars($libro->genero ?? ''); ?>">

    <label>Año:</label>
    <select name="anio" required>
        <?php
        $currentYear = date('Y');
        for ($year = $currentYear; $year >= 1500; $year--) {
            $selected = ($libro->anio == $year) ? 'selected' : '';
            echo "<option value='$year' $selected>$year</option>";
        }
        ?>
    </select>

    <label>Editorial:</label>
    <input type="text" name="editorial" value="<?php echo htmlspecialchars($libro->editorial ?? ''); ?>">

    <label>Páginas:</label>
    <input type="number" name="paginas" min="1" max="5000" value="<?php echo htmlspecialchars($libro->paginas ?? ''); ?>">

    <button type="submit">Actualizar</button>
</form>
</div>
</body>
</html>
