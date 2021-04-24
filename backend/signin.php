<?php 
    $curr_page ="Sign In";
    require_once('../include/db.php');
?>
<?php
    session_start();
    if(isset($_SESSION['login']) || isset($_COOKIE['user_id']) || isset($_COOKIE['user_nickname'])){
        header('Location:../index.php');
    }
?> 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Post Comment || Admin Panel</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
        <script data-search-pseudo-elements defer src="js/all.min.js"></script>
        <script src="js/feather.min.js"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                <?php
                    if(isset($_POST['submit'])){
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        $sql = "SELECT * FROM users WHERE user_email = :email";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([':email' => $email]);
                        $count = $stmt->rowCount();
                        if($count == 0 or $count > 1){
                            $error = "Invalid Username!";
                        }else if($count == 1){
                            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                            $user_password = $user['user_password'];
                            $user_name = $user['user_name'];
                            if(password_verify($password,$user_password)){
                                $success = "Successfully Signed in as ".$user['user_name'];
                                if(!empty($_POST['checkbox'])){
                                    $user_id = $user['user_id'];
                                    $user_nickname = $user['user_nickname'];
                                    $d_user_id = base64_encode($user_id);
                                    $d_user_nickname = base64_encode($user_nickname);
                                    $d_user_name = base64_encode($user_name);
                                    setcookie('user_id',$d_user_id,time() + 60*60*24,'/','','',true);
                                    setcookie('user_nickname',$d_user_nickname,time() + 60*60*24,'/','','',true);
                                    setcookie('user_name',$d_user_name,time() + 60*60*24,'/','','',true);
                                }
                                $_SESSION['user_name'] = $user['user_name'];
                                $_SESSION['user_role'] = $user['user_role'];
                                $_SESSION['user_id'] = $user['user_id'];
                                $_SESSION['login'] = 'success';
                                header("Refresh:2; url=../index.php");
                            }else{
                                $error = "Wrong Password!";
                            }
                        }
                    }
                ?>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header justify-content-center"><h3 class="font-weight-light my-4">SIGN IN</h3></div>
                                    <div class="card-body">
                                        <?php 
                                        if(isset($error)){
                                            echo "<p class='alert alert-danger'>{$error}</p>";
                                        }else if(isset($success)){
                                            echo "<p class='alert alert-success'>{$success}</p>";
                                        }
                                        ?>
                                        <form action="signin.php" method="POST">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input class="form-control py-4" name = "email" id="inputEmailAddress" type="email" placeholder="Enter email address" />
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control py-4" name="password" id="inputPassword" type="password" placeholder="Enter password" />
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input name="checkbox" class="custom-control-input" id="rememberPasswordCheck" type="checkbox" />
                                                    <label class="custom-control-label" for="rememberPasswordCheck">Remember password</label>
                                                </div>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button type="submit" name="submit" class="btn btn-primary btn-block">SIGN IN</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="signup.php">Need an account? Sign up!</a></div>
                                        <div class="small"><a href="forgot-password.php">Forgot Password</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <?php require_once('./include/footer.php'); ?>