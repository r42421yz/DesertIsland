<?php

    session_start();
    include('header.php');

    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];

    function add_post($food_id, $user_id, $content){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');
        echo("<p>hi</p>");
        $sql = "INSERT INTO food_post(food_id, user_id, content)
                VALUES (:food_id, :user_id, :content)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':food_id' => $food_id,
            ':user_id' => $user_id,
            ':content' => $content
        ]);

        echo("<div class='post'>" . htmlspecialchars($content));
        echo("</div>");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])){
        $content = $_POST['content'];
        $food_id = $_POST['food_id'];
        add_post($food_id, $user_id, $content);
    }

?>