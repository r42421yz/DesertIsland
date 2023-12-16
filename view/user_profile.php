<?php 
    include('header.php');
    // get user details
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $id = $_SESSION['user_id'];

    //connect to database
    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->query('USE desertisland_db');


    $sql = "SELECT * FROM user WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $id]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();

    $bio = $row['bio'];
    $dob = $row['dob'];
    $avatar = $row['avatar'];
    $avatar_type = $row['avatar_type'];

    function get_desertIsland($userId){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $stmt = $pdo->prepare("SELECT * FROM desert_island WHERE user_id = :user");
        $stmt->execute([':user' => $userId]);
        $desertIslands = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($desertIslands as $record){
            $category = $record['category_name'];
            $c1 = $record['first_choice'];
            $c2 = $record['second_choice'];
            $c3 = $record['third_choice'];

            echo("<div class='userChoice'>");
            echo("<h2>" . $category . "</h2>");
            echo("<table border='1'>");
            echo("<tr><td>First choice</td><td>Second choice</td><td>Third choice</td></tr>");
            echo("<tr><td>".$c1."</td><td>".$c2."</td><td>".$c3."</td></tr>");
            echo("</table>");
            echo("<form method='post' action='desertIsland_form.php'>");
            echo("<input type='hidden' name='category' id='category' value=" . $category . ">");
            echo("<input type='submit' value='Edit ↗'><br>");
            echo("</form>");
            echo("</div>");
        }
    }

    function get_myRecipes($userId){
        $pdo = connectDatabase();
        $sql = "SELECT * FROM recipe WHERE user_id = :user";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":user"=> $userId]);
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($recipes as $recipe) {
            $recipe_id = $recipe['recipe_id'];
            $recipe_name = $recipe['recipe_title'];

            $stmt = $pdo->prepare("SELECT * FROM recipe_images WHERE recipe_id = :recipe_id LIMIT 1");
            $stmt->execute([':recipe_id' => $recipe_id]);
            $image = $stmt->fetch();
            $image_data = $image['image_data'];
            $image_type = $image['image_type'];

            echo("<div class='recipe'>");
            echo "<h2><a href='detail_recipe.php?id=" . $recipe_id . "'><img src='data:$image_type;base64," . base64_encode($image_data) . "' alt='recipe_image'><br>" . $recipe_name .  "</a></h2>";         
            echo("</div>");
        }
    }

    function get_likeRecipes($userId){
        $pdo = connectDatabase();
        $sql = "SELECT * FROM like_recipe WHERE user_id = :user";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":user"=> $userId]);
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($recipes as $recipe) {
            $recipe_id = $recipe['recipe_id'];
            $recipe_name = $recipe['recipe_title'];

            $stmt = $pdo->prepare("SELECT * FROM recipe_images WHERE recipe_id = :recipe_id LIMIT 1");
            $stmt->execute([':recipe_id' => $recipe_id]);
            $image = $stmt->fetch();
            $image_data = $image['image_data'];
            $image_type = $image['image_type'];

            echo("<div class='recipe'>");
            echo "<h2><a href='detail_recipe.php?id=" . $recipe_id . "'><img src='data:$image_type;base64," . base64_encode($image_data) . "' alt='recipe_image'><br>" . $recipe_name .  "</a></h2>";         
            echo("</div>");
        }
    }

    function get_myPosts($userId){
        $pdo = connectDatabase();
        $sql = "SELECT * FROM food_post WHERE user_id = :user";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":user"=> $userId]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($posts as $post) {
            $post_id = $post['post_id'];
            $content = $post['content'];
            $post_at = $post['post_at'];
    
            echo("<div class='post'>");
            echo '<p class="time">' . $post_at . '</p>';
            echo '<p class="text">' . htmlspecialchars($content) . '</p>';
            echo '</div>';
        }

    }

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

            $recipe_title = get_recipe($comment_at);
    
            echo("<div class='messages'>");
            $sender = get_sender($sender_id);
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
        $avatar = $row['avatar'];
        $avatar_type = $row['avatar_type'];
        echo "<img class='avatar' src='data:$avatar_type;base64," . base64_encode($avatar) . "' alt='recipe_image' width='25px' height='25px' style='border-radius: 100%'><br>";
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

<link rel="stylesheet" type="text/css" href="http://localhost/DesertIslandDishes/css/user_profile.css">

<section>

    <div class="container">
        <aside class="sidebar">
            <div id="userInfor">
                <img class="avatar" src="data:<?php echo $avatar_type; ?>;base64,<?php echo base64_encode($avatar); ?>" alt="Avatar"><br>
                
                <p>Username: <?php echo($username)?></p>
                <p>Email: <?php echo($email)?></p>
                <p>User ID: <?php echo($id)?></p>
                <p>Date of Birth: <?php echo($dob)?></p>
                <p>Bio: <?php echo($bio)?></p>
                <a href="profile_form.php">Edit</a>
            </div>
            <ul>
                <li onclick="changeContent('desertIsland')">My Desert Island</li>
                <li onclick="changeContent('recipes')">My Recipes</li>
                <li onclick="changeContent('likeRecipes')">My Favorites Recipes</li>
                <li onclick="changeContent('posts')">My Posts</li>
                <li onclick="changeContent('messages')">My Messages</li>

            </ul>
        </aside>


        <main class="main-content">

            <div id="desertIsland" class="content active">
                <h2>My Desert Island</h2>
                <div id="userChoice">
                    <?php get_desertIsland($id)?>
                </div> 
            </div>

            <div id="recipes" class="content">
                <h2>My Recipes</h2>
                <?php get_myRecipes($id)?>

            </div>

            <div id="likeRecipes" class="content">
                <h2>My Favorites Recipes</h2>
                <?php get_likeRecipes($id)?>

            </div>

            <div id="posts" class="content">
                <h2>My Posts</h2>
                <?php get_myPosts($id)?>
            </div>

            
            <div id="messages" class="content">
                <h2>My Messages</h2>
                <?php get_myMessage($id)?>
            </div>
            
        </main>
    </div>
</section>

<script>
    function changeContent(page){
        console.log('Changing content to:', page);
        // Hide all content divs
        var contentDivs = document.querySelectorAll('.content');
        contentDivs.forEach(function(div) {
            div.classList.remove('active');
        });

        // Show the selected content div
        var selectedContent = document.getElementById(page);
        selectedContent.classList.add('active');
    }
</script>

<?php include('footer.php')?>