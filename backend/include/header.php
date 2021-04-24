<?php ob_start(); ?>
<?php require_once('../include/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title><?php echo $curr_page; ?></title>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
        <script data-search-pseudo-elements defer src="js/all.min.js"></script>
        <script src="js/feather.min.js"></script>
    </head>

    <?php 
        session_start();
        if((isset($_SESSION['login'])) && $_SESSION['user_role'] == 'Admin'){
            
        }else if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_nickname'])){
            $sql = "SELECT user_role FROM users WHERE user_id = :id AND user_nickname = :nickname";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => base64_decode($_COOKIE['user_id']),
                ':nickname' => base64_decode($_COOKIE['user_nickname'])
            ]);
            $count = $stmt->rowCount();
            if($count == 1){
                $user_header = $stmt->fetch(PDO::FETCH_ASSOC);
                $user_role_header = $user_header['user_role'];
                if($user_role_header != 'Admin'){
                    header('Location:../index.php');
                }
            } 
        }
        else{
            header('Location:../index.php');
        }

    ?>    