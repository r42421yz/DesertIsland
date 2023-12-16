<?php 

    include('header.php');
    session_start();
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    }
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
    }
    if(isset($_SESSION['logged'])){
        $logged = $_SESSION['logged'];
    }

    // connect to database
    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');

    // get user avatar
    $sql = "SELECT * FROM user WHERE user_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $user_id]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetch();
    $avatar = $result['avatar'];
    $avatar_type = $result['avatar_type'];

    // list for recipe_images
    $images=[];

    // get recipe details
    if (isset($_GET['id'])){
        $recipe = $_GET['id'];

        $sql = "SELECT * FROM recipe WHERE recipe_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $recipe]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        $recipe_title = $result['recipe_title'];
        $instruction = $result['instruction'];
        $time = $result['cooking_time'];
        $author = $result['user_id'];
    }

    // check if user has liked the recipe
    $sql = "SELECT * FROM like_recipe WHERE user_id = :user AND recipe_id = :recipe";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":user"=> $user_id, ":recipe"=> $recipe]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    if($stmt->fetch()){
        $isChecked = true;
    }else{
        $isChecked = false;
    }

    // get recipe_ingredients
    function get_ingredient($recipe_id){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $sql = "SELECT * FROM recipe_ingredient WHERE recipe_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":id"=> $recipe_id]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        echo ("<table border=1 id='recipe_table'>");
        echo ("<tr><th>Ingredient</th><th>Quantity</th><tr>");
        while($row = $stmt->fetch()){
            echo("<tr><td>".$row['ingredient_name']."</td><td>".$row['quantity']."</td><tr>");
        }
        echo("</table>");
    }
?>

<link rel="stylesheet" type="text/css" href="http://localhost/DesertIslandDishes/css/detail_recipe.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

<section>
    <div class="container">

        <label class="heart-box">
            <input type="checkbox" id="like-box" onclick="handleLikeToggle()" <?php if ($isChecked) echo 'checked'; ?>>
            <i class="fas fa-heart"></i>
        </label>

        <div class="detail">
            <?php
                echo("<h1>".$recipe_title."</h1>");
                echo("<P>".$instruction."</p>");
                echo("<p>estimated cooking time: ".$time."min</p>");
                get_ingredient($recipe);
                $sql = "SELECT * FROM recipe_images WHERE recipe_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([":id"=> $recipe]);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $stmt->fetch()){
                    $image_data = $row["image_data"];
                    $image_type = $row["image_type"];
                    $images[] = "data:" . $image_type . ";base64," . base64_encode($image_data);
                }
                $totalImages = count($images);
                $currentImageIndex = 0;
                $currentImagePath = $images[$currentImageIndex];
                echo("<div id='recipeImages'>");
                echo("<img id='myImg' src='" . $currentImagePath . "' alt='Image'>");
                // echo("<div id='navButtons'>");
                echo("<button class='prevButton' onclick='prevImage()'><</button>");
                echo("<button class='nextButton' onclick='nextImage()'>></button>");
                // echo("</div>");
                echo("</div>");
            ?>
        </div>

        <hr/>

        <div class="comment">
            <!-- add comments -->
            <form id="commentForm" name="commentForm">
                <textarea name="comment" id="comment" rows="1" cols="30" placeholder="Post your reply"></textarea>
                <button type="button" onclick="submitComment()">Reply</button>
            </form>
            <!-- show comments -->
            <div class="commentsContainer" id="commentsContainer">
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    var recipeId = <?php echo json_encode($recipe); ?>;
    var recipeTitle = <?php echo json_encode($recipe_title); ?>;
    var logged = <?php echo json_encode($logged); ?>;

    var currentImageIndex = 0;
    var images = <?php echo json_encode($images); ?>;

    $(document).ready(function(){

        //load top-level comment
        fetchComments(recipeId);

    
        $('#commentsContainer').on('click', '.replyButton', function(){
            var parentId = $(this).data('parentid');
            showReplyForm(parentId);
        });

        $('#commentsContainer').on('click', '.cancelReplyButton', function(){
            hideReplyForm();
        });

        // Toggle show/hide replies
        // problem:default is show replies instead of hide
        $('#commentsContainer').on('click', '.toggleRepliesButton', function () {
            var parentId = $(this).data('parentid');

            // Toggle visibility of replies
            $('.reply[data-parentid="' + parentId +'"]').toggle();

            // Toggle button text
            var buttonText = $(this).text() === 'Hide Replies' ? 'Show Replies' : 'Hide Replies';
            $(this).text(buttonText);
        
        });

    });

    function handleLikeToggle() {
        var likeBox = document.getElementById("like-box");

        if (likeBox.checked) {
            addLike();
        } else {
            deleteLike();
        }
    }

    function deleteLike(){
        if (!logged) {
            alert("Please log in first.");
            document.getElementById("like-box").checked = false;
        } else {
            $.ajax({
                type: 'POST',
                url: '../model/delete_like.php',
                data: {recipe_id: recipeId},
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }
    }

    function addLike(){
        if (!logged) {
            alert("Please log in first.");
            document.getElementById("like-box").checked = false;
        } else {
            $.ajax({
                type: 'POST',
                url: '../model/add_like.php',
                data: {recipe_id: recipeId, recipe_title: recipeTitle},
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }
    }

    function submitComment() {
        // get the comment from the textarea
        var commentText = $('#comment').val();

        // make an AJAX request to submit the comment
        $.ajax({
            type: 'POST',
            url: '../model/submit_comment.php',
            data: { comment_text: commentText, recipe_id: recipeId},
            success: function (response) {
                // clear the comments container
                $('#commentsContainer').html('');
                // fetch and display all comments (including the new one)
                fetchComments(recipeId);
                // clear the textarea
                $('#comment').val('');
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });

    }

    function submitReply(parentId, replyId){
        //get the reply
        var commentText = $('#' + replyId).val();

        $.ajax({
            type: 'POST',
            url: '../model/submit_reply.php',
            data: { comment_text: commentText, recipe_id: recipeId, parent_id: parentId},
            success: function (response) {
                // clear the comments container
                $('#commentsContainer').html('');
                // fetch and display all comments (including the new one)
                fetchComments(recipeId);
                // clear the textarea
                $('#' + replyId).val('');
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function fetchComments(recipe_id){
        
        $.ajax({
            type:'GET',
            url:'../model/get_comments.php',
            data:{recipe_id:recipe_id},
            success:function(response){
                $('#commentsContainer').html(response);
            },
            error:function(error){
                console.error('Error:', error)
            }
        });
    }

    function showReplyForm(parentId){

        var replyId = 'reply_' +parentId;

        hideReplyForm();
        var replyForm = $('<div class="replySection">');
        var replyTextarea = $('<textarea name="reply" id="' + replyId + '" rows="1" cols="30" placeholder="Post your reply"></textarea>');
        var submitButton = $('<button type="button" onclick="submitReply(' + parentId + ',\'' + replyId + '\')">Reply</button>');
        var cancelButton = $('<button class="cancelReplyButton" onclick="hideReplyForm()">Cancel</button>');
    
        replyForm.append(replyTextarea);
        replyForm.append(submitButton);
        replyForm.append(cancelButton);

        $('#commentsContainer').find('[data-commentid="' + parentId + '"]').after(replyForm);
    
    }

    function hideReplyForm(){
        $('.replySection').remove();
    }

    function prevImage(){
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
        updateImage();
    }

    function nextImage(){
        currentImageIndex = (currentImageIndex + 1) % images.length;
        updateImage();
    }

    function updateImage(){
        var imageElement = document.getElementById("myImg");
        imageElement.src = images[currentImageIndex];
    }


</script>

<?php include('footer.php')?>