<?php
interface Figura {
    public function calcularArea(): float;
    public function getNombre(): string;
}

abstract class FiguraGeometrica implements Figura {
    protected string $nombre;
    public function __construct(string $nombre) { $this->nombre = $nombre; }
    public function getNombre(): string { return $this->nombre; }
    abstract public function calcularArea(): float;
}

// Clases de figuras
class Rectangulo extends FiguraGeometrica {
    private float $base, $altura;
    public function __construct(float $b, float $a) {
        parent::__construct("Rectángulo");
        $this->base = $b;
        $this->altura = $a;
    }
    public function calcularArea(): float { return $this->base * $this->altura; }
}

class Triangulo extends FiguraGeometrica {
    private float $b, $h;
    public function __construct(float $b, float $h) {
        parent::__construct("Triángulo");
        $this->b = $b;
        $this->h = $h;
    }
    public function calcularArea(): float { return ($this->b * $this->h) / 2; }
}

class Circulo extends FiguraGeometrica {
    private float $r;
    public function __construct(float $r) {
        parent::__construct("Círculo");
        $this->r = $r;
    }
    public function calcularArea(): float { return pi() * pow($this->r, 2); }
}

// Variables para resultados
$resultado = null;
$error = "";
$area = "";
$nombreFigura = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tipo = $_POST['tipo'] ?? '';
        switch ($tipo) {
            case 'rectangulo':
                $b = (float)($_POST['rect_base'] ?? 0);
                $a = (float)($_POST['rect_altura'] ?? 0);
                if ($b <= 0 || $a <= 0) throw new Exception("Valores deben ser mayores a 0");
                $resultado = new Rectangulo($b, $a);
                break;

            case 'triangulo':
                $b = (float)($_POST['tri_base'] ?? 0);
                $h = (float)($_POST['tri_altura'] ?? 0);
                if ($b <= 0 || $h <= 0)
                    throw new Exception("Todos los valores deben ser mayores a 0");
                $resultado = new Triangulo($b, $h);
                break;

            case 'circulo':
                $r = (float)($_POST['circ_radio'] ?? 0);
                if ($r <= 0) throw new Exception("El radio debe ser mayor a 0");
                $resultado = new Circulo($r);
                break;

            default:
                throw new Exception("Selecciona una figura");
        }

        if ($resultado) {
            $nombreFigura = $resultado->getNombre();
            $area = number_format($resultado->calcularArea(), 4);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Calculadora de Área</title>
<style>
body {
    font-family: "Poppins", sans-serif;
    background: linear-gradient(135deg, #6DD5FA, #2980B9);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
}
.container {
    background: #fff;
    padding: 30px 40px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    width: 360px;
    text-align: center;
}
h1 {
    color: #2c3e50;
    font-size: 24px;
    margin-bottom: 20px;
}
label, select, input {
    display: block;
    width: 100%;
    margin-top: 10px;
    padding: 10px;
    border: 2px solid #ccc;
    border-radius: 10px;
    font-size: 15px;
}
button {
    margin-top: 20px;
    background-color: #3498db;
    color: white;
    border: none;
    padding: 10px;
    width: 100%;
    border-radius: 10px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}
button:hover {
    background-color: #2980b9;
}
.resultado {
    margin-top: 20px;
    padding: 15px;
    background: #ecf9ff;
    border-radius: 10px;
    color: #2c3e50;
    font-weight: bold;
    font-size: 18px;
    animation: aparecer 0.5s ease;
}
.error {
    margin-top: 20px;
    background: #ffe6e6;
    padding: 15px;
    border-radius: 10px;
    border-left: 5px solid #e74c3c;
    color: #c0392b;
    font-weight: bold;
}
@keyframes aparecer {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
<script>
function mostrarCampos(val){
    document.getElementById('rect').style.display = (val === 'rectangulo') ? 'block' : 'none';
    document.getElementById('tri').style.display = (val === 'triangulo') ? 'block' : 'none';
    document.getElementById('circ').style.display = (val === 'circulo') ? 'block' : 'none';
}
</script>
</head>
<body>

<div class="container">
    <h1>📏 Calculadora de Área</h1>

    <form method="post">
        <label>Selecciona la figura:</label>
        <select name="tipo" onchange="mostrarCampos(this.value)" required>
            <option value="">--Elige--</option>
            <option value="rectangulo" <?= (isset($_POST['tipo']) && $_POST['tipo'] == 'rectangulo') ? 'selected' : '' ?>>Rectángulo</option>
            <option value="triangulo" <?= (isset($_POST['tipo']) && $_POST['tipo'] == 'triangulo') ? 'selected' : '' ?>>Triángulo</option>
            <option value="circulo" <?= (isset($_POST['tipo']) && $_POST['tipo'] == 'circulo') ? 'selected' : '' ?>>Círculo</option>
        </select>

        <!-- Campos Rectángulo -->
        <div id="rect" style="display:none;">
            <label>Base:</label>
            <input type="number" name="rect_base" min="0.01" step="0.01" value="<?= $_POST['rect_base'] ?? '' ?>">
            <label>Altura:</label>
            <input type="number" name="rect_altura" min="0.01" step="0.01" value="<?= $_POST['rect_altura'] ?? '' ?>">
        </div>

        <!-- Campos Triángulo -->
        <div id="tri" style="display:none;">
            <label>Base:</label>
            <input type="number" name="tri_base" min="0.01" step="0.01" value="<?= $_POST['tri_base'] ?? '' ?>">
            <label>Altura:</label>
            <input type="number" name="tri_altura" min="0.01" step="0.01" value="<?= $_POST['tri_altura'] ?? '' ?>">
        </div>

        <!-- Campo Círculo -->
        <div id="circ" style="display:none;">
            <label>Radio:</label>
            <input type="number" name="circ_radio" min="0.01" step="0.01" value="<?= $_POST['circ_radio'] ?? '' ?>">
        </div>

        <button type="submit">Calcular Área</button>
    </form>

    <!-- Resultado -->
    <?php if ($area): ?>
        <div class="resultado">
            <?= "Área del " . htmlspecialchars($nombreFigura) . ": <br><strong>" . htmlspecialchars($area) . " unidades²</strong>" ?>
        </div>
    <?php endif; ?>

    <!-- Error -->
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
</div>

<script>
mostrarCampos('<?= $_POST['tipo'] ?? '' ?>');
</script>
</body>
</html>
