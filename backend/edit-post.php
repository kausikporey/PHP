<?php 
    $curr_page ="Edit Post";
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
                                    <span>Try Updating Post</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <?php
                        if(isset($_POST['update'])){
                            //update categories table
                            if($_POST['post_status_old'] == 'Draft' && $_POST['post_status'] == 'Published'){
                                $sql2 = "SELECT categories_total_posts FROM categories WHERE categories_id = :cat_id";
                                $stmt2 = $pdo->prepare($sql2);
                                $stmt2->execute([
                                    ':cat_id' => $_POST['post_category_id']
                                ]);
                                $category = $stmt2->fetch(PDO::FETCH_ASSOC);
                                $total_post = $category['categories_total_posts'];
                                $sql3 = "UPDATE categories SET categories_total_posts = :cat_total_post WHERE categories_id = :cat_id";
                                $stmt3 = $pdo->prepare($sql3);
                                $stmt3->execute([
                                    ':cat_total_post' => $total_post+1,
                                    ':cat_id' => $_POST['post_category_id']
                                ]);
                            }
                            //update the post
                            if(isset($_COOKIE['user_id'])){
                                $user_name = base64_decode($_COOKIE['user_name']);
                            }else if($_SESSION['user_id']){
                                $user_name = $_SESSION['user_name'];
                            }else{
                                $user_name = 'Unknown';
                            }
                            date_default_timezone_set('Asia/calcutta');
                            $post_photo = $_FILES['post_photo']['name'];
                            $post_photo_temp = $_FILES['post_photo']['tmp_name'];
                            if(empty($post_photo)){
                                $post_photo = $_POST['post_photo_old'];
                            }else{
                                move_uploaded_file($post_photo_temp,"../img/{$post_photo}");
                            }
                            $sql = "UPDATE posts SET post_title=:title,post_details=:details,post_category_id=:category_id,post_image=:image,post_status=:status,post_tags=:tags WHERE post_id = :id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':id' => $_POST['post_id'],
                                ':title' => $_POST['post_title'],
                                ':details' => $_POST['post_details'],
                                ':category_id' => $_POST['post_category'],
                                ':image' => $post_photo,
                                ':status' => $_POST['post_status'],
                                ':tags' => $_POST['post_tags']
                            ]);
                            $success = "Post Updated";
                        }
                    ?>

                    <?php
                        $sql = "SELECT * FROM posts WHERE post_id=:id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([':id' => $_POST['post_id']]);
                        $post = $stmt->fetch(PDO::FETCH_ASSOC);
                        $post_title = $post['post_title'];
                        $post_details = $post['post_details'];
                        $post_status = $post['post_status'];
                        $post_category_id = $post['post_category_id'];
                        $post_photo = $post['post_image'];
                        $post_tags = $post['post_tags'];
                    ?>

                    <!--Start Table-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Update Post</div>
                            <div class="card-body">
                                <?php
                                    if(isset($success)){
                                        echo "<p class='alert alert-success'>{$success}</p>";
                                    }
                                ?>
                                <form action="edit-post.php" method="POST" enctype='multipart/form-data'>
                                    <div class="form-group">
                                        <label for="post-title">Post Title:</label>
                                        <input value="<?php echo $post_title; ?>" name="post_title" class="form-control" id="post-title" type="text" placeholder="Post title ..." />
                                    </div>
                                    <div class="form-group">
                                        <label for="post-status">Post Status:</label>
                                        <select name="post_status" class="form-control" id="post-status">
                                            <option value="Published" <?php echo $post_status=='Published'?'selected':'' ?>>Published</option>
                                            <option value="Draft" <?php echo $post_status=='Draft'?'selected':'' ?>>Draft</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="select-category">Select Category:</label>
                                        <select name="post_category" class="form-control" id="select-category">
                                            <?php 
                                                $sql = "SELECT * FROM categories WHERE categories_status=:status";
                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute([':status' => 'Published']);
                                                while($category=$stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                                                    <option <?php if($category['categories_id']==$post_category_id){echo 'selected';} ?> value="<?php echo $category['categories_id']; ?>" ><?php echo $category['categories_name']; ?></option>
                                            <?php    }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="post-photo">Choose photo:</label>
                                        <input name="post_photo" class="form-control" id="post-title" type="file" />
                                        <img src="../img/<?php echo $post_photo; ?>" width="70" height="50">
                                    </div>

                                    <div class="form-group">
                                        <label for="post-content">Post Details:</label>
                                        <textarea  name="post_details" class="form-control" placeholder="Type here..." id="post-content" rows="9"><?php echo $post_details; ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="post-tags">Post Tags:</label>
                                        <textarea  name="post_tags" class="form-control" placeholder="Tags..." id="post-tags" rows="3"><?php echo $post_tags; ?></textarea>
                                    </div>
                                    <input type="hidden" name="post_status_old" value="<?php echo $post_status; ?>" >
                                    <input type="hidden" name="post_photo_old" value="<?php echo $post_photo; ?>" >
                                    <input type="hidden" name="post_category_id" value="<?php echo $post_category_id; ?>" >
                                    <input type="hidden" name="post_id" value="<?php echo $_POST['post_id']; ?>" >
                                    <button name="update" class="btn btn-primary mr-2 my-1" type="submit">Update Now!</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--End Table-->

                </main>

                <?php require_once('./include/footer.php'); ?>