<?php
    session_start();
    include('header.php');

    // get user details
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $id = $_SESSION['user_id'];

    //connect to database
    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');


    //get input
    $title = $_POST['recipe_title'];
    $instruction = $_POST['instruction'];
    $time = $_POST['cooking_time'];
    $tag = $_POST['tag'];

    //insert into recipe table
    $sql = "INSERT INTO recipe (recipe_title, instruction, cooking_time, user_id)
            VALUES (:title, :instruction, :cooking_time, :user)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':title' => $title,
        ':instruction' => $instruction,
        ':cooking_time' => $time,
        ':user' => $id
    ]);

    $recipe_id = $pdo->lastInsertId();

    //insert into recipe_tag table
    $sql = "SELECT * FROM tag WHERE tag_name = :tag";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':tag' => $tag]);
    $row = $stmt->fetch();
    $tag_id = $row['tag_id'];

    $sql = "INSERT INTO recipe_tag (recipe_id, tag_id, recipe_title, tag_name)
    VALUES (:recipe_id, :tag_id, :recipe_title, :tag_name)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':recipe_id' => $recipe_id,
        ':tag_id' => $tag_id,
        ':recipe_title' => $title,
        ':tag_name' => $tag
    ]);

    //insert into recipe_image table
    if (isset($_FILES['recipe_images']['name'][0])) {
        $fileCount = count($_FILES['recipe_images']['name']);
        
        for ($i = 0; $i < $fileCount; $i++) {
            $image_data = file_get_contents($_FILES['recipe_images']['tmp_name'][$i]);
            $image_mime_type = $_FILES['recipe_images']['type'][$i];

            $sql = "INSERT INTO recipe_images (recipe_id, image_data, image_type)
                    VALUES (:recipe_id, :image_data, :image_type)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':recipe_id' => $recipe_id,
                ':image_data' => $image_data,
                ':image_type' => $image_mime_type
            ]);
        }
    }

    // insert into ingredient table if not exist
    $ingredients = $_POST['ingredient'];
    $quantities = $_POST['quantity'];
    for ($i = 0; $i < count($ingredients); $i++){

        $ingredient = $ingredients[$i];
        $quantity = $quantities[$i];
        //insert into ingredient table
        $sql = "SELECT * FROM ingredient WHERE ingredient_name = :ingredient";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':ingredient' => $ingredient
        ]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
        
        if($row == null){
            $sql = "INSERT INTO ingredient(ingredient_name)
                    VALUES (:i_name)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':i_name' => $ingredient
            ]);

            $ingredient_id = $pdo->lastInsertId();
        }else{
            $ingredient_id = $row['ingredient_id'];
        }

        //insert into recipe_ingredient table
        $sql = "INSERT INTO recipe_ingredient(recipe_id, ingredient_id, recipe_title, ingredient_name, quantity)
                VALUES (:r_id, :i_id,:title, :i_name, :quantity)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':r_id' => $recipe_id,
            ':title' => $title,
            ':i_id' => $ingredient_id,
            ':i_name' => $ingredient,
            ':quantity' => $quantity
        ]);
        header("Location: ../view/detail_recipe.php?id=" . $recipe_id);    

    }


?>