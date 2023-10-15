<?php 
    // Importando o arquivo com a conexão
    require_once("../models/db.php");

    // Recebendo os valores passado pelo método POST
    $id = hash('sha256', uniqid(mt_rand(), true));
    $name = $_POST["name"] ?? "";
    $cpf = $_POST["cpf"] ?? "";
    $email = $_POST["email"] ?? "";
    $password = password_hash($_POST["password"] ?? "", PASSWORD_BCRYPT);
    $option_user = $_POST["option_user"] ?? "";

    /*Verificando se E-mail ou CPF já foram cadastrados*/
    $select_sql = "SELECT email, cpf FROM users WHERE email='$email' OR cpf='$cpf'";
    $result_select = mysqli_query($conn, $select_sql);
    
    if(!mysqli_num_rows($result_select) > 0){
        // Inserindo os valores na tabela
        $insert_sql = "INSERT INTO users(id, nome_completo, cpf, email, senha, tipo_conta) VALUES ('$id', '$name', '$cpf', '$email', '$password', '$option_user')";

        $result = mysqli_query($conn, $insert_sql);

        if(mysqli_insert_id($conn)){
            header("Location: ../views/register.php");
        }else{
            header("Location: ../views/register.php");
        }
    }else{
        header("Location: ../views/register.php");
    }
?>