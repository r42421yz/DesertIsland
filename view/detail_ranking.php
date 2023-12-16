<?php 
    include('header.php');

    $pdo = new pdo('mysql:localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query('USE desertisland_db');

    if (isset($_GET['id'])){
        $category_id = $_GET['id'];

        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        //get type name
        $sql = "SELECT * FROM category WHERE category_id= :category";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':category' => $category_id
        ]);

        $result = $stmt->fetch();
        $category_name = $result['category_name'];

        //get ranking
        $sql = "SELECT * FROM food_type WHERE category_id= :category ORDER BY chosen_number DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':category' => $category_id
        ]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        echo("<table border='1'>");
        echo("<tr><th>Name</th><th>Chosen_number</th></tr>");

        while($row = $stmt->fetch()){

            $food = $row['food_name'];
            $food_id = $row['food_id'];
            $chosen = $row['chosen_number'];
            echo("<tr><td><a href='detail_page.php?id=" . $food_id . "'>$food</a></td><td>$chosen</td></tr>");
        }
        echo("</table>");
    }
    
?>

<section>
    <div class="container">
        <?php
        
        echo("<h1>".$result['food_name']."</h1>");
        echo("<P>".$result['description']."</p>");
        
        ?>
    </div>
</section>

<?php include('footer.php')?>