<?php 
    // Iniciando ou resumindo a sessão PHP
    session_start();

    // Importando arquivo com a conexão ao banco de dados
    require_once("../models/db.php");

    // Verificando se o usuário está logado e se a sessão ainda é válida
    if (!isset($_SESSION['user_id']) || $_SESSION['expire_time'] < time()) {

        // Redirecionando o usuário para a página de login se não estiver logado ou a sessão expirou
        header("Location: ../views/login.php");
        exit;
    }

    // Recebendo valores passados via POST e GET de forma segura
    $id = hash('sha256', uniqid(mt_rand(), true));
    $user_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
    $call_id = filter_input(INPUT_GET, 'call_id', FILTER_SANITIZE_STRING);
    $resp = filter_input(INPUT_POST, 'resp', FILTER_SANITIZE_STRING);

    // Inserindo os valores na tabela usando consulta preparada
    $insert_sql = "INSERT INTO resposts(id, user_id, call_id, resposta) VALUES (?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $insert_sql)) {
        mysqli_stmt_bind_param($stmt, "ssss", $id, $user_id, $call_id, $resp);
        if (mysqli_stmt_execute($stmt)) {

            // Redirecionando para a página de visualização de chamados após a inserção bem-sucedida
            header("Location: ../views/support_ticket.php?id=" . $user_id);
        } else {

            // Tratamento de erro ao inserir no banco de dados
            echo "Erro ao inserir no banco de dados.";
            header("Location: ../views/resposts.php?id=" . $user_id);
        }
        mysqli_stmt_close($stmt);
    } else {

        // Tratamento de erro na preparação da consulta
        echo "Erro na preparação da consulta.";
        header("Location: ../views/resposts.php?id=" . $user_id);
    }

    // Fechando a conexão com o banco de dados após o uso
    mysqli_close($conn);
?>
