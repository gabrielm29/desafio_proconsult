<?php
    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['expire_time'] < time()) {
        // Redirecionando o usuário para a página de login
        header("Location: ../views/login.php");
        exit;
    }    

    // Importando arquivo com a conexão
    require_once("../models/db.php");

    // Recebendo os valores passados pelo método POST e GET de forma segura
    $id = hash('sha256', uniqid(mt_rand(), true));
    $user_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);
    $count_type = "Aberto";

    // Tratamento seguro de anexos
    if (isset($_FILES["attachment"]) && $_FILES["attachment"]["error"] === UPLOAD_ERR_OK) {
        $name_attachment = $_FILES["attachment"]["name"];
        $type_attachment = $_FILES["attachment"]["type"];
        $temporary_attachment = $_FILES["attachment"]["tmp_name"];
        $data_attachment = file_get_contents($temporary_attachment);
        $data_attachment = mysqli_real_escape_string($conn, $data_attachment);
    } else {
        $name_attachment = '';
        $type_attachment = '';
        $data_attachment = '';
    }

    // Inserindo os valores na tabela usando consulta preparada
    $insert_sql = "INSERT INTO support_calls(id, user_id, titulo, descricao, anexo_nome, anexo_tipo, anexo_arquivo, status_call) VALUES (?, ?, ?, ?, ?, ?, ?, '$count_type')";

    if ($stmt = mysqli_prepare($conn, $insert_sql)) {
        mysqli_stmt_bind_param($stmt, "sssssss", $id, $user_id, $title, $desc, $name_attachment, $type_attachment, $data_attachment);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../views/view_tickets.php?id=" . $user_id);
        } else {
            // Tratamento de erro ao inserir no banco de dados
            echo "Erro ao inserir no banco de dados.";
            header("Location: ../views/create_ticket.php?id=".$user_id);
        }
        mysqli_stmt_close($stmt);
    } else {
        // Tratamento de erro na preparação da consulta
        echo "Erro na preparação da consulta.";
        header("Location: ../views/create_ticket.php?id=".$user_id);
    }

    // Fechando a conexão após o uso
    mysqli_close($conn);
?>

<?php /* 
    // Importando arquivo com a conexão
    require_once("../models/db.php");

    // Recebendo os valores passado pelo método POST
    $id = hash('sha256', uniqid(mt_rand(), true));
    $user_id = $_GET["id"] ?? "";
    $title = $_POST["title"] ?? "";
    $desc = $_POST["desc"] ?? "";
    $name_attachment = $_FILES["attachment"]["name"] ?? "";
    $type_attachment = $_FILES["attachment"]["type"] ?? "";
    $temporary_attachment = $_FILES["attachment"]["tmp_name"] ?? "";

    if(is_uploaded_file($temporary_attachment)){
        $data_attachment = file_get_contents($temporary_attachment);
        $data_attachment = mysqli_real_escape_string($conn, $data_attachment);
    }else{
        $data_attachment = '';
    }

    // Inserindo os valores na tabela

    $insert_sql = "INSERT INTO support_calls(id, user_id, titulo, descricao, anexo_nome, anexo_tipo, anexo_arquivo, status_call) VALUES ('$id', '$user_id', '$title', '$desc', '$name_attachment', '$type_attachment', '$data_attachment', 'Aberto')";

    $result = mysqli_query($conn, $insert_sql);

    if(mysqli_affected_rows($conn) > 0){
        header("Location: ../views/view_tickets.php?id=".$user_id);
    }else{
        header("Location: ../views/create_ticket.php?id=".$user_id);
    }*/
?>
