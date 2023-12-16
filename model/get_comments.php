<?php

    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');

    $recipeID = isset($_GET['recipe_id']) ? $_GET['recipe_id'] : null;
    fetchComments($recipeID);
    function fetchComments($recipe_id=null){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $sql = "SELECT * FROM recipe_comment WHERE recipe_id = :r_id AND parent_id IS NULL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":r_id"=> $recipe_id]);

        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($comments as $comment){
            $comment_id = $comment["comment_id"];
            $comment_text = $comment["comment_text"];
            $comment_at = $comment["comment_at"];
            $user_id = $comment["user_id"];

            echo '<div class="comment" data-commentid="' . $comment_id . '">';
            get_user_infor($user_id);
            echo '<p class="time">' . $comment_at . '</p>';
            echo '<p class="text">' . htmlspecialchars($comment_text) . '</p>';
            echo '<button class="replyButton" data-parentid="' . $comment_id . '">Reply</button>';
            echo '<button class="toggleRepliesButton" data-parentid="' . $comment_id . '">Show Replies</button>';
            // Fetch and display replies (recursive call)
            echo '<div class="reply" style="color:red;margin-left:50px" data-parentid="' . $comment_id . '" >';
            fetchReplies($comment_id, $recipe_id, $user_id);
            echo '</div>';
            echo '</div>';
        }
    }

    function fetchReplies($parent_id, $recipe_id, $receiver_id){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $receiver_name = get_receiver($receiver_id);

        $sql = "SELECT * FROM recipe_comment WHERE recipe_id = :r_id AND parent_id = :p_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":r_id"=> $recipe_id, ":p_id"=> $parent_id]);

        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($comments as $comment){
            $comment_id = $comment["comment_id"];
            $comment_text = $comment["comment_text"];
            $comment_at = $comment["comment_at"];
            $user_id = $comment["user_id"];

            echo '<div class="comment" data-commentid="' . $comment_id . '">';
            get_user_infor($user_id);
            echo '<p class="time">' . $comment_at . '</p>';
            echo '<p class="text">Reply to' . $receiver_name . ':' . htmlspecialchars($comment_text) . '</p>';
            echo '<button class="replyButton" data-parentid="' . $comment_id . '">Reply</button>';
            // Fetch and display replies (recursive call)
            fetchReplies($comment_id, $recipe_id, $user_id);
            echo '</div>';
        }
    }

    function get_user_infor($user_id){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');
        $sql = "SELECT * FROM user WHERE user_id = :u_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":u_id"=> $user_id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $username = $row["username"];
        $avatar = $row["avatar"];
        $avatar_type = $row["avatar_type"];
        echo "<img class='avatar' src='data:$avatar_type;base64," . base64_encode($avatar) . "' alt='recipe_image' width='25px' height='25px' style='border-radius: 100%'><br>";
        echo "<p class='username' style='color:blue'>" . $username , "</p>";
    }

    function get_receiver($user_id){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');
        $sql = "SELECT * FROM user WHERE user_id = :u_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":u_id"=> $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $username = $row["username"];
        return $username;
    }
?>