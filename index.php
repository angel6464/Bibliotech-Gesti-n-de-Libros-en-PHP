<?php
require_once 'clases/Libro.php';
require_once 'clases/Biblioteca.php';

$biblioteca = new Biblioteca();

// Libros iniciales
$biblioteca->agregarLibro(new Libro(1, "1984", "George Orwell", "Ficción"));
$biblioteca->agregarLibro(new Libro(2, "El Principito", "Antoine de Saint-Exupéry", "Ficción"));
$biblioteca->agregarLibro(new Libro(3, "Sapiens", "Yuval Noah Harari", "Historia"));

$libros = $biblioteca->obtenerLibros();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    if ($accion === 'buscar') {
        $criterio = $_POST['criterio'];
        $valor = $_POST['valor'];
        $libros = $biblioteca->buscarLibros($criterio, $valor);
    } elseif ($accion === 'agregar') {
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $categoria = $_POST['categoria'];
        $nuevoId = count($biblioteca->obtenerLibros()) + 1;
        $biblioteca->agregarLibro(new Libro($nuevoId, $titulo, $autor, $categoria));
        $libros = $biblioteca->obtenerLibros();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Biblioteca</title>
</head>
<body>
    <h1>Gestión de Biblioteca</h1>

    <form method="POST">
        <h2>Buscar Libros</h2>
        <select name="criterio">
            <option value="titulo">Título</option>
            <option value="autor">Autor</option>
            <option value="categoria">Categoría</option>
        </select>
        <input type="text" name="valor" placeholder="Buscar...">
        <button type="submit" name="accion" value="buscar">Buscar</button>
    </form>

    <form method="POST">
        <h2>Agregar Libro</h2>
        <input type="text" name="titulo" placeholder="Título" required>
        <input type="text" name="autor" placeholder="Autor" required>
        <input type="text" name="categoria" placeholder="Categoría" required>
        <button type="submit" name="accion" value="agregar">Agregar</button>
    </form>

    <h2>Listado de Libros</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Categoría</th>
            <th>Disponibilidad</th>
        </tr>
        <?php foreach ($libros as $libro): ?>
            <tr>
                <td><?= $libro->getId() ?></td>
                <td><?= $libro->getTitulo() ?></td>
                <td><?= $libro->getAutor() ?></td>
                <td><?= $libro->getCategoria() ?></td>
                <td><?= $libro->isDisponible() ? "Sí" : "No" ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
