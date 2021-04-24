
<?php
    $curr_page = "New Category";
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
                                    <span>Create New Category</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <?php
                        if(isset($_POST['submit'])){
                            if(isset($_COOKIE['user_name'])){
                                $user_name = base64_decode($_COOKIE['user_name']);
                            }else if(isset($_SESSION['user_name'])){
                                $user_name = $_SESSION['user_name'];
                            }
                            date_default_timezone_set('Asia/calcutta');
                            $category_name = $_POST['category-name'];
                            $category_status = $_POST['category-status'];
                            $sql = "INSERT INTO categories (categories_name,categories_status,created_on,created_by) VALUES (:cat_name,:cat_status,:created_on,:created_by)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':cat_name' => $category_name,
                                ':cat_status' => $category_status,
                                ':created_on' => date("M d,Y").' at '.date("h:i A"),
                                ':created_by' => $user_name
                            ]);
                        }
                    ?>
                    <!--Start Table-->
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Create New Category</div>
                            <div class="card-body">
                                <form action="new-category.php" method="POST">
                                    <div class="form-group">
                                        <label for="post-title">Category Name:</label>
                                        <input name = "category-name" class="form-control" id="post-title" type="text" placeholder="Category Name..." />
                                    </div>
                                    <div class="form-group">
                                        <label for="category-status">Status:</label>
                                        <select name="category-status" class="form-control" id="post-status">
                                            <option value="Published">Published</option>
                                            <option value="Draft">Draft</option>
                                        </select>
                                    </div>
                                    <button name="submit" class="btn btn-primary mr-2 my-1" type="submit">Submit now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--End Table-->
                </main>
                <?php require_once('./include/footer.php'); ?>