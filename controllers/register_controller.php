<?php
    // Importando o arquivo com a conexão
    require_once("../models/db.php");

    // Recebendo os valores passados pelo método POST
    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING) ?? "";
    $cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_STRING) ?? "";
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL) ?? "";
    $password = password_hash(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING) ?? "", PASSWORD_BCRYPT);
    $option_user = filter_input(INPUT_POST, "option_user", FILTER_SANITIZE_STRING) ?? "";

    if (!empty($name) && !empty($cpf) && !empty($email) && !empty($password) && !empty($option_user)) {

        // Validação dos dados de entrada
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && validaCPF($cpf)) {

            // Verificação de duplicatas
            $select_sql = "SELECT email, cpf FROM users WHERE email = ? OR cpf = ?";
            $stmt = mysqli_prepare($conn, $select_sql);
            mysqli_stmt_bind_param($stmt, "ss", $email, $cpf);
            mysqli_stmt_execute($stmt);
            $result_select = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result_select) === 0) {

                // Inserindo os valores na tabela com consulta preparada
                $insert_sql = "INSERT INTO users(id, nome_completo, cpf, email, senha, tipo_conta) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insert_sql);
                $id = hash('sha256', uniqid(mt_rand(), true));
                mysqli_stmt_bind_param($stmt, "ssssss", $id, $name, $cpf, $email, $password, $option_user);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    header("Location: ../views/login.php?success=1");
                } else {
                    header("Location: ../views/register.php?error=1");
                }
            } else {
                header("Location: ../views/register.php?duplicate=1");
            }
        } else {
            header("Location: ../views/register.php?invalid=1");
        }
    } else {
        header("Location: ../views/register.php?missing_data=1");
    }

    function validaCPF($cpf) {

        // Remove todos os caracteres que não são dígitos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        // Verifica se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }
        
        // Verifica se o CPF não é uma sequência de dígitos iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        // Verifica o primeiro dígito verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }
        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : 11 - $remainder;
        
        if ($cpf[9] != $digit1) {
            return false;
        }
        
        // Verifica o segundo dígito verificador
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }
        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : 11 - $remainder;
        
        if ($cpf[10] != $digit2) {
            return false;
        }
        
        return true;
    }        
?>

<?php /*
    // Importando o arquivo com a conexão
    require_once("../models/db.php");

    // Recebendo os valores passado pelo método POST
    $id = hash('sha256', uniqid(mt_rand(), true));
    $name = $_POST["name"] ?? "";
    $cpf = $_POST["cpf"] ?? "";
    $email = $_POST["email"] ?? "";
    $password = password_hash($_POST["password"] ?? "", PASSWORD_BCRYPT);
    $option_user = $_POST["option_user"] ?? "";

    $select_sql = "SELECT email, cpf FROM users WHERE email='$email' OR cpf='$cpf'";
    $result_select = mysqli_query($conn, $select_sql);
    
    if(!mysqli_num_rows($result_select) > 0){
        $insert_sql = "INSERT INTO users(id, nome_completo, cpf, email, senha, tipo_conta) VALUES ('$id', '$name', '$cpf', '$email', '$password', '$option_user')";

        $result = mysqli_query($conn, $insert_sql);

        if(mysqli_insert_id($conn)){
            header("Location: ../views/login.php");
        }else{
            header("Location: ../views/register.php");
        }
    }else{
        header("Location: ../views/register.php");
    } */
?>