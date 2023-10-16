<?php
    // Iniciando ou resumindo a sessão PHP
    session_start();

    // Importa o arquivo com a conexão ao banco de dados
    require_once("../models/db.php");

    // Verifica se o usuário está logado e se a sessão é válida
    if (!isset($_SESSION['user_id']) || $_SESSION['expire_time'] < time()) {

        // Redireciona o usuário para a página de login se não estiver logado ou a sessão expirou
        header("Location: ../views/login.php");
        exit;
    }

    // Obtém os parâmetros da URL (GET)
    $id = isset($_GET["id"]) ? $_GET["id"] : "";
    $call_id = isset($_GET["call_id"]) ? $_GET["call_id"] : "";

    if ($id && $call_id) {

        // Prepara e executa uma consulta para recuperar os detalhes do anexo
        $select_query = "SELECT anexo_nome, anexo_tipo, anexo_arquivo FROM support_calls WHERE id=? AND user_id=?";

        $stmt = mysqli_prepare($conn, $select_query);
        mysqli_stmt_bind_param($stmt, "ss", $call_id, $id);
        mysqli_stmt_execute($stmt);

        $result_query = mysqli_stmt_get_result($stmt);
        
        if ($result_query && $row = mysqli_fetch_assoc($result_query)) {
            if ($row["anexo_tipo"] && $row["anexo_nome"] && $row["anexo_arquivo"]) {

                // Define o tipo de conteúdo e cabeçalho para download do anexo
                header("Content-type: " . $row["anexo_tipo"]);
                header("Content-Disposition: attachment; filename=" . $row["anexo_nome"]);

                // Exibe o conteúdo do anexo
                echo $row["anexo_arquivo"];
            } else {

                // Redireciona de volta para a página de chamados de suporte
                header("Location: ../views/view_tickets.php?id=$id");
            }
        } else {

            // Redireciona de volta para a página de chamados de suporte com uma mensagem de erro
            header("Location: ../views/view_tickets.php?id=$id");
            echo "Erro ao recuperar o anexo.";
        }
    } else {
        
        // Redireciona de volta para a página de chamados de suporte com uma mensagem de erro
        header("Location: ../views/view_tickets.php?id=$id");
        echo "Parâmetros inválidos.";
    }
?>