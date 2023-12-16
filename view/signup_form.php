<?php 
    include('header.php');
?>

<section>
    <div class="container">
    
        <h1>Sign up</h1>

        <form method="post" action="../model/add_user.php">
            <label>Username</label><br>
            <input type="text" name="username" id="username" placeholder="Username" required><br>

            <label>Email</label><br>
            <input type="email" name="email" id="email" placeholder="Email" required><br>

            <label>Password</label><br>
            <input type="password" name="password" id="password" placeholder="Password" required><br>

            <!-- <input type="checkbox" required>I agree to the Terms and Conditions<br> -->

            <input class="button" type="submit" value="Create account ↗">
        </form>
    </div>
</section>

<?php include('footer.php')?>