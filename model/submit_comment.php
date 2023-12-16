<?php
    session_start();
    include('header.php');

    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    function add_comment($recipe_id, $user_id, $text){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');


        $sql = "INSERT INTO recipe_comment(recipe_id, user_id, comment_text)
        VALUES (:r_id, :u_id, :c_text)";

        $stmt = $pdo->prepare($sql);
            
        $stmt->execute([
            ':r_id' => $recipe_id,
            ':u_id' => $user_id,
            ':c_text' => $text

        ]);
        
        echo ("<div class='comment' data-commentid='" . $pdo->lastInsertId() . "'>" . htmlspecialchars($text));
        echo ("<button class='replyButton' data-parentid='" . $pdo->lastInsertId() ."'>Reply</button>");
        echo ("</div>");

        $author = get_recipe_author($recipe_id);
        inform_author($author, $user_id, $text, $recipe_id);
    }

    function get_recipe_author($recipe_id){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $sql = "SELECT * FROM recipe WHERE recipe_id = :recipe";
        $stmt = $pdo->prepare($sql);
            
        $stmt->execute([
            ':recipe' => $recipe_id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $row['user_id'];
        return $user_id;
    }

    function inform_author($author_id, $user_id, $text, $recipe_id){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $sql = "INSERT INTO message(sender_id, receiver_id, content, comment_id)
        VALUES (:s_id, :r_id, :c_text, :c_id)";

        $stmt = $pdo->prepare($sql);
                    
        $stmt->execute([
            ':s_id' => $user_id,
            ':r_id' => $author_id,
            ':c_text' => $text,
            ':c_id' => $recipe_id
        ]);
    }   

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])){
        $commentText = $_POST['comment_text'];
        $recipe_id = $_POST['recipe_id'];
        add_comment($recipe_id, $user_id, $commentText);
    }
?>