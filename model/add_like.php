<?php
    session_start();
    include('header.php');

    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    
        $recipe_id = $_POST['recipe_id'];
        $recipe_title = $_POST['recipe_title'];

        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $sql = "INSERT INTO like_recipe(user_id, recipe_id, recipe_title) VALUES (:user, :recipe_id, :recipe_title)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user' => $user_id,
            ':recipe_id' => $recipe_id,
            ':recipe_title' => $recipe_title
        ]);
        try{
            $sql = "UPDATE recipe SET likes_number = likes_number + 1 WHERE recipe_id = :recipe";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':recipe' => $recipe_id
            ]);
        }catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>