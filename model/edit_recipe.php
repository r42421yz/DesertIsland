<?php

    include('header.php');
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

    }
?>

<section>
    <div class="container">
    <form id="recipe_form" method="post" action="../model/update_recipe.php" enctype="multipart/form-data">
            <!-- DISH NAME -->
            <label>Dish name</label>
            <input type="text" name="recipe_title" id="recipe_title" placeholder="Dish Name" value="<?php echo $recipe_title?>" required><br>
            
            <!-- INGREDIENT -->
            <!-- <div id="ingredient_container">
                <label for="ingredient1">Ingredient 1:</label>
                <input type="text" name="ingredient[]" id="ingredient1" required>
                <input type="text" name="quantity[]" id="quantity1" placeholder="Quantity (e.g. 100g)" required>
            </div>
            <button type="button" onclick="addIngredient()">Add Ingredient</button><br> -->

            <!-- INSTRUCTION -->
            <label>Instruction</label><br>
            <input type="text" name="instruction" id="instruction" placeholder="Instruction" value="<?php echo $instruction?>" required><br>

            <!-- COOKING TIME -->
            <label>Estimated Cooking Time</label>
            <input type="number" name="cooking_time" id="cooking_time" value="<?php echo $time?>">min<br>

            <!-- IMAGE -->
            <!-- <label for="recipe_images">Upload Images</label>
            <input type="file" name="recipe_images[]" id="recipe_images" multiple accept="image/*" required> -->
            
            <!-- TAG -->
            <!-- <label for ="tag">Tag</label> -->
            <!-- <select name = "tag" id="tag"> -->
                <?php
                    // while($row= $stmt->fetch()){

                        // $tag = $row['tag_name'];
                        //  echo("<option value=" . $tag .">" . $tag . "</option>");
                    // }
                ?>
            <!-- </select> -->
    </form>
    </div>
</section>

<?php include('footer.php')?>
