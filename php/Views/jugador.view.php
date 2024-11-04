<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Formulario de Juego</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Jugador 1</h2>
        <label for="nombre1">Nombre:</label>
        <input type="text" id="nombre1" name="nombre1" required><br><br>
        
        <label for="color1">Color del jugador:</label>
        <input type="color" id="color1" name="color1" required><br><br>

        <h2>Jugador 2</h2>
        <label for="nombre2">Nombre:</label>
        <input type="text" id="nombre2" name="nombre2" required><br><br>
        
        <label for="color2">Color del jugador:</label>
        <input type="color" id="color2" name="color2" required><br><br>
        
        <input type="submit" value="Iniciar Juego">
    </form>
</body>
</html>