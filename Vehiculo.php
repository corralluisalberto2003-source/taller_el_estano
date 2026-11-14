<?php
class Vehiculo {
    public $id_vehiculo;
    public $id_cliente;
    public $placas;
    public $marca;
    public $modelo;
    public $anio;

    public function __construct($id_cliente, $placas, $marca, $modelo, $anio, $id_vehiculo = null) {
        $this->id_vehiculo = $id_vehiculo;
        $this->id_cliente = $id_cliente;
        $this->placas = $placas;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->anio = $anio;
    }
}
?>