<?php require_once('./include/db.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content />
        <meta name="author" content />
        <title><?php echo $current_page; ?></title>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="img/favicon.png" />
    </head>
    <div id="layoutDefault">
            <div id="layoutDefault_content">
                <main>
                    
                    <nav class="navbar navbar-marketing navbar-expand-lg bg-white navbar-light">
                        <div class="container">
                            <a class="navbar-brand text-dark" href="index.php">MyBlog</a><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><img src="img/menu.png" style="height:20px;width:25px" /><i data-feather="menu"></i></button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ml-auto mr-lg-5">
                                    <li class="nav-item">
                                        <a class="nav-link" href="index.php">Home </a>
                                    </li>
                                    <li class="nav-item dropdown no-caret">
                                        <a class="nav-link" href="contact.php">Contact</a>
                                    </li>
                                    <li class="nav-item dropdown no-caret">
                                        <a class="nav-link" href="about.php">About</a>
                                    </li>
                                </ul>
                                <?php 
                                    session_start();
                                    if(isset($_SESSION['login'])){ ?>
                                        <form action="signout.php">
                                            <button class="btn-teal btn rounded-pill px-4 ml-lg-4">Sign Out (<?php echo $_SESSION['user_name']; ?>)</button>
                                        </form>
                                    <?php 
                                    }else{
                                        if(!isset($_COOKIE['user_id']) && !isset($_COOKIE['user_nickname'])){
                                            echo '<a class="btn-teal btn rounded-pill px-4 ml-lg-4" href="backend/signin.php">Sign in</a>';
                                            echo '<a class="btn-teal btn rounded-pill px-4 ml-lg-4" href="backend/signup.php">Sign up</a>';
                                         }else{
                                             $user_id = base64_decode($_COOKIE['user_id']);
                                             $user_nickname = base64_decode($_COOKIE['user_nickname']);
                                             $sql = "SELECT * FROM users WHERE user_id = :id AND user_nickname = :nickname";
                                             $stmt = $pdo->prepare($sql);
                                             $stmt->execute([':id' => $user_id,':nickname' => $user_nickname]);
                                             $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                             $user_name = $user['user_name'];?>
                                             <form action="signout.php">
                                                <button class="btn-teal btn rounded-pill px-4 ml-lg-4">Sign Out (<?php echo $user['user_name']; ?>)</button>
                                             </form>
                                    <?php   }
                                       }
                                    ?>
                            </div>
                        </div>
                    </nav>