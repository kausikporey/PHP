<?php
    $curr_page = 'Update Category';
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
                                    <span>Update Category</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <?php
                        if(isset($_POST['submit'])){
                            date_default_timezone_set('Asia/calcutta');
                            $category_id = $_POST['id'];
                            $category_name = $_POST['category-name'];
                            $category_status = $_POST['category-status'];
                            $sql = "UPDATE categories SET categories_name=:cat_name,categories_status=:cat_status,updated_on=:updated_on WHERE categories_id = :id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':cat_name' => $category_name,
                                ':cat_status' => $category_status,
                                ':updated_on' => date("M d,Y").' at '.date("h:i A"),
                                ':id' => $category_id
                            ]); 
                            header("Location:categories.php");
                        }
                    ?>

                    <?php 
                        if(isset($_POST['edit'])){
                            $categories_id = $_POST['id'];
                            $sql = "SELECT * FROM categories WHERE categories_id = :id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([':id' => $categories_id]);
                            $categories = $stmt->fetch(PDO::FETCH_ASSOC);
                            $categories_name = $categories['categories_name'];
                            $categories_status = $categories['categories_status']; ?>
                             <!--Start Table-->
                            <div class="container-fluid mt-n10">
                                <div class="card mb-4">
                                    <div class="card-header">Update Category</div>
                                    <div class="card-body">
                                        <form action="edit-category.php" method="POST">
                                            <div class="form-group">
                                                <input type="hidden" value="<?php echo $categories_id; ?>" name = "id"/>
                                                <label for="category-name">Category Name:</label>
                                                <input name="category-name" value="<?php echo $categories_name; ?>" class="form-control" id="post-title" type="text" placeholder="Category Name..." />
                                            </div>
                                            <div class="form-group">
                                                <label for="category-status">Status:</label>
                                                <select name="category-status" class="form-control" id="post-status">
                                                    <option value="Published" <?php echo $categories_status == 'Published' ? 'selected':''; ?>>Published</option>
                                                    <option value="Draft" <?php echo $categories_status == 'Draft' ? 'selected':''; ?>>Draft</option>
                                                </select>
                                            </div>
                                            <button type="submit" name="submit" class="btn btn-primary mr-2 my-1">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--End Table-->
                    <?php
                            }else{
                                header("Location:categories.php");
                            }
                    ?>    
                </main>

                <?php require_once('./include/footer.php'); ?>