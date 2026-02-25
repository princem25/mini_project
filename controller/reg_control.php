<?php
 $username = trim($_POST['name']);
 $password = trim($_POST['pass']);
 $role = $_POST['role'];

 require_once "C:/xampp/htdocs/mini_pro/config/dbconfig.php";

 try {
    if($connected == 1){
    $res = $conn->query("select * from users where name = '$username' and role_id = '$role' ");
    if($res->rowCount() > 0){
         echo "exists"; 
         exit;
    }
    else{
        $stmt = $conn->prepare("insert into users (name,password,role_id) values (?,?,?)");
        $stmt->execute([$username,hash("sha256",$password),$role]);
            echo "registered";
    }
    }
 } catch (PDOException $e) {
    file_put_contents(__DIR__."/../error.txt",date("H:i:s Y-m-d : ").$e->getMessage().PHP_EOL,FILE_APPEND);
    echo "error : ".$e->getMessage();
    
 }
?>
 