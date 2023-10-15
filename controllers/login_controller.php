<?php 
    // Importando arquivo com a conexão
    require_once("../models/db.php");

    // Recebendo e-mail e senha
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    /*Verificando se E-mail está cadastrado*/
    $select_sql = "SELECT * FROM users WHERE email='$email'";
    $result_select = mysqli_query($conn, $select_sql);
    $user = mysqli_fetch_assoc($result_select);
    
    if($user !== null){
        if(password_verify($password, $user["senha"])){
            if($user["tipo_conta"] == "client"){
                header("Location: ../views/create_ticket.php?id=".$user["id"]);
            }else if($user["tipo_conta"] == "collaborator"){
                header("Location: ../views/support_ticket.php?id=".$user["id"]);
            }
        }else{
            header("Location: ../views/login.php");
        }
    }
?>