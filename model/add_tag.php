<?php

    $tag = $_POST['tagname'];
    $type = $_POST['tagtype'];

    //connect to database
    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');

    
    //check it is not already in the table
    $sql = "SELECT * FROM tag WHERE tag_name = :tagname";
    
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':tagname' => $tag
    ]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();

    if($row == null){
        $sql = "INSERT INTO tag (tag_name, tag_type) VALUES(:tagname, :tagtype)";
        
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':tagname' => $tag,
            ':tagtype' => $type
        ]);
        header("Location: ../view/tag_form.php");
    }else{
        echo("Tag exists. <a href ='../view/category_form.php>Add another one</a>");

    }
    

?>