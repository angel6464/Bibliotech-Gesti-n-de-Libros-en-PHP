

<?php //Biblioteca.php
class Biblioteca {
    private $libros = [];

    public function agregarLibro($libro) {
        $this->libros[] = $libro;
    }

    public function buscarLibros($criterio, $valor) {
        return array_filter($this->libros, function($libro) use ($criterio, $valor) {
            if ($criterio === "titulo") {
                return stripos($libro->getTitulo(), $valor) !== false;
            } elseif ($criterio === "autor") {
                return stripos($libro->getAutor(), $valor) !== false;
            } elseif ($criterio === "categoria") {
                return stripos($libro->getCategoria(), $valor) !== false;
            }
            return false;
        });
    }

    public function eliminarLibro($id) {
        $this->libros = array_filter($this->libros, function($libro) use ($id) {
            return $libro->getId() !== $id;
        });
    }

    public function prestarLibro($id, $usuario) {
        foreach ($this->libros as $libro) {
            if ($libro->getId() === $id && $libro->isDisponible()) {
                $libro->prestar($usuario);
                return true;
            }
        }
        return false; // No se puede prestar (no disponible o no encontrado)
    }

    public function devolverLibro($id) {
        foreach ($this->libros as $libro) {
            if ($libro->getId() === $id && !$libro->isDisponible()) {
                $libro->devolver();
                return true;
            }
        }
        return false; // No se puede devolver (ya disponible o no encontrado)
    }

    public function obtenerLibros() {
        return $this->libros;
    }
}
