<?php

    insert_food();
    function insert_food(){
    
        $foodname = $_POST['foodname'];
        $category = $_POST['category'];

        //connect to database
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        //check it is not already in the table
        $sql = "SELECT * FROM food WHERE food_name = :foodname";
        
        $stmt = $pdo->prepare($sql);

		$stmt->execute([
			':foodname' => $foodname
		]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();

        if($row == null){
            //insert new food in to table
            $sql = "INSERT INTO food (food_name) VALUES(:foodname)";
            
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':foodname' => $foodname
            ]);

            
            //get food id
            $sql = "SELECT * FROM food WHERE food_name = :foodname";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':foodname' => $foodname
            ]);
    
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();
            $food_id = $row['food_id'];

            //get category id 
            $sql = "SELECT * FROM category WHERE category_name = :categoryname";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':categoryname' => $category
            ]);
    
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();
            $category_id = $row['category_id'];

            //add to food_type table
            $sql = "INSERT INTO food_type (food_id, category_id, food_name, category_name, chosen_number)
                    VALUES(:food_id, :category_id, :food_name, :category_name, :chosen)";
            
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':food_id' => $food_id,
                ':category_id' => $category_id,
                ':food_name' => $foodname,
                ':category_name' => $category,
                ':chosen' => 0
            ]);
            header("Location: ../view/food_form.php");

        }else{
            echo("Food exists. <a href ='../view/food_form.php>Add another one</a>");
        }


    }

?>