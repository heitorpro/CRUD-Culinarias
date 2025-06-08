<?php
    $server = "localhost";
    $user = "root";
    $pass = "";
    $bd = "gerenciador_receitas";

    if($conn = mysqli_connect($server, $user, $pass, $bd)){
       // echo "Conexão bem sucedida!";
    } else {
        echo "Erro!";
    }

?>