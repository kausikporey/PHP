<?php $current_page = 'Contact'; ?>
<?php include_once('./include/header.php') ?>
                    <header class="page-header page-header-dark bg-gradient-primary-to-secondary">
                        <div class="page-header-content pt-10">
                            <div class="container text-center">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">
                                        <h1 class="page-header-title mb-3">Contact Us</h1>
                                        <p class="page-header-text">We will back to you in a week!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="svg-border-rounded text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor"><path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0" /></svg>
                        </div>
                    </header>

                    <?php 
                        if(isset($_POST['submit'])){
                        date_default_timezone_set("Asia/calcutta");
                        $sql = "INSERT INTO messages (msg_username,msg_useremail,msg_details,msg_date) VALUES (:username,:useremail,:details,:date)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            ':username' => $_POST['name'],
                            ':useremail' => $_POST['email'],
                            ':details' => $_POST['msg'],
                            ':date' => date("M d, Y").' at '.date("h:i A")
                            ]);
                            $success = "Thank You for Contacting Us!";
                        }    
                    ?>
                    <section class="bg-white py-10">
                        <div class="container">
                            <?php
                                if(isset($success)){
                                    echo "<p class='alert alert-success'>{$success}</p>";
                                }
                            ?>    
                            <?php
                                if(isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])){
                                    if(isset($_COOKIE['user_id'])){
                                        $user_id = base64_decode($_COOKIE['user_id']);
                                    }else if($_SESSION['user_id']){
                                        $user_id = $_SESSION['user_id'];
                                    }else{
                                        $user_id = -1;
                                    }
                                    $sql = "SELECT * FROM users WHERE user_id = :id";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([':id' => $user_id]);
                                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $user_name = $user['user_name'];
                                    $user_email = $user['user_email'];  ?> 
                                    <form action="contact.php" method="POST">
                                        <div class="form-row">
                                            <div class="form-group col-md-6"><label class="text-dark" for="inputName">Name</label>
                                                <input name="name" value="<?php echo $user_name; ?>" readonly class="form-control py-4" id="inputName" type="text" placeholder="Full name" />
                                            </div>
                                            <div class="form-group col-md-6"><label class="text-dark" for="inputEmail">Email</label>
                                                <input name="email" value="<?php echo $user_email; ?>" readonly class="form-control py-4" id="inputEmail" type="email" placeholder="name@example.com" />
                                            </div>
                                        </div>
                                        <div class="form-group"><label class="text-dark" for="inputMessage">Message</label>
                                            <textarea name="msg" class="form-control py-3" id="inputMessage" type="text" placeholder="Enter your message..." rows="4"></textarea>
                                        </div>
                                        <div class="text-center">
                                            <button name="submit" class="btn btn-primary btn-marketing mt-4" type="submit">Submit Request</button>
                                        </div>
                                    </form>

                                    <!-- table start -->
                                    <div class="card-body">
                                    <div class="datatable table-responsive">
                                        <table style="width:70%;margin-left:15%;margin-right:15%;" class="table table-bordered table-hover" id="dataTable" width="50%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Your Messages</th>
                                                    <th>Reply</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sql = "SELECT * FROM messages WHERE msg_useremail=:email";
                                                    $stmt = $pdo->prepare($sql);
                                                    $stmt->execute([
                                                        ':email' => $user_email
                                                        ]);
                                                    while($messages = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                    $msg_details = $messages['msg_details'];
                                                    $msg_reply = $messages['msg_reply'];   ?>
                                                    <tr>
                                                        <td><?php echo $msg_details; ?></td>
                                                        <td><?php echo $msg_reply; ?></td>
                                                    </tr>
                                                <?php }  ?>              
                                            </tbody>
                                        </table>
                                    </div>
                            <?php }else{ ?>
                                    <a href="./backend/signin.php">Sign In to Contact Us!</a>
                            <?php } ?>        

                        </div>

                        <div class="svg-border-rounded text-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor"><path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0" /></svg>
                        </div>
                    </section>
                </main>
            </div>
            
<?php include_once('./include/footer.php') ?>                 