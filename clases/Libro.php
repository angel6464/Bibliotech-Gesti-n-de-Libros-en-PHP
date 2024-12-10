<?php //Libro.php
class Libro {
    private $id;
    private $titulo;
    private $autor;
    private $categoria;
    private $disponible;
    private $usuarioPrestamo;

    public function __construct($id, $titulo, $autor, $categoria, $disponible = true) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->categoria = $categoria;
        $this->disponible = $disponible;
        $this->usuarioPrestamo = null;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getAutor() {
        return $this->autor;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function isDisponible() {
        return $this->disponible;
    }

    public function getUsuarioPrestamo() {
        return $this->usuarioPrestamo;
    }

    public function prestar($usuario) {
        $this->disponible = false;
        $this->usuarioPrestamo = $usuario;
    }

    public function devolver() {
        $this->disponible = true;
        $this->usuarioPrestamo = null;
    }

    public function editar($titulo, $autor, $categoria) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->categoria = $categoria;
    }
}
