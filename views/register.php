<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>
    <header>
        <h1>Crie sua conta!</h1>
    </header>
    <main>
        <form action="" method="post" autocomplete="on">
            <div>
                <label for="name">Digite seu nome completo: </label>
                <input type="text" name="name" id="name">
            </div>
            <div>
                <label for="cpf">Digite seu CPF: </label>
                <input type="text" name="cpf" id="cpf" pattern="/^\d{3}\.\d{3}\.\d{3}-\d{2}$|^\d{11}$/
" placeholder="12345678909">
            </div>
            <div>
                <label for="email">E-mail: </label>
                <input type="email" name="email" id="email">
            </div>
            <div>
                <label for="password">Senha: </label>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <label for="">Tipo de conta: </label>
                <div>Cliente <input type="radio" name="option_user" id="client" value="client" checked></div>
                <div>Colaborador <input type="radio" name="option_user" id="collaborator" value="collaborator"></div>
            </div>
            <input type="submit" value="Cadastrar">
        </form>
    </main>
</body>
</html>