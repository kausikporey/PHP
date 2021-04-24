<?php 
    $curr_page="Profile";
    ob_start();
    include_once('./include/header.php');
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
                                    <div class="page-header-icon"><i data-feather="user"></i></div>
                                    <span>Your Profile</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <?php
                        if(isset($_POST['update'])){
                            $user_id = $_POST['user-id'];
                            $user_email = $_POST['user-email'];
                            $user_email_old = $_POST['user_email_old'];
                            $sql = "SELECT * FROM users WHERE user_email=:email";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([':email' => $user_email]);
                            $count = $stmt->rowCount();
                            if($count == 1 && $user_email != $user_email_old){
                                $email_exist = "Email Already Exist";
                            }else{
                                $user_photo = $_FILES['user-photo']['name'];
                                $user_photo_temp = $_FILES['user-photo']['tmp_name'];
                                move_uploaded_file($user_photo_temp,"./assets/img/{$user_photo}");
                                if(empty($user_photo)){
                                    $sql = "UPDATE users SET user_name=:name,user_email=:email WHERE user_id=:id";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([
                                        ':name' => $_POST['user-name'],
                                        ':email' => $user_email,
                                        ':id' => $user_id
                                        ]);
                                }else{
                                    $sql = "UPDATE users SET user_name=:name,user_email=:email,user_photo=:photo WHERE user_id=:id";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([
                                        ':name' => $_POST['user-name'],
                                        ':email' => $user_email,
                                        ':photo' => $user_photo,
                                        ':id' => $user_id
                                        ]);
                                    } 
                                    $success = "Profile Updated";
                                    //header("Refresh:2;url=profile.php");   
                                }  
                        }
                    ?>
                    <?php
                        if(isset($_COOKIE['user_id'])){
                            $user_id = base64_decode($_COOKIE['user_id']);
                        }else if($_SESSION['user_id']){
                            $user_id = $_SESSION['user_id'];
                        }else{
                            $user_id = -1;
                        }
                        $sql = "SELECT * FROM users WHERE user_id = :id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([':id' => $user_id]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        $user_name = $user['user_name'];
                        $user_email_old = $user['user_email'];
                        $user_image = $user['user_photo'];   
                    ?>
                            

                    <!--Start Table-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Profile</div>
                            <div class="card-body">
                                <?php
                                    if(isset($email_exist)){
                                        echo "<p class='alert alert-danger'>{$email_exist}</p>";
                                    } 
                                    else if(isset($success)){
                                        echo "<p class='alert alert-success'>{$success}</p>";
                                    }
                                ?>
                                <form action="profile.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="user-name">User Name:</label>
                                        <input name = "user-name" value="<?php echo $user_name; ?>" class="form-control" id="user-name" type="text" placeholder="User Name..." />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-email">User Email:</label>
                                        <input name="user_email_old" value="<?php echo $user_email_old; ?>" type="hidden"/>
                                        <input name="user-email" value="<?php echo $user_email_old; ?>" class="form-control" id="user-email" type="email" placeholder="User Email..." />
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                        <label for="post-title">Choose photo:</label>
                                        <input name="user-photo" class="form-control" id="post-title" type="file" />
                                        <img src="./assets/img/<?php echo $user_image; ?>" height="70px" width = "100px" />
                                    </div>
                                    </div>
                                    <input name="user-id" value="<?php echo $user_id; ?>" type="hidden"/>
                                    <button class="btn btn-primary mr-2 my-1" name="update" type="submit">Update now!</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--End Table-->
                </main>

                <?php require_once('./include/footer.php'); ?>