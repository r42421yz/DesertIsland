<?php
    session_start();
    include('header.php');

    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    
        $recipe_id = $_POST['recipe_id'];

        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $sql = "DELETE FROM like_recipe WHERE user_id = :user AND recipe_id = :recipe";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user' => $user_id,
            ':recipe' => $recipe_id
        ]);

        $sql = "UPDATE recipe SET likes_number = likes_number - 1 WHERE recipe_id = :recipe";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':recipe' => $recipe_id
        ]);
    }
?>