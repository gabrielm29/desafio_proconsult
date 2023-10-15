<?php 
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
    </main>
</body>
</html>