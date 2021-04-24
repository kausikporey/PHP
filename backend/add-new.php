<?php 
    $curr_page ="All New Post";
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
                                    <span>Try Creating New Post</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <?php
                        if(isset($_POST['submit'])){
                            if(isset($_COOKIE['user_id'])){
                                $user_name = base64_decode($_COOKIE['user_name']);
                            }else if($_SESSION['user_id']){
                                $user_name = $_SESSION['user_name'];
                            }else{
                                $user_name = 'Unknown';
                            }
                            date_default_timezone_set('Asia/calcutta');
                            $post_photo = $_FILES['post_photo']['name'];
                            if(empty($post_photo)){
                                $post_photo = 'wallpaper5.jpg';
                            }
                            $user_photo_temp = $_FILES['post_photo']['tmp_name'];
                            move_uploaded_file($user_photo_temp,"../img/{$post_photo}");
                            $sql = "INSERT INTO  posts (post_title,post_details,post_category_id,post_image,post_date,post_status,post_author,post_tags) VALUES (:title,:details,:category_id,:image,:date,:status,:author,:tags)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':title' => $_POST['post_title'],
                                ':details' => $_POST['post_details'],
                                ':category_id' => $_POST['category_id'],
                                ':image' => $post_photo,
                                ':date' => date("M d,Y").' at '.date("h:i A"),
                                ':status' => $_POST['post_status'],
                                ':author' => $user_name,
                                ':tags' => $_POST['post_tags']
                            ]);
                            if($_POST['post_status'] == 'Published'){
                                $sql2 = "SELECT categories_total_posts FROM categories WHERE categories_id = :cat_id";
                                $stmt2 = $pdo->prepare($sql2);
                                $stmt2->execute([
                                    ':cat_id' => $_POST['category_id']
                                ]);
                                $category = $stmt2->fetch(PDO::FETCH_ASSOC);
                                $total_post = $category['categories_total_posts'];
                                $sql3 = "UPDATE categories SET categories_total_posts = :cat_total_post WHERE categories_id = :cat_id";
                                $stmt3 = $pdo->prepare($sql3);
                                $stmt3->execute([
                                    ':cat_total_post' => $total_post+1,
                                    ':cat_id' => $_POST['category_id']
                                ]);
                            }
                            $success = "Post Inserted";
                            header("Refresh:2;url=add-new.php");
                        }
                    ?>
                    <!--Start Form-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Create New Post</div>
                            <div class="card-body">
                                <?php
                                    if(isset($success)){
                                        echo "<p class='alert alert-success'>{$success}</p>";
                                    }
                                ?>
                                <form action="add-new.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="post-title">Post Title:</label>
                                        <input name="post_title" class="form-control" id="post-title" type="text" placeholder="Post title ..." />
                                    </div>
                                    <div class="form-group">
                                        <label for="post-status">Post Status:</label>
                                        <select name="post_status" class="form-control" id="post-status">
                                            <option value="Published">Published</option>
                                            <option value="Draft">Draft</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="select-category">Select Category:</label>
                                        <select name="category_id" class="form-control" id="select-category">
                                            <?php 
                                                $sql = "SELECT * FROM categories WHERE categories_status=:status";
                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute([':status' => 'Published']);
                                                while($category=$stmt->fetch(PDO::FETCH_ASSOC)){
                                                    echo '<option value='.$category['categories_id'].'>'.$category['categories_name'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="post-title">Choose photo:</label>
                                        <input name="post_photo" class="form-control" id="post-title" type="file" />
                                    </div>

                                    <div class="form-group">
                                        <label for="post-content">Post Details:</label>
                                        <textarea name="post_details" class="form-control" placeholder="Type here..." id="post-content" rows="9"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="post-tags">Post Tags:</label>
                                        <textarea name="post_tags" class="form-control" placeholder="Tags..." id="post-tags" rows="3"></textarea>
                                    </div>
                                    <button name="submit" class="btn btn-primary mr-2 my-1" type="submit">Submit now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--End Form-->

                </main>

                <?php require_once('./include/footer.php'); ?>
