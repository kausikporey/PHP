<?php

    $dsn = "mysql:host=localhost;dbname=php";
    try{
        $pdo = new PDO($dsn,'root','');
    }catch(PDOException $e){
        echo $e -> getMessage();
    }

?>