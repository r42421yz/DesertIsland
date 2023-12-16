<!-- page for adding category to category table -->
<?php 
    include('header.php');

?>

<section>
    <div class="container">
    
        <h1>Add Tag</h1>

        <form method="post" action="../model/add_tag.php">
            <label>Tag Name</label><br>
            <input type="text" name="tagname" id="tagname" placeholder="Tag Name" required><br>
            
            <label>Tag Type</label><br>
            <select name="tagtype" id="tagtype" required>
                <option value="time">Time</option>
                <option value="cuisine">Cuisine</option>
                <option value="course">Course</option>
            </select>

            <input type="submit" value="Add ↗">
        </form>
    </div>
</section>

<?php include('footer.php')?>