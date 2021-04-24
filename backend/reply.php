<?php 
    $curr_page ="Reply";
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
                                    <div class="page-header-icon"><i data-feather="mail"></i></div>
                                    <span>Reply</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <?php
                        if(isset($_POST['submit'])){
                            $sql = "UPDATE messages SET msg_reply=:reply,msg_status=:status,msg_state=:state WHERE msg_id=:id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':reply' => $_POST['msg_reply'],
                                ':status' => 'Responded',
                                ':state' => 1,
                                ':id' => $_POST['msg_id']
                                ]);
                            $count = $stmt->rowCount();
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
                    ?>

                    <!--Start Form-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Reponse Area:</div>
                            <div class="card-body">
                                <form action="reply.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="post-content">Message:</label>
                                        <textarea name="msg_details" class="form-control" placeholder="Type here..." id="post-content" rows="9" readonly="true"><?php echo $_POST['msg_details']; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="user-name">User name:</label>
                                        <input name="username" value="<?php echo $user_name; ?>" class="form-control" id="user-name" type="text" placeholder="User name ..." readonly="true" value="Md. A. Barik" />
                                    </div>                               

                                    <div class="form-group">
                                        <label for="post-tags">Reply:</label>
                                        <textarea name="msg_reply" class="form-control" placeholder="Type your reply here..." id="post-tags" rows="9"></textarea>
                                    </div>
                                    <input type="hidden" name="msg_id" value="<?php echo $_POST['msg_id']; ?>"/>
                                    <button type="submit" name="submit" class="btn btn-primary mr-2 my-1" type="button">Send my respose</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--End Form-->

                </main>

                <?php require_once('./include/footer.php'); ?>