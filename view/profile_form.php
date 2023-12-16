<?php 
    include('header.php');
    session_start();
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $id = $_SESSION['user_id'];

    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');

    $sql = "SELECT * FROM user WHERE user_id = :id";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();

    $dob = $row['dob'];
    $bio = $row['bio'];
    $avatar = $row['avatar'];
    $avatar_type = $row['avatar_type']
?>  

<section>
    <div class="container">

        <h1>Edit</h1>

        <form method="post" action="../model/edit_profile.php" enctype="multipart/form-data">
            <!-- edit user details -->
            
            <!-- avatar -->
            <label for="avatar">Avatar</label><br>
            <img src="data:<?php echo $avatar_type; ?>;base64,<?php echo base64_encode($avatar); ?>" alt="Avatar"><br>
            <input type="file" name="avatar" id="avatar" accept="image/*"><br>

            <!-- username -->
            <label for="username">Username</label><br>
            <input type="text" name="username" id="username" value="<?php echo $username?>" required><br>

            <!-- email -->
            <label for="email">Email</label><br>
            <input type="email" name="email" id="email" value="<?php echo $email?>" required><br>
            
            <!-- dob -->
            <label for="dob">Date of Birth</label><br>
            <input type="date" name="dob" id="dob" value="<?php echo $dob?>"><br>
            <!-- bio -->
            <label for="bio">Bio</label><br>
            <input type="text" name="bio" id="bio" value="<?php echo $bio?>"><br>

            <input type="submit" value="Edit ↗"><br>
        </form>
        
    </div>
</section>

<?php include('footer.php')?>