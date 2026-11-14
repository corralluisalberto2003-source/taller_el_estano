<?php
class Cliente {
    public $id_cliente;
    public $nombre;
    public $telefono;
    public $correo;

    public function __construct($nombre, $telefono, $correo = null, $id_cliente = null) {
        $this->id_cliente = $id_cliente;
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->correo = $correo;
    }
}
?>