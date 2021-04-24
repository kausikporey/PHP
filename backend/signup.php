<?php 
    $curr_page = "Create User";
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
                            $firstname = $_POST['firstname'];
                            $lastname = $_POST['lastname'];
                            $fullname = $firstname.' '.$lastname;
                            $emailaddress = $_POST['emailaddress'];
                            $password = $_POST['password'];
                            $confirmpassword = $_POST['confirmpassword'];
                            $photo = "user-logo.jpg";
                            $nickname = $_POST['userNickname'];
                            //email already exist
                            $sql1 = "SELECT * FROM users WHERE user_email = :email";
                            $stmt = $pdo->prepare($sql1);
                            $stmt->execute([':email' => $emailaddress]);
                            $count = $stmt->rowCount();
                            if($count != 0){
                                $email_exist = "Email Already Exist";
                            }else{
                                    //NickName already exist
                                    $sql2 = "SELECT * FROM users WHERE user_nickname = :nickname";
                                    $stmt = $pdo->prepare($sql2);
                                    $stmt->execute([':nickname' => $nickname]);
                                    $count = $stmt->rowCount();
                                    if($count != 0){
                                        $nickname_exist = "NickName Already Exist";
                                    }else{
                                            //Password Checking
                                            if($password != $confirmpassword){
                                                $error = "Password Does not match";
                                            }else{
                                                date_default_timezone_set('Asia/calcutta');
                                                $hash = password_hash($password,PASSWORD_BCRYPT,['cost' => 10]);
                                                $sql = "INSERT INTO users (user_name,user_nickname,user_email,user_password,user_photo,registered_on) VALUES (:fullname,:nickname,:emailaddress,:password,:photo,:date)";
                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute([
                                                    ':fullname' => $fullname,
                                                    ':nickname' => $nickname,
                                                    ':emailaddress' => $emailaddress,
                                                    ':password' => $hash,
                                                    ':photo' => $photo,
                                                    ':date' => date("M d, Y").' at '.date("h:i A")
                                                ]);
                                                $sucess = "Account Created Successfully";
                                                header("Refresh:2;url=signin.php");
                                                }
                                        }
                                }
                            }
                    ?>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header justify-content-center"><h3 class="font-weight-light my-4">Create Account</h3></div>
                                    <div class="card-body">
                                        <form action="signup.php" method="POST">
                                            <?php
                                                if(isset($email_exist)){
                                                    echo "<p class='alert alert-danger'>{$email_exist}</p>";
                                                } 
                                                else if(isset($nickname_exist)){
                                                    echo "<p class='alert alert-danger'>{$nickname_exist}</p>";
                                                }
                                                else if(isset($error)){
                                                    echo "<p class='alert alert-danger'>{$error}</p>";
                                                }
                                                else if(isset($sucess)){
                                                    echo "<p class='alert alert-success'>{$sucess}</p>";
                                                }
                                            ?>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="small mb-1" for="inputFirstName">First Name</label><input class="form-control py-4" name="firstname" id="inputFirstName" type="text" placeholder="Enter first name" /></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="small mb-1" for="inputLastName">Last Name</label><input class="form-control py-4" name="lastname" id="inputLastName" type="text" placeholder="Enter last name" /></div>
                                                </div>
                                            </div>
                                            <div class="form-group"><label class="small mb-1" for="userNickname">Nick Name</label><input class="form-control py-4" name="userNickname" id="userNickname" type="text" aria-describedby="emailHelp" placeholder="Enter NickName" /></div>
                                            <div class="form-group"><label class="small mb-1" for="inputEmailAddress">Email</label><input class="form-control py-4" name="emailaddress" id="inputEmailAddress" type="email" aria-describedby="emailHelp" placeholder="Enter email address" /></div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="small mb-1" for="inputPassword">Password</label><input class="form-control py-4" name="password" id="inputPassword" type="password" placeholder="Enter password" /></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="small mb-1" for="inputConfirmPassword">Confirm Password</label><input name="confirmpassword" class="form-control py-4" id="inputConfirmPassword" type="password" placeholder="Confirm password" /></div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4 mb-0"><button name="submit" type="submit" class="btn btn-primary btn-block">Create Account</button></div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="signin.php">Have an account? Go to signin</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <?php require_once('./include/footer.php'); ?>