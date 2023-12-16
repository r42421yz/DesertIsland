<!-- filter not finished!! -->
<?php 
    include('header.php');

    if(isset($_SESSION['logged'])){
        $logged = $_SESSION['logged'];
    $LoginPath = "http://localhost/DesertIslandDishes/view/login_form.php";
    $RecipePath = "http://localhost/DesertIslandDishes/view/recipe_form.php";
    }

    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->query('USE desertisland_db');

    $stmt = $pdo->prepare("SELECT * FROM recipe");
    $stmt->execute([]);

    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<link rel="stylesheet" type="text/css" href="http://localhost/DesertIslandDishes/css/recipes.css">


<section>
    <div class="container">

        <form method="post" action="../model/search.php" >
            <input type="text" name="search_value" id="search_value">
            <input type="submit" value="Search ↗"><br>
        </form>

        <a href="<?php if(!$logged) echo($LoginPath); else echo ($RecipePath)?>">Add recipe</a><br>
        <?php

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
        ?>

    </div>
</section>

<?php include('footer.php')?>