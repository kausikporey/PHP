<?php 
    $curr_page="Add User";
    require_once('./include/header.php'); 
?>
    <body class="nav-fixed">
    <!-- top navigation bar -->
    <?php require_once('./include/top-navbar.php'); ?>

        <!--Side Nav-->
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php
                    $current_page = basename(__FILE__);
                    include_once('./include/left-sidebar.php'); 
                ?>
            </div>


            <div id="layoutSidenav_content">
                <main>
                    <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="edit-3"></i></div>
                                    <span>Create New User</span>
                                </h1>
                            </div>
                        </div>
                    </div>


                    <?php
                        if(isset($_POST['submit'])){
                            $user_nickname = $_POST['user-nickname'];
                            $user_email = $_POST['user-email'];
                            $user_password = $_POST['user-password'];
                            //email already exist
                            $sql1 = "SELECT * FROM users WHERE user_email = :email";
                            $stmt = $pdo->prepare($sql1);
                            $stmt->execute([':email' => $user_email]);
                            $count = $stmt->rowCount();
                            if($count != 0){
                                $email_exist = "Email Already Exist";
                            }else{
                                    //NickName already exist
                                    $sql2 = "SELECT * FROM users WHERE user_nickname = :nickname";
                                    $stmt = $pdo->prepare($sql2);
                                    $stmt->execute([':nickname' => $user_nickname]);
                                    $count = $stmt->rowCount();
                                    if($count != 0){
                                        $nickname_exist = "NickName Already Exist";
                                    }else{
                                            date_default_timezone_set('Asia/calcutta');
                                            $hash = password_hash($user_password,PASSWORD_BCRYPT,['cost' => 10]);
                                            $user_photo = $_FILES['user-photo']['name'];
                                            if(empty($user_photo)){
                                                $user_photo = 'favicon.png';
                                            }
                                            $user_photo_temp = $_FILES['user-photo']['tmp_name'];
                                            move_uploaded_file($user_photo_temp,"./assets/img/{$user_photo}");
                                            $sql = "INSERT INTO users (user_name,user_nickname,user_email,user_password,user_photo,registered_on,user_role) VALUES (:name,:nickname,:email,:password,:photo,:date,:role)";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute([
                                                ':name' => $_POST['user-name'],
                                                ':nickname' => $user_nickname,
                                                ':email' => $user_email,
                                                ':password' => $hash,
                                                ':photo' => $user_photo,
                                                ':date' => date("M d, Y").' at '.date("h:i A"),
                                                ':role' => $_POST['user-role']
                                                ]); 
                                            $sucess = "Account Created Successfully";
                                            header("Refresh:2;url=index.php");
                                        } 
                                }  
                            }
                    ?>
                    <!--Start Table-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Create New User</div>
                            <div class="card-body">
                                <form action="new-user.php" method="POST" enctype="multipart/form-data">
                                    <?php
                                        if(isset($email_exist)){
                                            echo "<p class='alert alert-danger'>{$email_exist}</p>";
                                        } 
                                        else if(isset($nickname_exist)){
                                            echo "<p class='alert alert-danger'>{$nickname_exist}</p>";
                                        }
                                        else if(isset($sucess)){
                                            echo "<p class='alert alert-success'>{$sucess}</p>";
                                        }
                                    ?>
                                    <div class="form-group">
                                        <label for="user-name">User Name:</label>
                                        <input required name="user-name" class="form-control" id="user-name" type="text" placeholder="User Name..." />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-nickname">User Nick Name:</label>
                                        <input required name="user-nickname" class="form-control" id="user-nickname" type="text" placeholder="User Nick Name..." />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-name">Password:</label>
                                        <input required name="user-password" class="form-control" id="password" type="password" placeholder="Password..." />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-email">User Email:</label>
                                        <input required name="user-email" class="form-control" id="user-email" type="email" placeholder="User Email..." />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-role">Role:</label>
                                        <select name="user-role" class="form-control" id="user-role">
                                            <option  value="Admin">Admin</option>
                                            <option value="Subscriber">Subscriber</option>
                                        </select>
                                        <div class="form-group">
                                        <label for="post-title">Choose photo:</label>
                                        <input name="user-photo" class="form-control" id="post-title" type="file" />
                                    </div>
                                    </div>
                                    <button name="submit" class="btn btn-primary mr-2 my-1" type="submit">Create now!</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--End Table-->
                </main>

                <?php require_once('./include/footer.php'); ?>