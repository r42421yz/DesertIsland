<?php

// create database
    $pdo =  new pdo('mysql:host=localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $sql = "CREATE DATABASE IF NOT EXISTS desertisland_db";
    $pdo->exec($sql);

// create user table
    $sql = "CREATE TABLE IF NOT EXISTS user
    (
        user_id INT AUTO_INCREMENT not null,
        username VARCHAR(225) not null,
        email VARCHAR(225) not null,
        password VARCHAR(225) not null,
        dob Date,
        -- country VARCHAR(225),
        bio VARCHAR(225),
        avatar BLOB,
        avatar_type VARCHAR(225),

        PRIMARY KEY (user_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

// create category table
    $sql = "CREATE TABLE IF NOT EXISTS category
    (
        category_id INT AUTO_INCREMENT not null,
        category_name VARCHAR(225) not null,

        PRIMARY KEY (category_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

//create food table
    $sql = "CREATE TABLE IF NOT EXISTS food
    (
        food_id INT AUTO_INCREMENT not null,
        food_name VARCHAR(225) not null,
        description VARCHAR(225),

        PRIMARY KEY (food_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

    //create food_post table
    $sql = "CREATE TABLE IF NOT EXISTS food_post(
        post_id INT AUTO_INCREMENT not null,
        food_id INT not null,
        user_id VARCHAR(225) not null,
        content TEXT not null,
        post_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        PRIMARY KEY(post_id),
        FOREIGN KEY(food_id) REFERENCES food(food_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

//create food_type table
    $sql = "CREATE TABLE IF NOT EXISTS food_type
    (
        food_id INT not null,
        category_id INT not null,
        food_name VARCHAR(225) not null,
        category_name VARCHAR(225) not null,
        chosen_number INT not null,

        PRIMARY KEY (food_id, category_id),
        FOREIGN KEY (food_id) REFERENCES food(food_id),
        FOREIGN KEY (category_id) REFERENCES category(category_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

//create desert_island table
    $sql = "CREATE TABLE IF NOT EXISTS desert_island
    (
        user_id INT not null,
        category_id INT not null,
        category_name VARCHAR(225) not null,
        first_choice VARCHAR(225),
        second_choice VARCHAR(225),
        third_choice VARCHAR(225),

        PRIMARY KEY (user_id, category_id),
        FOREIGN KEY (user_id) REFERENCES user(user_id),
        FOREIGN KEY (category_id) REFERENCES category(category_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

//create recipe table
    $sql = "CREATE TABLE IF NOT EXISTS recipe
    (
        recipe_id INT AUTO_INCREMENT not null,
        recipe_title VARCHAR(225) not null,
        instruction TEXT not null,
        cooking_time INT, 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        likes_number INT DEFAULT 0,
        PRIMARY KEY (recipe_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

//create like_recipe table
$sql = "CREATE TABLE IF NOT EXISTS like_recipe
(
    user_id INT not null,
    recipe_id INT not null,
    recipe_title VARCHAR(225) not null,
    time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (user_id, recipe_id),
    FOREIGN KEY (user_id) REFERENCES user(user_id),
    FOREIGN KEY (recipe_id) REFERENCES recipe(recipe_id)
)";
$pdo->exec('USE desertisland_db');
$pdo->exec($sql);

//create ingredient table
    $sql = "CREATE TABLE IF NOT EXISTS ingredient
    (
        ingredient_id INT AUTO_INCREMENT not null,
        ingredient_name VARCHAR(225) not null,

        PRIMARY KEY (ingredient_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

//create user_recipe table
    $sql = "CREATE TABLE IF NOT EXISTS user_recipe
    (
        user_id INT not null,
        recipe_id INT not null,
        recipe_title VARCHAR(225) not null,

        PRIMARY KEY (user_id, recipe_id),
        FOREIGN KEY (user_id) REFERENCES user(user_id),
        FOREIGN KEY (recipe_id) REFERENCES recipe(recipe_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

//create recipe_ingredient table
    $sql = "CREATE TABLE IF NOT EXISTS recipe_ingredient
    (
        recipe_id INT not null,
        ingredient_id INT not null,
        recipe_title VARCHAR(225) not null,
        ingredient_name VARCHAR(225) not null,
        quantity VARCHAR(225) not null,

        PRIMARY KEY (recipe_id, ingredient_id),
        FOREIGN KEY (recipe_id) REFERENCES recipe(recipe_id),
        FOREIGN KEY (ingredient_id) REFERENCES ingredient(ingredient_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

//create recipe_image table
    $sql = "CREATE TABLE IF NOT EXISTS recipe_images
    (
        image_id INT AUTO_INCREMENT not null,
        recipe_id INT not null,
        image_data BLOB not null,
        image_type VARCHAR(225) not null,

        PRIMARY KEY (image_id, recipe_id),
        FOREIGN KEY (recipe_id) REFERENCES recipe(recipe_id)

    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

//create tag table
    $sql = "CREATE TABLE IF NOT EXISTS tag
    (
        tag_id INT AUTO_INCREMENT not null,
        tag_name VARCHAR(225),

        PRIMARY KEY (tag_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

//create recipe_tag table
    $sql = "CREATE TABLE IF NOT EXISTS recipe_tag
    (
        recipe_id INT not null,
        tag_id INT not null,
        recipe_title VARCHAR(225) not null,
        tag_name VARCHAR(225) not null,

        PRIMARY KEY (recipe_id, tag_id),
        FOREIGN KEY (recipe_id) REFERENCES recipe(recipe_id),
        FOREIGN KEY (tag_id) REFERENCES tag(tag_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

//create recipe_comment table
    $sql = "CREATE TABLE IF NOT EXISTS recipe_comment(
        comment_id INT AUTO_INCREMENT not null,
        recipe_id INT not null,
        parent_id INT,
        user_id VARCHAR(225) not null,
        comment_text VARCHAR(225) not null,
        comment_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        PRIMARY KEY(comment_id),
        FOREIGN KEY(recipe_id) REFERENCES recipe(recipe_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);

//create message table
    $sql = "CREATE TABLE IF NOT EXISTS message(
        message_id INT AUTO_INCREMENT not null,
        sender_id INT not null,
        receiver_id INT not null,
        content TEXT not null,
        comment_id INT not null,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        read_status BOOLEAN DEFAULT FALSE,

        PRIMARY KEY(message_id)
    )";
    $pdo->exec('USE desertisland_db');
    $pdo->exec($sql);
?>
