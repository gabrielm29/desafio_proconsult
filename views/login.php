<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <header>
        <h1>Faça Login!</h1>
    </header>
    <main>
        <form action="../controllers/login_controller.php" method="post" autocomplete="on">
            <div>
                <label for="email">E-mail: </label>
                <input type="email" name="email" id="email" required>
            </div>
            <div>
                <label for="password">Senha: </label>
                <input type="password" name="password" id="password" required>
            </div>
            <input type="submit" value="Logar">
        </form>
    </main>
</body>
</html>