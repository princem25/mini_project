<?php
$connected = 0;
try {

    $conn = new PDO("mysql:host=localhost;dbname=tournament","root");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connected = 1;
 
} catch (PDOException $e) {

file_put_contents(__DIR__."/../error.txt",date("H:i:s Y-m-d : ").$e->getMessage().PHP_EOL,FILE_APPEND);
    echo "error : ".$e->getMessage();
}
