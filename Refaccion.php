<?php
class Refaccion {
    public $id_refaccion;
    public $codigo;
    public $descripcion;
    public $precio;
    public $stock;

    public function __construct($codigo, $descripcion, $precio, $stock = 0, $id_refaccion = null) {
        $this->id_refaccion = $id_refaccion;
        $this->codigo = $codigo;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->stock = $stock;
    }
}
?>