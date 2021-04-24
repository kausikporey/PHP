<?php 
    $curr_page ="All Post";
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
                                    <div class="page-header-icon"><i data-feather="layout"></i></div>
                                    <span>All Posts</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <?php
                        if(isset($_POST['delete'])){
                            if($_POST['post_status'] == 'Published'){
                                //decrease category_total_post from categories table
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
                                    ':cat_total_post' => $total_post-1,
                                    ':cat_id' => $_POST['post_category_id']
                                ]);
                            }    
                            //delete all comments related to post
                            $sql2 = "DELETE FROM comments WHERE com_post_id=:com_post_id";
                            $stmt2 = $pdo->prepare($sql2);
                            $stmt2->execute([':com_post_id' => $_POST['post_id']]); 
                            //delete the post
                            $sql = "DELETE FROM posts WHERE post_id=:id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([':id' => $_POST['post_id']]); 
                        }    
                    ?>
                    <!--Start Table-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">All Posts</div>
                            <div class="card-body">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Category</th>
                                                <th>Author</th>
                                                <th>Image</th>
                                                <th>Date</th>
                                                <th>Details</th>
                                                <th>Tags</th>
                                                <th>Comments</th>
                                                <th>Views</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $sql = "SELECT * FROM posts ORDER BY post_id DESC";
                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute();
                                                while($posts = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                    $post_id = $posts['post_id'];
                                                    $post_title = $posts['post_title'];
                                                    $post_views = $posts['post_views'];
                                                    $post_image = $posts['post_image'];
                                                    $post_date = $posts['post_date'];
                                                    $post_status = $posts['post_status'];
                                                    $post_author = $posts['post_author'];
                                                    $post_details = substr($posts['post_details'],0,150);
                                                    $post_comments = $posts['post_comment_count'];
                                                    $post_tags = $posts['post_tags'];
                                                    $post_category_id = $posts['post_category_id'];
                                                    $sql2 = "SELECT categories_name FROM categories WHERE categories_id = :id";
                                                    $stmt2 = $pdo->prepare($sql2);
                                                    $stmt2->execute([':id' => $post_category_id]);
                                                    $category = $stmt2->fetch(PDO::FETCH_ASSOC);
                                                    $post_category = $category['categories_name']; ?>
                                            <tr>
                                                <td><?php echo $post_id; ?></td>
                                                <td>
                                                    <a href="../single.php?post_id=<?php echo $post_id; ?>" target="_blank"><?php echo $post_title; ?></a>
                                                </td>
                                                <td>
                                                    <div class="badge badge-<?php echo $post_status=='Published'?'success':'warning'; ?>"><?php echo $post_status; ?></div>
                                                </td>
                                                <td><?php echo $post_category; ?></td>
                                                <td><?php echo $post_author; ?></td>
                                                <td><img src="../img/<?php echo $post_image; ?>" width="70" height="50"></td>
                                                <td><?php echo $post_date; ?></td>
                                                <td><?php echo $post_details; ?></td>
                                                <td><?php echo $post_tags; ?></td>
                                                <td>
                                                    <a href="comments.php"><?php echo $post_comments; ?></a>
                                                </td>
                                                <td><?php echo $post_views; ?></td>
                                                <td>
                                                    <form action="edit-post.php" method="POST">
                                                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>" >
                                                        <button name="edit" type="submit" class="btn btn-blue btn-icon"><i data-feather="edit"></i></button>
                                                    </form>    
                                                </td>
                                                <td>
                                                    <form action="all-post.php" method="POST">
                                                        <input type="hidden" name="post_category_id" value="<?php echo $post_category_id; ?>" >
                                                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>" >
                                                        <input type="hidden" name="post_status" value="<?php echo $post_status; ?>" >
                                                        <button name="delete" type="submit" class="btn btn-red btn-icon"><i data-feather="trash-2"></i></button>
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