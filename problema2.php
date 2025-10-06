<?php
abstract class SaludoAbstracto {
    protected $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }


    abstract public function saludar(): string;
}


class SaludoEspañol extends SaludoAbstracto {
    public function saludar(): string {
        return "¡Hola, " . $this->nombre . "!";
    }
}

// Saludo en inglés
class SaludoIngles extends SaludoAbstracto {
    public function saludar(): string {
        return "Hello, " . $this->nombre . "!";
    }
}


function obtenerSaludo($idioma, $nombre) {
    switch(strtolower($idioma)) {
        case 'es':
        case 'español':
            return new SaludoEspañol($nombre);
        case 'en':
        case 'ingles':
            return new SaludoIngles($nombre);
        default:
            return new SaludoEspañol($nombre); 
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $idioma = $_POST['idioma'];

    $saludo = obtenerSaludo($idioma, $nombre);
    echo "<h2>" . $saludo->saludar() . "</h2>";
}
?>


<form method="post">
    Nombre: <input type="text" name="nombre" required>
    Idioma:
    <select name="idioma">
        <option value="es">Español</option>
        <option value="en">Inglés</option>
    </select>
    <button type="submit">Saludar</button>
</form>
