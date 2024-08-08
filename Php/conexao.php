<?php
    /*
    ESSE ARQUIVO É "CONEXÃO", É PARA SER UM "PADRÃO", ONDE AO USAR "include 'db_conn.php';" ESTE MODELO É IMPORTADO.
    */
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Capivara";

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Verificar conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
?>