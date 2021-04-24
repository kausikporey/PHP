<?php
    $curr_page = "User Update";
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
                                    <div class="page-header-icon"><i data-feather="edit-3"></i></div>
                                    <span>Updating User</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <?php
                        if(isset($_POST['update'])){
                            $user_nickname = $_POST['user-nickname'];
                            $user_nickname_old = $_POST['user_nickname_old'];
                            $user_email = $_POST['user-email'];
                            $user_email_old = $_POST['user_email_old'];
                            //email already exist
                            $sql1 = "SELECT * FROM users WHERE user_email = :email";
                            $stmt = $pdo->prepare($sql1);
                            $stmt->execute([':email' => $user_email]);
                            $count = $stmt->rowCount();
                            if($count == 1 && $user_email_old != $user_email){
                                $email_exist = "Email Already Exist";
                            }else{
                                    //NickName already exist
                                    $sql2 = "SELECT * FROM users WHERE user_nickname = :nickname";
                                    $stmt = $pdo->prepare($sql2);
                                    $stmt->execute([':nickname' => $user_nickname]);
                                    $count = $stmt->rowCount();
                                    if($count == 1 && $user_nickname != $user_nickname_old){
                                        $nickname_exist = "NickName Already Exist";
                                    }else{
                                            date_default_timezone_set('Asia/calcutta');
                                            $user_photo = $_FILES['user-photo']['name'];
                                            $user_photo_temp = $_FILES['user-photo']['tmp_name'];
                                            move_uploaded_file($user_photo_temp,"./assets/img/{$user_photo}");
                                            $sql = "UPDATE users SET user_name=:name,user_nickname=:nickname,user_email=:email,user_photo=:photo,user_role=:role WHERE user_id=:id";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute([
                                                ':name' => $_POST['user-name'],
                                                ':nickname' => $user_nickname,
                                                ':email' => $user_email,
                                                ':photo' => $user_photo,
                                                ':role' => $_POST['user-role'],
                                                ':id' => $_POST['user-id']
                                                ]); 
                                            $sucess = "Updated Successfully";
                                            //header("Refresh:2;url=users.php");
                                        } 
                                }  
                            }
                    ?>


                    <?php
                        if(isset($_POST['edit']) || isset($_POST['update'])){
                            $sql1 = "SELECT * FROM users WHERE user_id = :id";
                            $stmt = $pdo->prepare($sql1);
                            $stmt->execute([':id' => $_POST['user-id']]);
                            $count = $stmt->rowCount();
                            if($count == 1){
                                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                $user_name = $user['user_name'];
                                $user_nickname_old = $user['user_nickname'];
                                $user_email_old = $user['user_email'];
                                $user_role = $user['user_role'];
                                $user_photo = $user['user_photo'];
                            }
                        }
                    ?>

                    <!--Start Table-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Edit User</div>
                            <div class="card-body">
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
                                <form action="user-update.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="user-name">User Name:</label>
                                        <input name="user-name" value="<?php echo $user_name; ?>" class="form-control" id="user-name" type="text" placeholder="User Name..." />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-name">User Nick Name:</label>
                                        <input name="user-nickname" value="<?php echo $user_nickname_old; ?>" class="form-control" id="user-nickname" type="text" placeholder="User Nick Name..." />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-email">User Email:</label>
                                        <input name="user-email" value="<?php echo $user_email_old; ?>" class="form-control" id="user-email" type="email" placeholder="User Email..." />
                                    </div>
                                    <div class="form-group">
                                        <label for="user-role">Role:</label>
                                        <select name="user-role"  class="form-control" id="user-role">
                                            <option value="Admin" <?php echo $user_role=='Admin'?'selected':''; ?>>Admin</option>
                                            <option value="Subscriber" <?php echo $user_role == 'Subscriber'?'selected':''; ?>>Subscriber</option>
                                        </select>
                                        <div class="form-group">
                                        <label for="post-title">Choose photo:</label>
                                        <input name="user-photo" class="form-control" id="post-title" type="file" />
                                        <img src="./assets/img/<?php echo $user_photo; ?>" height="50" width="70">
                                    </div>
                                    </div>
                                    <input name="user_email_old" value="<?php echo $user_email_old; ?>" type="hidden"/>
                                    <input name="user_nickname_old" value="<?php echo $user_nickname_old; ?>" type="hidden"/>
                                    <input name="user-id" value="<?php echo $_POST['user-id']; ?>" type="hidden"/>
                                    <button name="update" class="btn btn-primary mr-2 my-1" type="submit">Update now!</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--End Table-->
                </main>

                <?php require_once('./include/footer.php'); ?>
