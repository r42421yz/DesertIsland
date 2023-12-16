<?php 
    include('header.php');
    session_start();
    $id = $_SESSION['user_id'];
    $category = $_POST['category'];

    function  get_food_option($category, $choice, $otherChoice1, $otherChoice2){

        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->query('USE desertisland_db');

        $stmt = $pdo->prepare("SELECT * FROM food_type WHERE category_name = :category");
        $stmt->execute([':category' => $category]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        while($row = $stmt->fetch()){
            $food_name = $row['food_name'];
            if($food_name == $choice){
                echo "<option value='" . $food_name . "' selected>" . $food_name . "</option>";
            }else if($food_name == $otherChoice1 || $food_name == $otherChoice2){
                echo "<option value='" . $food_name . "' disabled>" . $food_name . "</option>";
            }else{
                echo "<option value='" . $food_name . "'>" . $food_name . "</option>";
            }
        }
    }

    function get_desertIsland($userId, $category){
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        $stmt = $pdo->prepare("SELECT * FROM desert_island WHERE user_id = :user AND category_name = :category");
        $stmt->execute([
            ':user' => $userId,
            ':category' => $category
        ]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $record = $stmt->fetch();

        $c1 = $record['first_choice'];
        $c2 = $record['second_choice'];
        $c3 = $record['third_choice'];

        echo("<div class='choice_form'>");
        echo("<form method='post' action='../model/edit_desertIsland.php'>");
        echo("<br><label>" . $category ."</label><br>");
        echo("<input type='hidden' name='category' id='category' value=" . $category . ">");

        echo("<br><label>First choice</label><br>");
        echo("<select name='selection1' id='select1' onchange='updateOptions(this, 1)'>");
        echo("<option value='' hidden>First Choice</option>");
        get_food_option($category, $c1, $c2, $c3);
        echo("</select>");

        //selection two
        echo("<br><label>Second choice</label><br>");
        echo("<select name='selection2' id='select2' onchange='updateOptions(this, 2)'>");
        echo("<option value='' hidden>Second Choice</option>");
        get_food_option($category, $c2, $c1, $c3);
        echo("</select>");

        //selection three
        echo("<br><label>Third choice</label><br>");
        echo("<select name='selection3' id='select3' onchange='updateOptions(this, 3)'>");
        echo("<option value='' hidden>Second Choice</option>");
        get_food_option($category, $c3, $c1, $c2);
        echo("</select>");

        //button
        echo("<br><input type='submit' value='Edit ↗'><br>");
        echo("</form>");
        echo("</div>");
            
        
    }

?>

<section>
    <div class="container">
    
        <h1>My Desert Island Dishes</h1>

        <?php
            get_desertIsland($id, $category);
        ?>

    </div>
</section>


<script>
///forbidden repeat selection
function updateOptions(selectedSelect, index) {

    var selects = document.querySelectorAll("select");

    if (index === 1){
        var otherSelect1 = document.getElementById("select2");
        var otherSelect2 = document.getElementById("select3");
    }else if (index === 2){
        var otherSelect1 = document.getElementById("select1");
        var otherSelect2 = document.getElementById("select3");
    }else{
        var otherSelect1 = document.getElementById("select1");
        var otherSelect2 = document.getElementById("select2");
    }

    var selectedValue = selectedSelect.value;
    var otherValue1 = otherSelect1.value;
    var otherValue2 = otherSelect2.value;

    // Reset all selects
    for (var i = 0; i < selects.length; i++) {

        var select = selects[i];
        var options = select.getElementsByTagName("option");

        // Enable or disable options based on the selected value
        for (var j = 0; j < options.length; j++) {
            var option = options[j];
            if (option.value === selectedValue && select !== selectedSelect) {
                option.disabled = true;
            }else if(option.value === otherValue1 && select !== otherSelect1){
                option.disabled = true;
            }else if(option.value === otherValue2 && select !== otherSelect2){
                option.disabled = true;
            }else{
                option.disabled = false;
            }
        }
    }
}

</script>

<?php include('footer.php')?>