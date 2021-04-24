<?php
    session_start();
    ob_start();
if(isset($_SESSION['login'])){
    session_destroy();
    unset($_SESSION['login']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_role']);
    unset($_SESSION['user_id']);
}if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_nickname'])){
    setcookie('user_id','',time()-60*24*60,'/','','',true);
    setcookie('user_nickname','',time()-60*60*24,'/','','',true);
    setcookie('user_name','',time()-60*60*24,'/','','',true);
}
header('Location:index.php');
?>