<?php 
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
    }

    // Inserindo os valores na tabela

    $insert_sql = "INSERT INTO support_calls(id, user_id, titulo, descricao, anexo_nome, anexo_tipo, anexo_arquivo) VALUES ('$id', '$user_id', '$title', '$desc', '$name_attachment', '$type_attachment', '$data_attachment')";

    $result = mysqli_query($conn, $insert_sql);
?>