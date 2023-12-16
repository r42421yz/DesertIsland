<?php

    $category = $_POST['categoryname'];

    //connect to database
    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');

    
    //check it is not already in the table
    $sql = "SELECT * FROM category WHERE category_name = :categoryname";
    
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':categoryname' => $category
    ]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();

    if($row == null){
        $sql = "INSERT INTO category (category_name) VALUES(:categoryname)";
        
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':categoryname' => $category
        ]);
        header("Location: ../view/category_form.php");
    }else{
        echo("Category exists. <a href ='../view/category_form.php>Add another one</a>");

    }
    

?>