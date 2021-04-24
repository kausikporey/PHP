<?php $current_page = 'Post Details'; ?>
<?php ob_start(); ?>
<?php include_once('./include/header.php') ?>
                    <?php 
                        if(isset($_GET['post_id'])){
                            $post_id = $_GET['post_id'];
                            $sql = "SELECT * FROM posts WHERE post_id = :id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([':id' => $post_id]);
                            $posts = $stmt->fetch(PDO::FETCH_ASSOC);
                            $count = $stmt->rowCount();
                            if($count == 0){
                                header("Location:index.php");
                            }
                            $post_title = $posts['post_title'];
                            $post_category_id = $posts['post_category_id'];
                            $sql1 = "SELECT categories_name FROM categories WHERE categories_id=:id";
                            $stmt = $pdo->prepare($sql1);
                            $stmt->execute([':id' => $post_category_id]);
                            $category = $stmt->fetch(PDO:: FETCH_ASSOC);
                            $post_details = $posts['post_details'];
                            $post_date = $posts['post_date'];
                            $post_author = $posts['post_author'];

                            $sql1 = "UPDATE posts SET post_views = post_views + 1 WHERE post_id = :id";
                            $stmt = $pdo->prepare($sql1);
                            $stmt->execute([':id' => $post_id]);
                            }else{
                                header("Location:index.php");
                            }
                    ?>
                    <header class="page-header page-header-dark bg-gradient-primary-to-secondary">
                        <div class="page-header-content pt-10">
                            <div class="container text-center">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">
                                        <h1 class="page-header-title mb-3"><?php echo $post_title; ?></h1>
                                        <p class="page-header-text">
                                        Category : <?php echo $category['categories_name']; ?><br>
                                        Posted By : <?php echo $post_author; ?><br>
                                        Posted On : <?php echo $post_date; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="svg-border-rounded text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor"><path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0" /></svg>
                        </div>
                    </header>
                    <section class="bg-white py-10">
                        <div class="container">
                            <!--start post content-->
                            <div>
                                <h1><?php echo $post_title; ?></h1>
                                <p class="lead"><?php echo $post_details; ?></p>
                                
                            </div>
                            <!--end post content-->

                            <!--start comment section-->
                            <div class="pt-5 col-lg-8 col-xl-9">
                                <div class="d-flex align-items-center justify-content-between flex-column flex-md-row">
                                    <h2 class="mb-0">Comments</h2>
                                </div>
                                <hr class="mb-4" />
                                <?php 
                                    if(isset($_COOKIE['user_id'])){
                                        $user_id = base64_decode($_COOKIE['user_id']);
                                    }else if(isset($_SESSION['login'])){
                                        $user_id = $_SESSION['user_id'];
                                    }
                                    if(isset($_COOKIE['user_id']) || isset($_SESSION['login'])){
                                    $sql  = "SELECT * FROM comments WHERE com_status = :status AND com_post_id = :post_id AND com_user_id = :id";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([
                                        ':status' => 'unapproved',
                                        ':post_id' => $_GET['post_id'],
                                        ':id' => $user_id
                                        ]);  
                                    while($comments = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        $date = $comments['com_date'];
                                        $details = $comments['com_details'];
                                        $user = $comments['com_user_name'];
                                        $status = $comments['com_status'];
                                        $user_id = $comments['com_user_id'];
                                         ?>
                                        <div class="card mb-5">
                                            <div class="card-header d-flex justify-content-between">
                                                <div class="mr-2 text-dark">
                                                    <?php echo $user; ?>
                                                    <div class="text-xs text-muted"> <?php echo $date; ?></div>
                                                </div>
                                                <div class="h5"><span class="badge badge-warning-soft text-warning font-weight-normal">Awaiting Response</span></div>
                                            </div>
                                            <div class="card-body">
                                                <?php echo $details; ?>
                                            </div>
                                        </div>
                                <?php }}?>
                                <?php 
                                    $sql  = "SELECT * FROM comments WHERE com_status = :status AND com_post_id = :post_id";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([
                                        ':status' => 'Approved',
                                        ':post_id' => $_GET['post_id']
                                        ]);
                                    while($comments = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        $date = $comments['com_date'];
                                        $details = $comments['com_details'];
                                        $user = $comments['com_user_name'];
                                        $status = $comments['com_status'];
                                        $user_id = $comments['com_user_id'];
                                         ?>
                                        <div class="card mb-5">
                                            <div class="card-header d-flex justify-content-between">
                                                <div class="mr-2 text-dark">
                                                    <?php echo $user; ?>
                                                    <div class="text-xs text-muted"> <?php echo $date; ?></div>
                                                </div>
                                                <div class="h5"><span class="badge badge-pill badge-success font-weight-normal">Approved</span></div>
                                            </div>
                                            <div class="card-body">
                                                <?php echo $details; ?>
                                            </div>
                                        </div>
                                <?php } ?>
                                
                                <?php 
                                  if(isset($_COOKIE['user_id']) || isset($_COOKIE['user_nickname']) || isset($_SESSION['login'])){ ?>
                                    <div class="card">
                                    <div class="card-header">Add Comment</div>
                                    <div class="card-body">
                                        <?php
                                            if(isset($_POST['submit'])){
                                                date_default_timezone_set('Asia/calcutta');
                                                $comments = $_POST['comments'];
                                                $sql = "INSERT INTO comments (com_post_id,com_details,com_user_id,com_user_name,com_date,com_status) VALUES (:post_id,:com_details,:user_id,:user_name,:com_date,:com_status)";
                                                $stmt = $pdo->prepare($sql);
                                                $sql2  = "SELECT user_name FROM users WHERE user_id = :user_id";
                                                $stmt2 = $pdo->prepare($sql2);
                                                $stmt2->execute([':user_id' => $_SESSION['user_id']]);
                                                $user = $stmt2->fetch(PDO::FETCH_ASSOC);
                                                $user_name = $user['user_name'];
                                                $post_id = $_GET['post_id'];
                                                $stmt->execute([
                                                    ':post_id' => $post_id,
                                                    ':com_details' => $comments,
                                                    ':user_id' => $_SESSION['user_id'],
                                                    ':user_name' => $user_name,
                                                    ':com_date' => date("M d, Y").' at '.date("h:i A"),
                                                    ':com_status' => 'Unapproved'
                                                ]);
                                                header("Location:single.php?post_id={$post_id}");
                                            }
                                        ?>
                                        <form action="single.php?post_id=<?php echo $_GET['post_id']; ?>" method="POST">
                                            <textarea name = "comments" placeholder="Type here..." class="form-control mb-2" rows="4"></textarea>
                                            <input type="hidden" name="post_id" value="<?php echo $_GET['post_id']; ?>"/>
                                            <button name="submit" type="submit" class="btn btn-primary btn-sm mr-2">Post Comment</button>
                                        </form>
                                    </div>
                                </div>
                                <?php }else{
                                    echo "<a href='./backend/signin.php'>Sign In to comment</a>";
                                }  
                                ?>
                            </div>
                            <!--end comment section end-->
                        </div>

                        <!--Rounded style-->
                        <div class="svg-border-rounded text-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor"><path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0" /></svg>
                        </div>
                        <!--Rounded style-->
                    </section>
                </main>
            </div>

            <!--Footer start-->
<?php include_once('./include/footer.php') ?>                 
