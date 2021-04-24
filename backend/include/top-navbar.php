<nav class="topnav navbar navbar-expand shadow navbar-light bg-white" id="sidenavAccordion">
    <a class="navbar-brand d-none d-sm-block" href="index.php">Admin Panel</a><button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle" href="#"><i data-feather="menu"></i></button>
    <ul class="navbar-nav align-items-center ml-auto">
        
        <?php
            $sql = "SELECT * FROM comments WHERE com_state=:state";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':state' => 0]);
            $count = $stmt->rowCount();
        ?>    
        <!--User Registration + New Comment Notification-->
        <li class="nav-item dropdown no-caret mr-3 dropdown-notifications">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownAlerts" href="comments.php" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i data-feather="bell"></i>
                    <?php if($count > 0){ ?>
                        <span class="badge badge-red"><?php echo $count; ?></span>
                    <?php } ?>    
            </a>

            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownAlerts">
                <h6 class="dropdown-header dropdown-notifications-header">
                    <i class="mr-2" data-feather="bell"></i>
                    Notification
                </h6>
                <?php
                    while($comments=$stmt->fetch(PDO::FETCH_ASSOC)){
                        $com_id = $comments['com_id'];
                        $com_details = $comments['com_details'];
                        $com_date = $comments['com_date']; ?>
                        <a class="dropdown-item dropdown-notifications-item" href="comments.php?id=<?php echo $com_id; ?>">
                            <div class="dropdown-notifications-item-icon bg-warning"><i data-feather="activity"></i></div>
                            <div class="dropdown-notifications-item-content">

                                <div class="dropdown-notifications-item-content-details">
                                    <?php echo $com_date; ?>
                                </div>
                                <div class="dropdown-notifications-item-content-text">
                                    <?php echo $com_details; ?>
                                </div>
                            </div>
                        </a>
                <?php    }
                ?>
                <a class="dropdown-item dropdown-notifications-footer" href="comments.php?id=all">
                    View All Alerts
                </a>
            </div>
        </li>
        <!--User Registration + New Comment Notification-->
        <?php
            $sql = "SELECT * FROM messages WHERE msg_state=:state";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':state' => 0]);
            $count = $stmt->rowCount();
        ?>
        <!--Message Notification-->
        <li class="nav-item dropdown no-caret mr-3 dropdown-notifications">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownMessages" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i data-feather="mail"></i>
                    <?php
                        if($count > 0){ ?>
                                <span class="badge badge-red"><?php echo $count; ?></span>
                    <?php } ?>            
            </a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownMessages">
                <h6 class="dropdown-header dropdown-notifications-header">
                    <i class="mr-2" data-feather="mail"></i>
                    Message Notification
                </h6>

                <?php
                    while($msg = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $msg_id = $msg['msg_id'];
                        $msg_details = $msg['msg_details'];
                        $msg_username = $msg['msg_username'];
                        $msg_useremail = $msg['msg_useremail'];
                        $sql2 = "SELECT * FROM users WHERE user_email=:email";
                        $stmt2 = $pdo->prepare($sql2);
                        $stmt2->execute([':email' => $msg_useremail]);
                        $count2 = $stmt2->rowCount();
                        if($count2 == 1){
                            $user = $stmt2->fetch(PDO::FETCH_ASSOC);
                            $user_image = $user['user_photo'];
                        }
                        ?>
                        <a class="dropdown-item dropdown-notifications-item" href="messages.php?id=<?php echo $msg_id; ?>">
                            <img class="dropdown-notifications-item-img" src="./assets/img/<?php echo $user_image; ?>" />
                            <div class="dropdown-notifications-item-content">
                                <div class="dropdown-notifications-item-content-text">
                                    <?php echo $msg_details; ?>
                                </div>
                                <div class="dropdown-notifications-item-content-details">
                                    <?php echo $msg_username; ?> &#xB7; 58m
                                </div>
                            </div>
                        </a>
                <?php } ?>


                <a class="dropdown-item dropdown-notifications-footer" href="messages.php?id=all">
                    Read All Messages
                </a>
            </div>
        </li>
        <!--Message Notification-->

        <?php
            if(isset($_COOKIE['user_id'])){
                $user_id_bar = base64_decode($_COOKIE['user_id']);
            }else if($_SESSION['user_id']){
                $user_id_bar = $_SESSION['user_id'];
            }else{
                $user_id_bar = -1;
            }
            $sql = "SELECT * FROM users WHERE user_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $user_id_bar]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_name_bar = $user['user_name'];
            $user_email_bar = $user['user_email'];
            $user_image_bar = $user['user_photo'];   
        ?>

        <li class="nav-item dropdown no-caret mr-3 dropdown-user">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="img-fluid" src="./assets/img/<?php echo $user_image_bar; ?>"/></a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="./assets/img/<?php echo $user_image_bar; ?>" alt="<?php echo $user_name_bar; ?>" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name">
                            <?php echo $user_name_bar; ?>
                        </div>
                        <div class="dropdown-user-details-email">
                            <?php echo $user_email_bar; ?>
                        </div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="profile.php">
                    <div class="dropdown-item-icon">
                        <i data-feather="settings"></i>
                    </div>
                    Profile
                </a>
                <a class="dropdown-item" href="../signout.php"
                    ><div class="dropdown-item-icon">
                        <i data-feather="log-out"></i>
                    </div>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>