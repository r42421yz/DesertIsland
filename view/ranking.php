<?php 
    include('header.php');

    function get_category_option(){

        //connect to database
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $sql = "SELECT * FROM category";
        $stmt = $pdo->prepare($sql);
		$stmt->execute([]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        echo("<ul>");

        while($row = $stmt->fetch()){

            $category_name = $row['category_name'];
            $category_id = $row['category_id'];
            echo "<li><a href='detail_ranking.php?id=" . $category_id . "'>" . $category_name . "</a></li>";

        }
        echo("</ul>");
    }

?>

<section>
    <div class="container">、

        <form method="post" action="../model/search_food.php" >
            <input type="text" name="search_value" id="search_value">
            <input type="submit" value="Search ↗"><br>
        </form>


        <!-- <div class="sideBar">
            <ul>
                <li class="menubutton">Ranking1</li>
                <li class="menubutton">Ranking2</li>
            </ul>
        </div> -->
    
        <div class="ranking">
            <h1>DesertIsland Ranking</h1>
            <p>Ranking for ...</p>
            <?php get_category_option() ?>
        </div>

    </div>
</section>

<script>
    const menuButtons = document.querySelectorAll('.menubutton');
</script>

<?php include('footer.php')?>