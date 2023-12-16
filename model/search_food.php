<?php
    include('../view/header.php');

    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');
    $search_value = $_POST['search_value'];

    $sql = "SELECT * FROM food WHERE food_name = :food_name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":food_name"=> $search_value]);
    $row = $stmt->fetch();
    if ($row) {
        $food_id  = $row["food_id"];
        header("Location: http://localhost/DesertIslandDishes/view/detail_page.php?id=" . $food_id);
        exit();
    }else{
        echo("<p>Food not exists</p>");
        echo("<a href='http://localhost/DesertIslandDishes/view/ranking.php'>Back to Ranking</a>");
    }

    include('../view/footer.php');
?>