<?php 

    session_start();

    if(isset($_SESSION['user_id'])){
        $userId = $_SESSION['user_id'];
    }

    if(isset($_SESSION['logged'])){
        $logged = $_SESSION['logged'];
    }

    if(!$logged){
        header("http://localhost/DesertIslandDishes/view/homepage.php");
    }

    $category = $_POST['category'];
    $choice1 = $_POST['selection1'];
    $choice2 = $_POST['selection2'];
    $choice3 = $_POST['selection3'];

    //connect to database
    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');


    // get category_id
    $stmt = $pdo->prepare("SELECT * FROM category WHERE category_name = :category");
    $stmt->execute([':category' => $category]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();
    $category_id = $row['category_id'];
 
    //if user has record of desert_island choice
    $stmt = $pdo->prepare("SELECT * FROM desert_island WHERE user_id = :user AND category_name = :category");
    $stmt->execute([':user' => $userId, ':category' => $category]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();

    if($row == null){

        $sql = "INSERT INTO desert_island (user_id, category_id, category_name, first_choice, second_choice, third_choice)
                VALUES(:user, :id, :category, :choice1, :choice2, :choice3)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user' => $userId,
            ':id' => $category_id,
            ':category' => $category,
            'choice1' => $choice1,
            'choice2' => $choice2,
            'choice3' => $choice3
        ]);
    
        add_chosen($choice1, $category);
        add_chosen($choice2, $category);
        add_chosen($choice3, $category);
    }
    else{
        $c1 = $row['first_choice'];
        $c2 = $row['second_choice'];
        $c3 = $row['third_choice'];

        if ($choice1 != $c1){
            add_chosen($choice1, $category);
            delete_chosen($c1, $category);
            update($userId, $category_id, 1, $choice1);
        }

        if ($choice2 != $c2){
            add_chosen($choice2, $category);
            delete_chosen($c2, $category);
            update($userId, $category_id, 2,  $choice2);
        }

        if ($choice3 != $c3){
            add_chosen($choice3, $category);
            delete_chosen($c3, $category);
            update($userId, $category_id, 3, $choice3);
        }

    }

    echo("<a href='http://localhost/DesertIslandDishes/view/user_profile.php'>User Profile ↗</a>");

    
    function update($user, $category, $i, $food){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        if ($i == 1){
            $sql = "UPDATE desert_island
            SET first_choice = :food
            WHERE user_id = :user AND category_id = :category";
        }else if ($i == 2){
            $sql = "UPDATE desert_island
            SET second_choice = :food
            WHERE user_id = :user AND category_id = :category";
        }else{
            $sql = "UPDATE desert_island
            SET third_choice = :food
            WHERE user_id = :user AND category_id = :category";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user' => $user,
            ':category' => $category,
            'food' => $food
        ]);
    }


    function add_chosen($food, $category){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $sql = "UPDATE food_type
        SET chosen_number = chosen_number + 1
        WHERE food_name = :foodname AND category_name = :category";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':foodname' => $food,
            ':category' => $category
        ]);
    }

    function delete_chosen($food, $category){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $sql = "UPDATE food_type
        SET chosen_number = chosen_number - 1
        WHERE food_name = :foodname AND category_name = :category";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':foodname' => $food,
            ':category' => $category
        ]);
    }


?>