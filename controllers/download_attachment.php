<?php
    // Importando arquivo com a conexão
    require_once("../models/db.php");

    $id = $_GET["id"] ?? "";
    $call_id = $_GET["call_id"] ?? "";

    $select_query = "SELECT anexo_nome, anexo_tipo, anexo_arquivo FROM support_calls WHERE id='$call_id' AND  user_id='$id'";
    $result_query = mysqli_query($conn, $select_query);
    $row = mysqli_fetch_assoc($result_query);

    if($row["anexo_tipo"] !== "" && $row["anexo_nome"] !== "" && $row["anexo_arquivo"] !== ""){
        header("Content-type: ".$row["anexo_tipo"]);
        header("Content-Disposition: attachment; filename=".$row["anexo_nome"]);
        echo $row["anexo_arquivo"];
    }else{
        header("Location: ../views/view_tickets.php?id=$id");
    }
?>