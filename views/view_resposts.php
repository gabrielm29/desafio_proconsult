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
    
    // Obtém o valor "id" dos parâmetros GET da URL
    $id = $_GET["id"] ?? "";
    $call_id = $_GET["call_id"] ?? "";

    // Consulta o tipo de conta do usuário (cliente ou colaborador)
    $select_sql = "SELECT * FROM users WHERE id=?";

    $stmt = mysqli_prepare($conn, $select_sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    
    $result_sql = mysqli_stmt_get_result($stmt);
    $row_user = mysqli_fetch_assoc($result_sql);

    // Caso o usuário seja um cliente, o redireciona para a sua página
    if ($row_user["tipo_conta"] == "collaborator") {
        header("Location: view_tickets.php?id=$id");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respostas</title>
</head>
<body>
    <header>
        <h1>Respostas</h1>
    </header>
    <main>
        <p style="text-align:right;font-size:2em;"><a href="../controllers/logout.php">Logout</a></p>
        <?php 
            // Consulta ao banco de dados para recuperar os chamados de suporte
            $select_query = "SELECT * FROM support_calls WHERE id='$call_id' ORDER BY data_criacao DESC";
            $result_query = mysqli_query($conn, $select_query);
            
            // Itera pelos resultados da consulta
            while ($row = mysqli_fetch_assoc($result_query)) {
                echo "<div>";
                echo "<p>Título: " . $row['titulo'] . "</p>";
                echo "<p>Descrição: " . $row['descricao'] . "</p>";
                echo "<p>Status: " . $row['status_call'] . "</p>";
                
                // Link para baixar anexo
                echo "<p><a href='../controllers/download_attachment.php?id=" . $row["user_id"] . "&call_id=" . $row["id"] . "'>Baixar anexo</a></p>";

                $select_resp = "SELECT resposta FROM resposts WHERE call_id='$call_id'";
                $result_resp = mysqli_query($conn, $select_resp);
                $row_resp = mysqli_fetch_assoc($result_resp);

                // Resposta
                echo "<form action='../controllers/resposts_controller.php?id=$id&call_id=$call_id' method='post'>";
                echo "<div><label for='desc'>Resposta: </label>";
                echo "<textarea name='resp' id='resp' cols='20' rows='5'required readonly>".$row_resp["resposta"]."</textarea></div>";
                echo "</form>";
            
                // Verifica se o chamado não está finalizado para permitir ações
                if ($row["status_call"] !== "Finalizado") {
                    
                    // Link para finalizar o chamado
                    echo "<p><a href='../views/view_tickets.php?case=2&id=" . $id . "&call_id=" . $row["id"] . "'>Voltar</a></p>";

                    // Link para finalizar o chamado
                    echo "<p><a href='../controllers/support_ticket_controller.php?case=2&id=" . $id . "&call_id=" . $row["id"] . "'>Finalizar</a></p>";
                }
                
                echo "</div>";
                echo "<hr>";
            }
        ?>
    </main>
</body>
</html>