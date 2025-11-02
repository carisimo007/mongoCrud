<?php
require_once "includes/conexion.php";

// Obtener búsqueda si existe
$search = $_GET['search'] ?? '';
$query = [];
if ($search) {
    $query = ['$or' => [
        ['titulo' => new MongoDB\BSON\Regex($search, 'i')],
        ['autor' => new MongoDB\BSON\Regex($search, 'i')]
    ]];
}

// Convertir cursor a array para evitar errores de rewind
$librosCursor = $librosCollection->find($query);
$libros = iterator_to_array($librosCursor);

// Limpiar buscador si venimos de update o delete
if (isset($_GET['action']) && ($_GET['action'] === 'updated' || $_GET['action'] === 'deleted')) {
    $search = '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>CRUD Libros MongoDB</title>
<link rel="icon" type="image/png" href="imagenes/book.png">
<link rel="stylesheet" href="css/estilos.css">
<script src="js/script.js"></script>

</head>
<body>
<div class="container">
    <h1>Biblioteca Virtual 👻</h1>

    <h2>Agregar nuevo libro</h2>
    <form action="php/create.php" method="POST" onsubmit="return confirmarAgregar();">
        <label>Título:</label>
        <input type="text" name="titulo" required>

        <label>Autor:</label>
        <input type="text" name="autor" required>

        <label>ISBN:</label>
        <input type="text" name="isbn">

        <label>Género:</label>
        <input type="text" name="genero">

        <label>Año:</label>
        <div class="select-wrapper">
            <select name="anio" required>
                <option value="">Seleccione año</option>
                <?php
                $currentYear = date('Y');
                for ($year = $currentYear; $year >= 1500; $year--) {
                    echo "<option value='$year'>$year</option>";
                }
                ?>
            </select>
        </div>

        </select>

        <label>Editorial:</label>
        <input type="text" name="editorial">

        <label>Páginas:</label>
        <input type="number" name="paginas" min="1" max="5000" placeholder="Ej: 309">

        <button type="submit">Agregar</button>
    </form>

    <h2>Buscador</h2>
    <form method="GET">
        <input type="text" name="search" placeholder="Buscar título o autor" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Buscar</button>
    </form>

    <h2>Listado de libros</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>ISBN</th>
            <th>Género</th>
            <th>Año</th>
            <th>Editorial</th>
            <th>Páginas</th>
            <th>Acciones</th>
        </tr>

        <?php foreach($libros as $libro): ?>
        <tr>
            <td><?php echo $libro->_id->__toString(); ?></td>
            <td><?php echo htmlspecialchars($libro->titulo); ?></td>
            <td><?php echo htmlspecialchars($libro->autor); ?></td>
            <td><?php echo htmlspecialchars($libro->isbn ?? ''); ?></td>
            <td><?php echo htmlspecialchars($libro->genero ?? ''); ?></td>
            <td class="anio-cell"><?php echo htmlspecialchars($libro->anio ?? ''); ?></td>
            <td><?php echo htmlspecialchars($libro->editorial ?? ''); ?></td>
            <td><?php echo htmlspecialchars($libro->paginas ?? ''); ?></td>
            <td>
                <div class="actions">
                    <button class="edit" onclick="location.href='php/update.php?id=<?php echo $libro->_id->__toString(); ?>'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#005b96" viewBox="0 0 24 24">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm3.5 1.25H5v-1.5l9.06-9.06 1.5 1.5L6.5 18.5zm12.71-12.04c.39-.39.39-1.02 0-1.41l-2.34-2.34a.9959.9959 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                        </svg>
                    </button>

                    <button class="delete" onclick="confirmarEliminar('<?php echo $libro->_id->__toString(); ?>')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#d90429" viewBox="0 0 24 24">
                            <path d="M3 6h18v2H3V6zm2 3h14v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V9zm3 2v8h2v-8H8zm4 0v8h2v-8h-2z"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
function confirmarEliminar(id) {
    if(confirm('¿Desea eliminar este libro?')) {
        window.location.href = 'php/delete.php?id=' + id;
    }
}

// Confirmar antes de agregar un libro
function confirmarAgregar() {
    return confirm("¿Estás seguro que quieres agregar este libro?");
}
</script>
</body>
</html>
