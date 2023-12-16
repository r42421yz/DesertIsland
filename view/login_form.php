<?php 
    include('header.php');
    // if(!isset($emailErr)){
    //     $emailErr = "";
    // }
    // if(!isset($passwordErr)){
    //     $passwordErr = "";
    // }
    
    // if(!isset($log)){
    //     $path = "../model/login.php";
    //     $signupPath = "signup_form.php";
    // }else{
    //     $path = "#";
    //     $signupPath = "../view/signup_form.php";

    // }
    // $path = "../model/login.php";
?>  

<section>
    <div class="container" id="login_form">

        <h1>Log In</h1>

        <form method="post" action="../model/login.php">

            <label for="email">Email</label><br>
            <input type="email" name="email" id="email" placeholder="Email" required><br>
            <!-- <span class="error"><?php echo($emailErr);?></span> -->

            <label for="email">Password</label><br>
            <input type="password" name="password" id="password" placeholder="Password" required><br>
            <!-- <span class="error"><?php echo($passwordErr);?></span> -->

            <input type="submit" value="Log In ↗"><br>

            <a href="#">Forget your password?</a>
        </form>

        <!-- Don't have account yet?<a href="<?php echo($signupPath) ?>">Sign up ↗</a> -->
        Don't have account yet?<a href="http://localhost/DesertIslandDishes/view/signup_form.php">Sign up ↗</a>
        
    </div>
</section>

<?php include('footer.php')?>
