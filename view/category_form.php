<!-- page for adding category to category table -->
<?php 
    include('header.php');

?>

<section>
    <div class="container">
    
        <h1>Add Category</h1>

        <form method="post" action="../model/add_category.php">
            <label>Category Name</label><br>
            <input type="text" name="categoryname" id="categoryname" placeholder="Category Name" required><br>
            <input type="submit" value="Add ↗">
        </form>
    </div>
</section>

<?php include('footer.php')?>