<!DOCTYPE html>

<?php 
    session_start();
    // $logged = false;
    // $userId = 0;
    // $email = "";
    if(isset($_SESSION['user_id'])){
        $userId = $_SESSION['user_id'];
    }
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
    }
    if(isset($_SESSION['email'])){
        $email = $_SESSION['email'];
    }
    if(isset($_SESSION['logged'])){
        $logged = $_SESSION['logged'];
    }

    $LoginPath = "http://localhost/DesertIslandDishes/view/login_form.php";
    $UserPath = "http://localhost/DesertIslandDishes/view/user_profile.php";
    $LogoutPath = "http://localhost/DesertIslandDishes/model/logout.php";
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="http://localhost/DesertIslandDishes/css/style.css">
    <title>Document</title>
</head>
<body>
    <div id="nav">
        <div id="logo"></div>
        <div id="navList">
            <a href="http://localhost/DesertIslandDishes/view/homepage.php">Home</a>
            <a href="http://localhost/DesertIslandDishes/view/recipes.php">Recipes</a>
            <a href="http://localhost/DesertIslandDishes/view/ranking.php">Ranking</a>
            <a href="<?php if(!$logged) echo($LoginPath); else echo ($UserPath)?>">User profile</a>
            <a href="<?php if(!$logged) echo($LoginPath); else echo ($UserPath)?>"><?php if(!$logged) echo("Login"); else echo ("Logout")?></a>
        </div>
    </div>
</body>
