<?php

    insert_user();
    function insert_user(){

        //get input data 
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        //connect to database
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query('USE desertisland_db');

        //check user does not exists
        $sql = "SELECT * FROM user WHERE email = :email";

        $stmt = $pdo->prepare($sql);

		$stmt->execute([
			':email' => $email
		]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();

        if($row == null){
            //insert into database
            $sql = "INSERT INTO user (email, username, password)
                    VALUES(:email, :username, :password)";
            
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':email' => $email,
                ':username' => $username,
                ':password' => $password
            ]);
            header("Location: ../view/login_form.php");
        }else{
            echo("User exists. <a href='../view/login_form.php'>Log in ↗</a>");
        }


    }

?>