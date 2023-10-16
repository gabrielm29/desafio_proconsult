<?php
    // Inicia a sessão
    session_start();

    // Importando arquivo com a conexão ao banco de dados
    require_once("../models/db.php"); 
    
    // Verifica se o usuário está logado e se a sessão é válida
    if (!isset($_SESSION['user_id']) || $_SESSION['expire_time'] < time()) {

        // Redireciona o usuário para a página de login se não estiver logado ou a sessão expirou
        header("Location: ../views/login.php");
        exit;
    }
    
    // Obtém o valor "id" dos parâmetros GET da URL
    $id = $_GET["id"] ?? "";

    // Consulta o tipo de conta do usuário (cliente ou colaborador)
    $select_sql = "SELECT * FROM users WHERE id=?";

    $stmt = mysqli_prepare($conn, $select_sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    
    $result_sql = mysqli_stmt_get_result($stmt);
    $row_user = mysqli_fetch_assoc($result_sql);

    // Caso o usuário seja um cliente, o redireciona para a sua página
    if ($row_user["tipo_conta"] == "collaborator") {
        header("Location: support_ticket.php?id=$id");
    }
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