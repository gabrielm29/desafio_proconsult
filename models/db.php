<?php 
    /*Dados da Conexão*/
    $server = "localhost";
    $user = "root";
    $password = "";
    $database = "desafio_proconsult";

    /*Conectando*/
    $conn = mysqli_connect($server, $user, $password, $database);

    if(!$conn){
        die('Erro na conexão!'.mysqli_connect_error());
    }
?>