<?php 
    include('header.php');

    //connect to database
    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');

    $sql = "SELECT * FROM tag";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
?>
<link rel="stylesheet" type="text/css" href="http://localhost/DesertIslandDishes/css/recipe_form.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
<section>
    <div class="container">
    <h1>New Recipe</h1>

    <form id="recipe_form" method="post" action="../model/add_recipe.php" enctype="multipart/form-data">
            
            <!-- DISH NAME -->
            <label>Dish name</label>
            <input type="text" name="recipe_title" id="recipe_title" placeholder="Dish Name" required><br>
            
            <!-- INGREDIENT -->
            <div id="ingredient_container">
                <label for="ingredient1">Ingredient 1:</label>
                <input type="text" name="ingredient[]" id="ingredient1" required>
                <input type="text" name="quantity[]" id="quantity1" placeholder="Quantity (e.g. 100g)" required>
            </div>
            <button type="button" onclick="addIngredient()">Add Ingredient</button><br>

            <!-- INSTRUCTION -->
            <label>Instruction</label><br>
            <input type="text" name="instruction" id="instruction" placeholder="Instruction" required><br>

            <!-- COOKING TIME -->
            <label>Estimated Cooking Time</label>
            <input type="number" name="cooking_time" id="cooking_time">min<br>

            <!-- IMAGE -->
            
            <input type="file" name="recipe_images[]" id="recipe_images" multiple accept="image/*" required>
            <label for="recipe_images" class="custom-file-input">
                <span class="camera-icon"><i class="fas fa-camera"></i></span>Add Image
            </label>
            <div id="image-preview"></div>


            <!-- TAG -->
            <label for ="tag">Tag</label>
            <select name = "tag" id="tag">
                <?php
                    while($row= $stmt->fetch()){

                        $tag = $row['tag_name'];
                         echo("<option value=" . $tag .">" . $tag . "</option>");
                    }
                ?>
            </select>

            <!-- SUBMIT BUTTON -->
            <input class="button" type="submit" value="Post ↗">
        </form>
    </div>
</section>

<script>
    let ingredientCounter = 1;
    
    document.getElementById('recipe_images').addEventListener('change', function(event){
        displayImagePreview(event);
    });
    

    function addIngredient() {
        ingredientCounter++;

        const container = document.getElementById('ingredient_container');

        const ingredientDiv = document.createElement('div');
        ingredientDiv.classList.add('ingredient-container');

        const label = document.createElement('label');
        label.setAttribute('for', 'ingredient' + ingredientCounter);
        label.textContent = 'Ingredient ' + ingredientCounter + ': ';

        const inputIngredient = document.createElement('input');
        inputIngredient.setAttribute('type', 'text');
        inputIngredient.setAttribute('name', 'ingredient[]');
        inputIngredient.setAttribute('id', 'ingredient' + ingredientCounter);
        inputIngredient.setAttribute('required', 'true');

        const inputQuantity = document.createElement('input');
        inputQuantity.setAttribute('type', 'text');
        inputQuantity.setAttribute('name', 'quantity[]');
        inputQuantity.setAttribute('id', 'quantity' + ingredientCounter);
        inputQuantity.setAttribute('placeholder', 'Quantity (e.g. 100g)');
        inputIngredient.setAttribute('required', 'true');

        ingredientDiv.appendChild(label);
        ingredientDiv.appendChild(inputIngredient);
        ingredientDiv.appendChild(inputQuantity);

        container.appendChild(ingredientDiv);
    }

    function displayImagePreview(event){
        var preview =document.getElementById('image-preview');
        preview.innerHTML = '';

        var files =event.target.files;

        for(var i = 0; i < files.length; i++){
            var file = files[i];

            if(file.type.match('image.*')){
                var reader = new FileReader();

                reader.onload = function(e){
                    var img = document.createElement('img');
                    img.src = e.target.result;

                    // var deleteButton = document.createElement('span');
                    // deleteButton.className = 'delete-button';
                    // deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
                    // deleteButton.addEventListener('click', function(){
                        // var index = Array.from(preview.children).indexOf(deleteButton);
                        // selectFiles.splice(index,1);
                        // preview.removeChild(img);
                        // preview.remove(deleteButton);
                        // updateFileInput();
                    // });
                    preview.appendChild(img);
                    // preview.appendChild(deleteButton);

                };
                reader.readAsDataURL(file);
            }
        }
        
        // function updateFileInput(){

        // }
    }

</script>

<?php include('footer.php')?>