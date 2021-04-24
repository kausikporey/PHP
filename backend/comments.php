<?php 
    $curr_page = "Comments";
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
                                    <div class="page-header-icon"><i data-feather="package"></i></div>
                                    <span>All Comments</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <?php  
                        if(isset($_GET['id']) && $_GET['id']=='all'){
                            $sql = "UPDATE comments SET com_state=:state WHERE com_state=:prev_state";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':prev_state' => 0,
                                ':state' => 1
                                ]);
                            header("Location:comments.php");    
                        }
                        else if(isset($_GET['id'])){
                            $sql = "UPDATE comments SET com_state=:state WHERE com_id=:id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':state' => 1,
                                ':id' => $_GET['id']
                                ]);
                            header("Location:comments.php");
                        }
                    ?>
                    <!--Start Table-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">All Comments</div>
                            <div class="card-body">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User Name</th>
                                                <th>User Email</th>
                                                <th>In response to</th>
                                                <th>Details</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Approve</th>
                                                <th>Decline</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                 $sql = "SELECT * FROM comments";
                                                 $stmt = $pdo->prepare($sql);
                                                 $stmt->execute();
                                                 while($comments = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                    $com_id = $comments['com_id'];
                                                    $com_details = $comments['com_details'];
                                                    $com_date = $comments['com_date'];
                                                    $com_status = $comments['com_status'];
                                                    $com_user_id = $comments['com_user_id'];
                                                    $com_post_id = $comments['com_post_id'];
                                                    //to get the user information
                                                    $sql2 = "SELECT * FROM users WHERE user_id = :id";
                                                    $stmt2 = $pdo->prepare($sql2);
                                                    $stmt2->execute([':id' => $com_user_id]);
                                                    $user = $stmt2->fetch(PDO::FETCH_ASSOC);
                                                    $com_user_name = $user['user_name'];
                                                    $com_user_email = $user['user_email'];
                                                    //to get the post title
                                                    $sql3 = "SELECT * FROM posts WHERE post_id = :id";
                                                    $stmt3 = $pdo->prepare($sql3);
                                                    $stmt3->execute([':id' => $com_post_id]);
                                                    $posts = $stmt3->fetch(PDO::FETCH_ASSOC);
                                                    $post_title = $posts['post_title'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $com_id; ?></td>
                                                        <td>
                                                            <?php echo $com_user_name; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $com_user_email; ?>
                                                        </td>
                                                        <td>
                                                            <a href="../single.php?post_id=<?php echo $com_post_id; ?>" target="_blank">
                                                                <?php echo $post_title; ?>
                                                            </a>
                                                        </td>
                                                        <td><?php echo $com_details; ?></td>
                                                        <td><?php echo $com_date; ?></td>
                                                        <td>
                                                            <div class="badge badge-<?php echo $com_status=='Approved'?'success':'warning'; ?>"><?php echo $com_status; ?></div>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                if(isset($_POST['approve'])){
                                                                    $comment_id = $_POST['comment_id'];
                                                                    $sql = "UPDATE comments SET com_status=:status WHERE com_id=:id";
                                                                    $stmt = $pdo->prepare($sql);
                                                                    $stmt->execute([
                                                                        ':status' => 'Approved',
                                                                        ':id' => $comment_id
                                                                        ]);
                                                                    header("Location:comments.php");    
                                                                }
                                                            ?>
                                                            <?php
                                                                if($com_status == "Unapproved"){ ?>
                                                                    <form action="comments.php" method="POST">
                                                                        <input type = "hidden" name="comment_id" value="<?php echo $com_id; ?>"/>
                                                                        <button type="submit" name="approve" class="btn btn-success btn-icon"><i data-feather="check"></i></button>
                                                                    </form>
                                                            <?php    
                                                                }else{ 
                                                            ?>
                                                                    <button name="approve" title="Already Approved" class="btn btn-success btn-icon"><i data-feather="check"></i></button>
                                                            <?php   
                                                                }     
                                                            ?>
                                                        </td>

                                                
                                                        <td>
                                                            <?php
                                                                if(isset($_POST['unapprove'])){
                                                                    $comment_id = $_POST['comment_id'];
                                                                    $sql = "UPDATE comments SET com_status=:status WHERE com_id=:id";
                                                                    $stmt = $pdo->prepare($sql);
                                                                    $stmt->execute([
                                                                        ':status' => 'Unapproved',
                                                                        ':id' => $comment_id
                                                                        ]);
                                                                    header("Location:comments.php");    
                                                                }
                                                            ?>
                                                            <?php
                                                                if($com_status == "Approved"){ ?>
                                                                    <form action="comments.php" method="POST">
                                                                        <input type = "hidden" name="comment_id" value="<?php echo $com_id; ?>"/>
                                                                        <button type="submit" name="unapprove" class="btn btn-red btn-icon"><i data-feather="delete"></i></button>
                                                                    </form>
                                                            <?php    
                                                                }else{ 
                                                            ?>
                                                                    <button title="Already Unapproved" name="unapprove" class="btn btn-red btn-icon"><i data-feather="delete"></i></button>
                                                            <?php   
                                                                }     
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                if(isset($_POST['delete'])){
                                                                    $comment_id = $_POST['comment_id'];
                                                                    $sql = "DELETE FROM comments WHERE com_id=:id";
                                                                    $stmt = $pdo->prepare($sql);
                                                                    $stmt->execute([
                                                                        ':id' => $comment_id
                                                                        ]);
                                                                    header("Location:comments.php");    
                                                                }
                                                            ?>
                                                            <form action="comments.php" method="POST">
                                                                <input type = "hidden" name="comment_id" value="<?php echo $com_id; ?>"/>
                                                                <button name="delete" type="submit" class="btn btn-red btn-icon"><i data-feather="trash-2"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>  
                                            <?php     
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Table-->

                </main>
                <?php require_once('./include/footer.php'); ?>