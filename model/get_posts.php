<?php

$pdo = new pdo('mysql:localhost', 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->query('USE desertisland_db');

$food_id = isset($_GET['food_id']) ? $_GET['food_id'] : null;
fetchPosts($food_id);

function fetchPosts($food_id){
    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');

    $sql = "SELECT * FROM food_post WHERE food_id = :food_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':food_id' => $food_id]);

    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($posts as $post){
        $post_id = $post['post_id'];
        $user_id = $post['user_id'];
        $content = $post['content'];
        $post_at = $post['post_at'];

        echo("<div class='post'>");
        get_user_infor($user_id);
        echo '<p class="time">' . $post_at . '</p>';
        echo '<p class="text">' . htmlspecialchars($content) . '</p>';
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
?>