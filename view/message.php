<?php
    include('header.php');

    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $id = $_SESSION['user_id'];

    function get_myMessage($userId){
        $pdo = connectDatabase();
        $sql = "SELECT * FROM message WHERE receiver_id = :user";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":user"=> $userId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($messages as $message) {
            $sender_id = $message['sender_id'];
            $comment_at = $message['comment_at'];
            $content = $message['content'];
            $time = $message['created_at'];
            $read_status = $message['read_status'];

            $sender = get_sender($sender_id);
            $recipe_title = get_recipe($comment_at);
    
            echo("<div class='messages'>");
            echo '<p class="sender">' . $sender . '</p>';
            echo '<p class="where"><a href="detail_recipe.php?id=' . $comment_at . '">' . $recipe_title . '</a></p>';
            echo '<p class="time">' . $time . '</p>';
            echo '<p class="text">' . htmlspecialchars($content) . '</p>';
            echo '</div>';
        }
    }

    function get_sender($userId){
        $pdo = connectDatabase();
        $sql = "SELECT * FROM user WHERE user_id = :user";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":user"=> $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $username = $row['username'];
        return $username;
    }

    function get_recipe($comment_at){
        $pdo = connectDatabase();
        $sql = "SELECT * FROM recipe WHERE recipe_id = :comment";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":comment"=> $comment_at]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $recipe_title = $row['recipe_title'];
        return $recipe_title;
    }

    function connectDatabase(){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');
        return $pdo;
    }
?>
<section>
    <?php get_myMessage($id)?>
</section>
<?php include('footer.php')?>
