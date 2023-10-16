<?php
    // Importando arquivo com a conexão
    require_once("../models/db.php");

    $id = $_GET["id"] ?? "";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Tickets</title>
</head>
<body>
    <header>
        <h1>Meus Tickets</h1>
    </header>
    <main>
        <?php 
            $select_query = "SELECT * FROM support_calls WHERE user_id='$id' ORDER BY data_criacao DESC";
            $result_query = mysqli_query($conn, $select_query);
            while($row = mysqli_fetch_assoc($result_query)){
                echo "<div>";
                echo "<p>Título: ".$row['titulo']."</p>";
                echo "<p>Descrição: ".$row['descricao']."</p>";
                echo "<p>Status: ".$row['status_call']."</p>";
                echo "<p><a href='../controllers/download_attachment.php?id=$id&call_id=" . $row["id"] . "'>Baixar anexo</a></p>";
                echo "</div>";
                echo "<hr>";
            }
        ?>
    </main>
</body>
</html>