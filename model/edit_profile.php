<?php

    session_start();

    $id = $_SESSION['user_id'];

    // $avatar = $_POST['avatar'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $bio = $_POST['bio'];

    if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] == UPLOAD_ERR_OK) {
        $image_data = file_get_contents($_FILES["avatar"]["tmp_name"]);
        $image_mime_type = $_FILES["avatar"]["type"];
    }

    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->query('USE desertisland_db');
    
    $stmt = $pdo->prepare("UPDATE user
                        SET username = :username, email = :email, dob = :dob, bio = :bio, avatar = :avatar, avatar_type = :a_type
                        WHERE user_id = :id");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':dob' => $dob,
        ':bio' => $bio,
        ':id'=> $id,
        ':avatar' => $image_data,
        ':a_type' => $image_mime_type
    ]);

    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    header("Location: ../view/user_profile.php");


?>