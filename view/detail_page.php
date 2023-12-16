<?php 
    include('header.php');

    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');

    if (isset($_GET['id'])){
        $food_id = $_GET['id'];

        $sql = "SELECT * FROM food WHERE food_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $food_id]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
    }
    
?>
<link rel="stylesheet" type="text/css" href="http://localhost/DesertIslandDishes/css/detail_page.css">

<section>
    <div class="container">
        <?php
            echo("<h1>".$result['food_name']."</h1>");
            echo("<P>".$result['description']."</p>");
        ?>

        <div class="postList">
            <!-- button for add post -->
            <button class="add-button" onclick="toggleForm()">Add a post</button>
            
            <!-- showing all posts -->
            <div id="posts-container"></div>

            <div id="post-form-overlay" onclick="toggleForm()"></div>
            <div id="post-form-container">
                <form id="post-form">
                    <textarea id="post-text" rows="4" cols="50" placeholder="Share your story..." required></textarea>
                    <br>
                    <button type="button" onclick="addPost()">Post</button>
                </form>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    var foodId = <?php echo json_encode($food_id); ?>;

    $(document).ready(function(){
        fetchPost(foodId);
    });


    function toggleForm(){
        var formContainer =document.getElementById("post-form-container");
        var overlay = document.getElementById("post-form-overlay");

        if(formContainer.style.display==='none'){
            formContainer.style.display = 'block';
            overlay.style.display = 'block';
        }else{
            formContainer.style.display = 'none';
            overlay.style.display = 'none';
        }
    }

    function addPost(){
        var content = $('#post-text').val();

        $.ajax({
            type: 'POST',
            url: '../model/add_post.php',
            data: { content: content, food_id: foodId},
            success: function (response) {
                // clear the comments container
                $('#posts-container').html('');
                // fetch and display all comments (including the new one)
                fetchPost(foodId);
                // clear the textarea
                $('#post-text').val('');
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function fetchPost(foodId){

        $.ajax({
            type:'GET',
            url:'../model/get_posts.php',
            data:{food_id:foodId},
            success:function(response){
                $('#posts-container').html(response);
            },
            error:function(error){
                console.error('Error:', error)
            }
        });
    }

</script>

<?php include('footer.php')?>