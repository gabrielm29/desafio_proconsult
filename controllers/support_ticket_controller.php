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
    $call_id = $_GET["call_id"] ?? "";
    $case = $_GET["case"] ?? "";

    if ($id && $call_id && $case) {

        // Evitando injeção de SQL usando consultas preparadas
        $update_query = "";
        
        switch ($case) {
            case 1:
                $update_query = "UPDATE support_calls SET status_call='Atendimento' WHERE id = ?";
                break;
            case 2:
                $update_query = "UPDATE support_calls SET status_call='Finalizado' WHERE id = ?";
                break;
        }

        if ($update_query) {
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "s", $call_id);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                header("Location: ../views/support_ticket.php?id=$id");
            } else {
                // Tratamento de erro - redirecionamento, mensagem de erro, etc.
                echo "Erro ao atualizar o chamado de suporte.";
                header("Location: ../views/support_ticket.php?id=$id");
            }
        } else {
            // Caso não correspondente
            header("Location: ../views/support_ticket.php?id=$id");
        }
    } else {
        // Parâmetros ausentes
        header("Location: ../views/support_ticket.php?id=$id");
    }
?>
<?php /*
    // Importando arquivo com a conexão
    require_once("../models/db.php");

    $id = $_GET["id"] ?? "";
    $call_id = $_GET["call_id"] ?? "";
    $case = $_GET["case"] ?? "";

    switch($case){
        case 1:
            $update_query = "UPDATE support_calls SET status_call='Atendimento' WHERE id='$call_id'";
            $result = mysqli_query($conn, $update_query);
            header("Location: ../views/support_ticket.php?id=$id");
            break;
        case 2:
            $update_query = "UPDATE support_calls SET status_call='Finalizado' WHERE id='$call_id'";
            $result = mysqli_query($conn, $update_query);
            header("Location: ../views/support_ticket.php?id=$id");
            break;
    } */
?>