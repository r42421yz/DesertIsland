<!-- page for adding food to food table -->
<?php 
    include('header.php');

?>

<section>
    <div class="container">
    
        <h1>Add food</h1>

        <form method="post" action="../model/add_food.php">
            <label>Name</label><br>
            <input type="text" name="foodname" id="foodname" placeholder="Foodname" required><br>

            <label>Category</label><br>
            <?php 
                $pdo = new pdo('mysql:localhost', 'root', 'root');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->query('USE desertisland_db');

                $sql = "SELECT * FROM category";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([]);

                $stmt->setFetchMode(PDO::FETCH_ASSOC);

                echo("<select name='category' id='category' required>");
                echo("<option value='' hidden>Category</option>");
                while($row = $stmt->fetch()){

                    $category = $row['category_name'];
                    echo("<option value=" . $category .">" . $category . "</option>");
                }
                echo("</select>");
            ?>

            <input type="submit" value="Add ↗">
        </form>
    </div>
</section>

<?php include('footer.php')?>