<?php
    // Importa o arquivo com a conexão ao banco de dados
    require_once("../models/db.php");

    // Recebe o e-mail e senha do formulário de login
    $email = filter_var($_POST["email"] ?? "", FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"] ?? "";

    // Verifica se o e-mail é válido
    if ($email) {
        
        // Prepara e executa uma consulta para verificar se o e-mail existe no banco de dados
        $select_sql = "SELECT * FROM users WHERE email = ?";

        $stmt = mysqli_prepare($conn, $select_sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result_select = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result_select)) {

            // Se o e-mail existe no banco de dados, verifica a senha

            if (password_verify($password, $user["senha"])) {

                // Se a senha é válida, inicia uma sessão para autenticar o usuário
                session_start();
                $_SESSION["user_id"] = $user["id"];

                // Define um tempo de expiração para a sessão (24 horas)
                $_SESSION["expire_time"] = time() + 86400;

                // Redireciona o usuário com base no tipo de conta
                if ($user["tipo_conta"] == "client") {
                    header("Location: ../views/create_ticket.php?id=" . $user["id"]);
                } elseif ($user["tipo_conta"] == "collaborator") {
                    header("Location: ../views/support_ticket.php?id=" . $user["id"]);
                }
            } else {

                // Redireciona de volta para a página de login com um erro de senha
                header("Location: ../views/login.php?error=password");
            }
        } else {

            // Redireciona de volta para a página de login com um erro de e-mail
            header("Location: ../views/login.php?error=email");
        }
    } else {
        
        // Redireciona de volta para a página de login com um erro de e-mail inválido
        header("Location: ../views/login.php?error=email");
    }
?>