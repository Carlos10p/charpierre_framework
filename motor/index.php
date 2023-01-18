<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
<?php
    require_once __DIR__ . DIRECTORY_SEPARATOR .'conexion.php';

    $conexion = new conexion();

    $resultado= $conexion->realizaConsulta("SELECT * FROM nombre tabla");

    $num=$conexion->bd_cuentaRegistros($resultado);

    if($num>1){
        
        while($row = $conexion->bd_dameRegistro($resultado)){
			$name = $row['nombre_campo_tabla'];
			echo "Nombre: ".$name."<br/>";
		}
    }
?>
</body>
</html>