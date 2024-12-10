<?php
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

    public function obtenerLibros() {
        return $this->libros;
    }
}
