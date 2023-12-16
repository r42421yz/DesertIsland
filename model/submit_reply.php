<?php
    session_start();
    include('header.php');

    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];

    function add_comment($recipe_id, $user_id, $text, $parent_id){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $sql = "INSERT INTO recipe_comment(recipe_id, parent_id, user_id, comment_text)
        VALUES (:r_id, :p_id, :u_id, :c_text)";

        $stmt = $pdo->prepare($sql);
            
        $stmt->execute([
            ':r_id' => $recipe_id,
            'p_id' => $parent_id,
            ':u_id' => $user_id,
            ':c_text' => $text
        ]);

        echo ("<div class='comment' data-commentid='" . $pdo->lastInsertId() . "'>" . htmlspecialchars($text));
        echo ("<button class='replyButton' data-parentid='" . $pdo->lastInsertId() ."'>Reply</button>");
        echo ("</div>");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])){
        $commentText = $_POST['comment_text'];
        $recipe_id = $_POST['recipe_id'];
        // $parentID = isset($_POST['parent_id']) ? $_POST['parent_id'] : null;
        $parent_id = $_POST['parent_id'];
        add_comment($recipe_id, $user_id, $commentText, $parent_id);
    }
?>