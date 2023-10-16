<?php
    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['expire_time'] < time()) {
        // Redirecionando o usuário para a página de login
        header("Location: ../views/login.php");
        exit;
    } 
    $id = $_GET["id"] ?? "";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets do Cliente</title>
</head>
<body>
    <header>
        <h1>Crie o seu ticket</h1>
    </header>
    <main>
        <p style="text-align:right;font-size:2em;"><a href="../controllers/logout.php">Logout</a></p>
        <form action="../controllers/create_ticket_controller.php?id=<?php echo $id?>" method="post" autocomplete="on" enctype="multipart/form-data">
            <div>
                <label for="title">Título: </label>
                <input type="text" name="title" id="title" required>
            </div>
            <div>
                <label for="desc">Descrição: </label>
                <textarea name="desc" id="desc" cols="20" rows="5" required></textarea>
            </div>
            <div>
                <label for="attachment">Anexo (opcional): </label>
                <input type="file" name="attachment" id="attachment">
            </div>
            <input type="submit" value="Criar Chamado">
        </form>
        <p><a href="view_tickets.php?id=<?php echo $id?>">Ver Tickets</a></p>
    </main>
</body>
</html>