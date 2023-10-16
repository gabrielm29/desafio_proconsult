<?php
    // Importando arquivo com a conexão
    require_once("../models/db.php");

    // Recebendo e-mail e senha
    $email = filter_var($_POST["email"] ?? "", FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"] ?? "";

    if ($email) {
        // Verificação de e-mail
        $select_sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $select_sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result_select = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result_select)) {
            // Verificação de senha

            if (password_verify($password, $user["senha"])) {
                // Definindo uma sessão para manter o usuário autenticado
                session_start();
                $_SESSION["user_id"] = $user["id"];

                // Definindo um tempo de expiração
                $_SESSION["expire_time"] = time() + 86400;

                if ($user["tipo_conta"] == "client") {
                    header("Location: ../views/create_ticket.php?id=" . $user["id"]);
                } elseif ($user["tipo_conta"] == "collaborator") {
                    header("Location: ../views/support_ticket.php?id=" . $user["id"]);
                }
            } else {
                header("Location: ../views/login.php?error=password");
            }
        } else {
            header("Location: ../views/login.php?error=email");
        }
    } else {
        header("Location: ../views/login.php?error=email");
    }
?>

<?php /*
    // Importando arquivo com a conexão
    require_once("../models/db.php");

    // Recebendo e-mail e senha
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

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
    } */
?>