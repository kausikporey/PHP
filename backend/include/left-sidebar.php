 <nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">

        <!-- to make the index page active status -->
            <?php
                if($current_page == 'index.php'){ ?>
                        <a class="nav-link collapsed pt-4 active" href="index.php">
                            <div class="nav-link-icon"><i data-feather="activity"></i></div>
                                Dashboard
                        </a>
            <?php    
                }else{ ?>
                <a class="nav-link collapsed pt-4" href="index.php">
                    <div class="nav-link-icon"><i data-feather="activity"></i></div>
                        Dashboard
                </a>
            <?php    
                }
            ?>

            <!-- to make the new-category page in active status -->
            <?php
                if($current_page == 'new-category.php' || $current_page == 'edit-category.php'){ ?>
                    <a class="nav-link active" href="categories.php" ><div class="nav-link-icon"><i data-feather="chevrons-up"></i></div>
                        Categories
                    </a>
            <?php 
                }else{?>
                    <a class="nav-link" href="categories.php" ><div class="nav-link-icon"><i data-feather="chevrons-up"></i></div>
                        Categories
                    </a>
            <?php 
                }
            ?>

            <?php
                if($current_page == 'all-post.php' || $current_page == 'add-new.php' || $current_page == 'edit-post.php'){ ?>
                    <a class="nav-link active" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="true" aria-controls="collapseLayouts"><div class="nav-link-icon"><i data-feather="layout"></i></div>
                        Posts
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse show" id="collapseLayouts" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            <a class="nav-link" href="all-post.php">All Posts</a>
                            <a class="nav-link" href="add-new.php">Add New Post</a>
                         </nav>
            </div>
            <?php 
                }else{?>
                    <a class="nav-link collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts"><div class="nav-link-icon"><i data-feather="layout"></i></div>
                        Posts
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            <a class="nav-link" href="all-post.php">All Posts</a>
                            <a class="nav-link" href="add-new.php">Add New Post</a>
                        </nav>
            </div>
            <?php 
                }
            ?>
            

            <?php
                if($current_page == 'new-user.php' || $current_page == 'users.php' || $current_page == 'user-update.php'){ ?>
                    <a class="nav-link active" href="users.php" ><div class="nav-link-icon"><i data-feather="users"></i></div>
                        Users
                    </a>
            <?php 
                }else{?>
                    <a class="nav-link" href="users.php" ><div class="nav-link-icon"><i data-feather="users"></i></div>
                        Users
                    </a>
            <?php 
                }
            ?>

            
            <a class="nav-link" href="comments.php" ><div class="nav-link-icon"><i data-feather="package"></i></div>
                Comments
            </a>
            
            <?php
                if($current_page == 'reply.php' || $current_page == 'messages.php'){ ?>
                    <a class="nav-link active" href="messages.php" ><div class="nav-link-icon"><i data-feather="mail"></i></div>
                        Messages
                    </a>
            <?php 
                }else{?>
                    <a class="nav-link" href="messages.php" ><div class="nav-link-icon"><i data-feather="mail"></i></div>
                        Messages
                    </a>
            <?php 
                }
            ?>

            <a class="nav-link" href="profile.php" ><div class="nav-link-icon"><i data-feather="user"></i></div>
                Profile
            </a>
        </div>
    </div>

    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title"><?php echo $user_name_bar; ?></div>
        </div>
    </div>
</nav>