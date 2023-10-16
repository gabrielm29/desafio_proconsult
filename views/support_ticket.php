<?php
    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['expire_time'] < time()) {
        // Redirecionando o usuário para a página de login
        header("Location: ../views/login.php");
        exit;
    }

    // Importando arquivo com a conexão
    require_once("../models/db.php");
    
    $id = $_GET["id"] ?? "";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets dos Clientes</title>
</head>
<body>
    <header>
        <h1>Tickets dos Clientes</h1>
    </header>
    <main>
        <p style="text-align:right;font-size:2em;"><a href="../controllers/logout.php">Logout</a></p>
        <?php 
            $select_query = "SELECT * FROM support_calls ORDER BY data_criacao DESC";
            $result_query = mysqli_query($conn, $select_query);
            while($row = mysqli_fetch_assoc($result_query)){
                echo "<div>";
                echo "<p>Título: ".$row['titulo']."</p>";
                echo "<p>Descrição: ".$row['descricao']."</p>";
                echo "<p>Status: ".$row['status_call']."</p>";
                echo "<p><a href='../controllers/download_attachment.php?id=".$row["user_id"]."&call_id=" . $row["id"] . "'>Baixar anexo</a></p>";
                if($row["status_call"] !== "Finalizado"){
                    echo "<p><a href='../controllers/support_ticket_controller.php?case=1&id=".$id."&call_id=" . $row["id"] . "'>Responder</a></p>";
                    echo "<p><a href='../controllers/support_ticket_controller.php?case=2&id=".$id."&call_id=" . $row["id"] . "'>Finalizar</a></p>";
                }
                echo "</div>";
                echo "<hr>";
            }
        ?>
    </main>
</body>
</html>