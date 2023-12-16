<!-- filter not finished!! -->
<?php
    session_start();
    include('../view/header.php');

    // get tags with a specific type
    function get_tag($tag_type){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $sql = "SELECT * FROM tag WHERE tag_type = :tag_type";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":tag_type"=> $tag_type]);

        echo ("<select name = '" . $tag_type . "'  id = '" . $tag_type . "'>");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $stmt->fetch()){
            $tag = $row['tag_name'];
            echo ("<option value = '" . $tag . "'>" . $tag . "</option>");
        };
        echo ("</select>");
    }
?>
<link rel="stylesheet" type="text/css" href="http://localhost/DesertIslandDishes/css/recipes.css">

<section>
    <div class="container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

            <!-- search box -->
            <input type="text" name="search_value" id="search_value"><br>

            <!-- filter -->
            <div class = "filter_box">
                <?php
                    // get_tag("time");
                    // get_tag("course");
                    // get_tag("cuisine");
                ?>
            </div>


            <input type="submit" value="Search ↗"><br>
        </form>
    </div>
</section>

<?php
    //connect to database
    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');

    $search_value = $_POST['search_value'];
    $time = $_POST['time'];
    $course = $_POST['course'];
    $cuisine = $_POST['cuisine'];


    //search recipe title directly
    $sql = "SELECT * FROM recipe WHERE recipe_title = :recipe_title";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":recipe_title"=> $search_value]);

    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($recipes as $recipe) {
        $recipe_id = $recipe['recipe_id'];
        $recipe_name = $search_value;

        $stmt = $pdo->prepare("SELECT * FROM recipe_images WHERE recipe_id = :recipe_id LIMIT 1");
        $stmt->execute([':recipe_id' => $recipe_id]);
        $image = $stmt->fetch();
        $image_data = $image['image_data'];
        $image_type = $image['image_type'];

        echo("<div class='recipe'>");
        echo "<h2><a href='detail_recipe.php?id=" . $recipe_id . "'><img src='data:$image_type;base64," . base64_encode($image_data) . "' alt='recipe_image'><br>" . $recipe_name .  "</a></h2>";         
        echo ("</div>");
    }

    //search ingredient -> show recipe using this ingredient
    $sql = "SELECT * FROM recipe_ingredient WHERE ingredient_name = :ingredient_name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":ingredient_name"=> $search_value]);

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
        echo ("</div>");
    }

    //search tag
    $sql = "SELECT * FROM recipe_tag WHERE tag_name = :tag_name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":tag_name"=> $search_value]);
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
        echo ("</div>");
        
    }


    include('../view/footer.php');

?>
