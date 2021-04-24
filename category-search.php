<?php $current_page = 'Search Result'; ?>
<?php include_once('./include/header.php') ?>

                    <?php
                        if(isset($_POST['search-keyword'])){
                                    $url = "./category-search.php?key=".$_POST['search-keyword']."&categories_id=".$_POST['categories_id'];
                                    header("Location:{$url}");
                                }
                    ?>

                    <?php 
                        if(isset($_GET['key'])){
                            $keyword  = $_GET['key'];
                            $categories_id = $_GET['categories_id'];
                            $sql = "SELECT * FROM posts WHERE post_status = :status AND post_title LIKE :title AND post_category_id = :id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':status' => "Published",
                                ':title' =>'%'.trim($keyword).'%',
                                ':id' => $categories_id
                                ]);
                            $count = $stmt->rowCount();
                            if($count == 0){
                                $post_found = 0;
                            }else{
                                $post_found = $count;
                            }
                        }
                    ?>
                    <header class="page-header page-header-dark bg-gradient-primary-to-secondary">
                        <div class="page-header-content pt-10">
                            <div class="container text-center">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">
                                        <h1 class="page-header-title mb-3">Search result for "<?php echo $keyword; ?>"<br><?php echo $post_found; ?></h1>
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
                            
                            <h1>Search Result:</h1>
                            <?php
                                $sql = "SELECT * FROM posts WHERE post_status = :status AND post_title LIKE :title AND post_category_id = :id";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([
                                    ':status' => "Published",
                                    ':title' =>'%'.trim($keyword).'%',
                                    ':id' => $categories_id
                                    ]);
                                 $post_count = $stmt->rowCount();
                                 $post_per_page = 3;
                                 if(isset($_GET['page'])){
                                     $page = $_GET['page'];
                                     if($page == 1){
                                         $page_id = 0;
                                     }else{
                                         $page_id = ($page * $post_per_page) - $post_per_page;
                                     }
                                 }else{
                                     $page = 1;
                                     $page_id = 0;
                                 }
                                 $total_pager = ceil($post_count/$post_per_page);
                            ?>
                            <hr />
                            <div class="row">
                                <?php
                                    if(isset($_GET['key'])){
                                        $keyword  = $_GET['key'];
                                        $sql = "SELECT * FROM posts WHERE post_status = :status AND post_title LIKE :title AND post_category_id = :id LIMIT $page_id,$post_per_page";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute([
                                            ':status' => "Published",
                                            ':title' =>'%'.trim($keyword).'%',
                                            ':id' => $categories_id
                                            ]);
                                        $count = $stmt->rowCount();
                                        if($count == 0){
                                            echo "No Post Found! Try Again.";
                                        }else{
                                            while($posts = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                $post_title = $posts['post_title'];
                                                $post_details = substr($posts['post_details'],0,100);
                                                $post_author = $posts['post_author'];
                                                $post_image = $posts['post_image'];
                                                $post_date = $posts['post_date'];
                                                $post_views = $posts['post_views']; 
                                                $post_id = $posts['post_id']; 
                                                $post_image = $posts['post_image'];
                                                $post_author_image = $posts['post_author_image'];
                                                ?>

                                                <div class="col-md-6 col-xl-4 mb-5">
                                                    <a class="card post-preview lift h-100" href="single.php?post_id=<?php echo $post_id; ?>"
                                                        ><img class="card-img-top" src="./img/<?php echo $post_image; ?>" alt="..." />
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?php echo $post_title; ?></h5>
                                                            <p class="card-text"><?php echo $post_details; ?></p>
                                                        </div>
                                                        <div class="card-footer">
                                                            <div class="post-preview-meta">
                                                                <img class="post-preview-meta-img" src="./img/<?php echo $post_author_image; ?>" />
                                                                <div class="post-preview-meta-details">
                                                                    <div class="post-preview-meta-details-name"><?php echo $post_author; ?></div>
                                                                    <div class="post-preview-meta-details-date"><?php echo $post_date; ?></div>
                                                                </div>
                                                            </div>
                                                        </div></a>
                                                </div>
                                            <?php }
                                        }
                                    }
                                ?>
                            </div>


                        <!-- pagination implementation -->
                            <?php
                                if($post_count > $post_per_page){ ?>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination pagination-blog justify-content-center">
                                        <?php
                                            if(isset($_GET['page'])){
                                                $previous = $_GET['page']-1;
                                            }else{
                                                $previous = 0;
                                            }
                                            if($previous<=0){
                                                echo '<li class="page-item disabled">
                                                        <a class="page-link disabled" href="#!" aria-label="Previous"><span aria-hidden="true">&#xAB;</span></a>
                                                      </li>';
                                            }else{
                                                echo '<li class="page-item">
                                                        <a class="page-link" href="category-search.php?key='.$_GET['key'].'&categories_id='.$_GET['categories_id'].'&page='.$previous.'" aria-label="Previous"><span aria-hidden="true">&#xAB;</span></a>
                                                     </li>';
                                            }
                                        ?>



                                            <?php
                                            if(isset($_GET['page'])){
                                                $active = $_GET['page'];
                                            }else{
                                                $active = 1;
                                            }
                                                for($i = 1;$i <= $total_pager; $i++){
                                                    if($i == $active){
                                                        echo '<li class="page-item active"><a class="page-link" href="category-search.php?key='.$_GET['key'].'&categories_id='.$_GET['categories_id'].'&page='.$i.'">'.$i.'</a></li>';
                                                    }else{
                                                        echo '<li class="page-item"><a class="page-link" href="category-search.php?key='.$_GET['key'].'&categories_id='.$_GET['categories_id'].'&page='.$i.'">'.$i.'</a></li>';
                                                    }
                                                }
                                            ?>

                                            <?php 
                                                if(isset($_GET['page'])){
                                                    $next = $_GET['page'] + 1;
                                                }else{
                                                    $next = 2;
                                                }

                                                if($next-1 >= $total_pager){
                                                    echo '<li class="page-item disabled">
                                                            <a class="page-link disabled" href="" aria-label="Next"><span aria-hidden="true">&#xBB;</span></a>
                                                          </li>';
                                                }else{
                                                    echo '<li class="page-item">
                                                            <a class="page-link" href="category-search.php?key='.$_GET['key'].'&categories_id='.$_GET['categories_id'].'&page='.$next.'" aria-label="Next"><span aria-hidden="true">&#xBB;</span></a>
                                                          </li>';
                                                }
                                            ?>
                                        </ul>
                                    </nav>
                            <?php } ?>

                        </div>

                        <div class="svg-border-rounded text-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor"><path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0" /></svg>
                        </div>
                    </section>
                </main>
            </div>
       
<?php include_once('./include/footer.php') ?>                 