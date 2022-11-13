<?php

    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "db_myapp";
    $port = "3306";

    //Conexao 
    
    $conn = new PDO("mysql:host=$host;port=$port;dbname=" . $dbname, $user, $pass);

?>