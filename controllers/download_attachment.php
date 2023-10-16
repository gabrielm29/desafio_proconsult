<?php
    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['expire_time'] < time()) {
        // Redirecionando o usuário para a página de login
        header("Location: ../views/login.php");
        exit;
    }
     
    // Importando arquivo com a conexão
    require_once("../models/db.php");

    $id = isset($_GET["id"]) ? $_GET["id"] : "";
    $call_id = isset($_GET["call_id"]) ? $_GET["call_id"] : "";

    if ($id && $call_id) {
        $select_query = "SELECT anexo_nome, anexo_tipo, anexo_arquivo FROM support_calls WHERE id=? AND user_id=?";
        $stmt = mysqli_prepare($conn, $select_query);
        mysqli_stmt_bind_param($stmt, "ss", $call_id, $id);
        mysqli_stmt_execute($stmt);
        $result_query = mysqli_stmt_get_result($stmt);
        
        if ($result_query && $row = mysqli_fetch_assoc($result_query)) {
            if ($row["anexo_tipo"] && $row["anexo_nome"] && $row["anexo_arquivo"]) {
                header("Content-type: " . $row["anexo_tipo"]);
                header("Content-Disposition: attachment; filename=" . $row["anexo_nome"]);
                echo $row["anexo_arquivo"];
            } else {
                header("Location: ../views/view_tickets.php?id=$id");
            }
        } else {
            header("Location: ../views/view_tickets.php?id=$id");
            echo "Erro ao recuperar o anexo.";
        }
    } else {
        header("Location: ../views/view_tickets.php?id=$id");
        echo "Parâmetros inválidos.";
    }
?>

<?php /*
    // Importando arquivo com a conexão
    require_once("../models/db.php");

    $id = $_GET["id"] ?? "";
    $call_id = $_GET["call_id"] ?? "";

    $select_query = "SELECT anexo_nome, anexo_tipo, anexo_arquivo FROM support_calls WHERE id='$call_id' AND  user_id='$id'";
    $result_query = mysqli_query($conn, $select_query);
    $row = mysqli_fetch_assoc($result_query);

    if($row["anexo_tipo"] !== "" && $row["anexo_nome"] !== "" && $row["anexo_arquivo"] !== ""){
        header("Content-type: ".$row["anexo_tipo"]);
        header("Content-Disposition: attachment; filename=".$row["anexo_nome"]);
        echo $row["anexo_arquivo"];
    }else{
        header("Location: ../views/view_tickets.php?id=$id");
    }*/
?>