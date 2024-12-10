<?php
class Libro {
    private $id;
    private $titulo;
    private $autor;
    private $categoria;
    private $disponible;

    public function __construct($id, $titulo, $autor, $categoria, $disponible = true) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->categoria = $categoria;
        $this->disponible = $disponible;
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

    public function setDisponibilidad($estado) {
        $this->disponible = $estado;
    }

    public function editar($titulo, $autor, $categoria) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->categoria = $categoria;
    }
}
