<?php
    // Inicia a sessão
    session_start();

    // Importa o arquivo com a conexão ao banco de dados
    require_once("../models/db.php");
    
    // Verifica se o usuário está logado e se a sessão é válida
    if (!isset($_SESSION['user_id']) || $_SESSION['expire_time'] < time()) {

        // Redireciona o usuário para a página de login se não estiver logado ou a sessão expirou
        header("Location: ../views/login.php");
        exit;
    }
    
    // Obtém o valor 'id' dos parâmetros passados pela URL
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
        <p style="text-align:right;font-size:2em;"><a href="../controllers/logout.php">Logout</a></p>
        <?php 
            // Consulta ao banco de dados para recuperar os chamados de suporte associados ao usuário com o ID especificado
            $select_query = "SELECT * FROM support_calls WHERE user_id='$id' ORDER BY data_criacao DESC";
            $result_query = mysqli_query($conn, $select_query);
            
            // Itera pelos resultados da consulta
            while ($row = mysqli_fetch_assoc($result_query)) {
                echo "<div>";
                echo "<p>Título: " . $row['titulo'] . "</p>";
                echo "<p>Descrição: " . $row['descricao'] . "</p>";
                echo "<p>Status: " . $row['status_call'] . "</p>";
            
                // Link para baixar anexo
                echo "<p><a href='../controllers/download_attachment.php?id=$id&call_id=" . $row["id"] . "'>Baixar anexo</a></p>";

                // Verifica se o chamado não está finalizado para permitir ações
                if ($row["status_call"] !== "Finalizado") {
                    // Link para finalizar o chamado
                    echo "<p><a href='../controllers/support_ticket_controller.php?case=2&id=" . $id . "&call_id=" . $row["id"] . "'>Finalizar</a></p>";

                    // Link para ver a resposta
                    echo "<p><a href='../views/view_resposts.php?case=2&id=" . $id . "&call_id=" . $row["id"] . "'>Ver Resposta</a></p>";
                }
            
                echo "</div>";
                echo "<hr>";
            }
        ?>
    </main>
</body>
</html>