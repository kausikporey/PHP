<?php 
    $curr_page ="Messages";
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
                                    <span>Messages</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <?php
                        if(isset($_POST['delete'])){
                            $sql = "DELETE FROM messages WHERE msg_id=:id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([':id' => $_POST['msg_id']]);
                        } 
                    ?>


                    <?php  
                        if(isset($_GET['id']) && $_GET['id']=='all'){
                            $sql = "UPDATE messages SET msg_state=:state WHERE msg_state=:prev_state";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':prev_state' => 0,
                                ':state' => 1
                                ]);
                            header("Location:messages.php");    
                        }
                        else if(isset($_GET['id'])){
                            $sql = "UPDATE messages SET msg_state=:state WHERE msg_id=:id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':state' => 1,
                                ':id' => $_GET['id']
                                ]);
                            header("Location:messages.php");    
                        }
                    ?>
                    <!--Start Table-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">All Messages</div>
                            <div class="card-body">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User Name</th>
                                                <th>User Email</th>
                                                <th>Message</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Response</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $sql = "SELECT * FROM messages";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute();
                                            $count = $stmt->rowCount();
                                            while($msg = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                $msg_state = $msg['msg_state'];
                                                $msg_details = $msg['msg_details'];
                                                $msg_username = $msg['msg_username'];
                                                $msg_useremail = $msg['msg_useremail'];
                                                $msg_id = $msg['msg_id'];
                                                $msg_date = $msg['msg_date'];
                                                $msg_status = $msg['msg_status']; ?>
                                            <tr>
                                                <td><?php echo $msg_id; ?></td>
                                                <td>
                                                    <?php echo $msg_username; ?>
                                                </td>
                                                <td>
                                                    <?php echo $msg_useremail; ?>
                                                </td>
                                                <td><?php echo $msg_details; ?></td>
                                                <td><?php echo $msg_date; ?></td>
                                                <td>
                                                    <div class="badge badge-<?php echo $msg_status=='Pending'?'warning':'success'; ?>">
                                                        <?php echo $msg_status; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($msg_status == 'Pending'){ ?>
                                                            <form action="reply.php" method="POST">
                                                                <input type="hidden" name="msg_id" value="<?php echo $msg_id; ?>"/> 
                                                                <input type="hidden" name="msg_username" value="<?php echo $msg_username; ?>"/>
                                                                <input type="hidden" name="msg_details" value="<?php echo $msg_details; ?>"/>
                                                                <button type="submit" name="response" class="btn btn-success btn-icon"><i data-feather="mail"></i></button>
                                                            </form>   
                                                    <?php }else{ ?>
                                                            <button title="Already Responded" name="response" class="btn btn-success btn-icon"><i data-feather="check"></i></button>
                                                    <?php } ?>
                                                     
                                                </td>

                                                <td>
                                                    <form action="messages.php" method="POST">
                                                        <input type="hidden" name="msg_id" value="<?php echo $msg_id; ?>">
                                                        <button type="submit" name="delete" class="btn btn-red btn-icon"><i data-feather="trash-2"></i></button>
                                                    </form>    
                                                </td>
                                            </tr> 
                                        <?php } ?>                         
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Table-->
                </main>

                <?php require_once('./include/footer.php'); ?>