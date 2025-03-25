<?php
class Pagina {
    private $cabecera;
    private $cuerpo;
    private $pie;

    public function __construct($texto1, $texto2) {
        $this->cabecera = new Cabecera($texto1);
        $this->cuerpo = new Cuerpo;
        $this->pie = new Pie($texto2);
    }

    public function insertar_cuerpo($texto) {
        $this->cuerpo->insertar_parrafo($texto);
    }

    public function graficar() {
        $this->cabecera->graficar();
        $this->cuerpo->graficar();
        $this->pie->graficar();
    }

    /**
     * Implementar las clases Cabecera, Cuerpo y Pie
     * 
     * 1. La clase Cabecera tiene las siguiente caracteristicas
     *  >Tiene un constructor que recibbe un texto e inicializa un atributo de nombre titulo.
     *  >Tiene una funcion graficar, que utiliza un encabezado de nivel 1, a partir de un texto y un estilo por defecto.
     * 2. La clase Cuerpo tiene las siguinetes caracteristicas
     *      >No tiene constructor pero tiene un atributo privado que corresponde a un arreglo de lineas de texto, el atributo se debe llamar lineas.
     *      >Tiene una funcion graficar, que recorre el atributo lineas para mostrar elementos <p> que contiene el texto dentro del arreglo.
     * 1. La clase Pie tiene las siguiente caracteristicas
     *  >Tiene un constructor que recibbe un texto e inicializa un atributo de nombre titulo.
     *  >Tiene una funcion graficar, que utiliza un encabezado de nivel 1, a partir de un texto y un estilo por defecto.
     */
}
?>