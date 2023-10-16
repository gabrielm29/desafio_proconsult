<?php
    // Inicia a sessão para gerenciar o estado do usuário
    session_start();

    // Importa o arquivo com a configuração da conexão ao banco de dados
    require_once("../models/db.php");

    // Verifica se o usuário está autenticado e se a sessão é válida
    if (!isset($_SESSION['user_id']) || $_SESSION['expire_time'] < time()) {
        // Redireciona o usuário para a página de login se não estiver autenticado ou a sessão expirou
        header("Location: ../views/login.php");
        exit;
    }

    // Obtém os parâmetros da URL (GET) para usar posteriormente
    $id = $_GET["id"] ?? ""; // ID do usuário
    $call_id = $_GET["call_id"] ?? ""; // ID do chamado de suporte
    $case = $_GET["case"] ?? ""; // Caso (1 para "Atendimento" ou 2 para "Finalizado")

    // Consulta o tipo de conta do usuário (cliente ou colaborador)
    $select_sql = "SELECT * FROM users WHERE id=?";

    $stmt = mysqli_prepare($conn, $select_sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    
    $result_sql = mysqli_stmt_get_result($stmt);
    $row_user = mysqli_fetch_assoc($result_sql);

    if ($row_user["tipo_conta"] == "client") {
        $case = 2; // Define o caso 2 para clientes
    }

    // Verifica se todos os parâmetros necessários estão presentes
    if ($id && $call_id && $case) {
        $update_query = "";

        // Determina a ação a ser realizada com base no caso
        switch ($case) {
            case 1:
                $update_query = "UPDATE support_calls SET status_call='Atendimento' WHERE id = ?";
                break;
            case 2:
                $update_query = "UPDATE support_calls SET status_call='Finalizado' WHERE id = ?";
                break;
        }

        if ($update_query) {
            // Prepara a consulta e executa a atualização do chamado de suporte
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "s", $call_id);
            $result = mysqli_stmt_execute($stmt);

            // Determina a página de redirecionamento com base no tipo de conta
            $redirect_location = ($row_user["tipo_conta"] == "client") ? "../views/view_tickets.php?id=$id" : "../views/resposts.php?id=$id&call_id=$call_id";

            if ($result) {
                // Redireciona o usuário para a página apropriada após a atualização bem-sucedida
                header("Location: $redirect_location");
            } else {
                // Exibe uma mensagem de erro em caso de falha na atualização
                $error_message = "Erro ao atualizar o chamado de suporte.";
                echo $error_message;
                header("Location: $redirect_location");
            }
        } else {
            // Redireciona o usuário para a página apropriada em caso de caso não correspondente
            $redirect_location = ($row_user["tipo_conta"] == "client") ? "../views/view_tickets.php?id=$id" : "../views/support_ticket.php?id=$id";
            header("Location: $redirect_location");
        }
    } else {
        // Redireciona o usuário para a página apropriada em caso de parâmetros ausentes
        $redirect_location = ($row_user["tipo_conta"] == "client") ? "../views/view_tickets.php?id=$id" : "../views/support_ticket.php?id=$id";
        header("Location: $redirect_location");
    }
?>