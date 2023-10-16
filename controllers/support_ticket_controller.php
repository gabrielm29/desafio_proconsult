<?php 
    // Importando arquivo com a conexão
    require_once("../models/db.php");

    $id = $_GET["id"] ?? "";
    $call_id = $_GET["call_id"] ?? "";
    $case = $_GET["case"] ?? "";

    switch($case){
        case 1:
            $update_query = "UPDATE support_calls SET status_call='Atendimento' WHERE id='$call_id'";
            $result = mysqli_query($conn, $update_query);
            header("Location: ../views/support_ticket.php?id=$id");
            break;
        case 2:
            $update_query = "UPDATE support_calls SET status_call='Finalizado' WHERE id='$call_id'";
            $result = mysqli_query($conn, $update_query);
            header("Location: ../views/support_ticket.php?id=$id");
            break;
    }
?>