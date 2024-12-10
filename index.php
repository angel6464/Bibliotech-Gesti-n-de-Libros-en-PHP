<?php
session_start();
require_once 'clases/Libro.php';
require_once 'clases/Biblioteca.php';

// Inicializar la biblioteca en la sesión
if (!isset($_SESSION['biblioteca'])) {
    $_SESSION['biblioteca'] = serialize(new Biblioteca());
}
$biblioteca = unserialize($_SESSION['biblioteca']);

// Agregar libros iniciales solo la primera vez
if (empty($biblioteca->obtenerLibros())) {
    $biblioteca->agregarLibro(new Libro(1, "1984", "George Orwell", "Ficción"));
    $biblioteca->agregarLibro(new Libro(2, "El Principito", "Antoine de Saint-Exupéry", "Ficción"));
    $biblioteca->agregarLibro(new Libro(3, "Sapiens", "Yuval Noah Harari", "Historia"));
}

// Procesar acciones desde los botones de la tabla
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    
    if ($accion === 'agregar') {
        // Agregar libro
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $categoria = $_POST['categoria'];
        $id = count($biblioteca->obtenerLibros()) + 1; // Generar ID secuencial
        $biblioteca->agregarLibro(new Libro($id, $titulo, $autor, $categoria));
    } elseif ($accion === 'prestar' && isset($_POST['id']) && isset($_POST['usuario'])) {
        // Prestar libro
        $id = (int)$_POST['id'];
        $usuario = $_POST['usuario'];
        $biblioteca->prestarLibro($id, $usuario);
    } elseif ($accion === 'devolver' && isset($_POST['id'])) {
        // Devolver libro
        $id = (int)$_POST['id'];
        $biblioteca->devolverLibro($id);
    }

    // Guardar los cambios en la sesión
    $_SESSION['biblioteca'] = serialize($biblioteca);
}

// Obtener todos los libros para mostrar en la tabla
$libros = $biblioteca->obtenerLibros();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Biblioteca</title>
    
    <!-- incluimos SweetAlert2 desde un CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Estilos -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Biblioteca</h1>

        <!-- Agregar Libro -->
        <form method="POST">
            <h2>Agregar Libro</h2>
            <input type="text" name="titulo" placeholder="Título" required>
            <input type="text" name="autor" placeholder="Autor" required>
            <input type="text" name="categoria" placeholder="Categoría" required>
            <button type="submit" name="accion" value="agregar">Agregar</button>
        </form>

        <!-- Listado de Libros -->
        <h2>Listado de Libros</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Categoría</th>
                <th>Disponibilidad</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($libros as $libro): ?>
                <tr>
                    <td><?= $libro->getId() ?></td>
                    <td><?= $libro->getTitulo() ?></td>
                    <td><?= $libro->getAutor() ?></td>
                    <td><?= $libro->getCategoria() ?></td>
                    <td><?= $libro->isDisponible() ? "Disponible" : "Prestado a " . $libro->getUsuarioPrestamo() ?></td>
                    <td>
                        <?php if ($libro->isDisponible()): ?>
                            <button class="prestarBtn" data-id="<?= $libro->getId() ?>">Prestar</button>
                        <?php else: ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $libro->getId() ?>">
                                <button type="submit" name="accion" value="devolver">Devolver</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
        // Obtenemos todos los botones de "Prestar"
        var prestarBtns = document.getElementsByClassName("prestarBtn");

        // Agregamos un listener para cada botón de "Prestar"
        for (var i = 0; i < prestarBtns.length; i++) {
            prestarBtns[i].onclick = function() {
                // Obtener el ID del libro al que se hace clic
                var libroId = this.getAttribute("data-id");
                
                // Usar SweetAlert2 para pedir el nombre del usuario
                Swal.fire({
                    title: 'Ingrese el nombre del usuario',
                    input: 'text',
                    inputPlaceholder: 'Nombre del usuario',
                    showCancelButton: true,
                    confirmButtonText: 'Prestar',
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        if (!value || value.trim() === '') {
                            return '¡Debe ingresar un nombre!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        var usuario = result.value;

                        // Crear el formulario y enviarlo de manera programática
                        var form = document.createElement("form");
                        form.method = "POST";
                        
                        var accionInput = document.createElement("input");
                        accionInput.type = "hidden";
                        accionInput.name = "accion";
                        accionInput.value = "prestar";
                        form.appendChild(accionInput);
                        
                        var idInput = document.createElement("input");
                        idInput.type = "hidden";
                        idInput.name = "id";
                        idInput.value = libroId;
                        form.appendChild(idInput);
                        
                        var usuarioInput = document.createElement("input");
                        usuarioInput.type = "hidden";
                        usuarioInput.name = "usuario";
                        usuarioInput.value = usuario;
                        form.appendChild(usuarioInput);
                        
                        // Agregamos el formulario al cuerpo y enviarlo
                        document.body.appendChild(form);
                        form.submit();
                    } else {
                        // Si el usuario cancela o no ingresa un nombre
                        Swal.fire('Operación cancelada', '', 'info');
                    }
                });
            };
        }
    </script>
</body>
</html>
