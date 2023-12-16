<?php 

    // $emailErr = "";
    // $passwordErr = "";

    session_start();
    if ($_SERVER['REQUEST_METHOD'] == "POST"){

        // get input from login form
        $email = $_POST['email'];
        $password = $_POST['password'];
        validate($email, $password);
    }

    function validate($email, $password){
        //connect to database
        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->query('USE desertisland_db');
        
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        if($result != null){
            if($password == $result['password']){
                $_SESSION['user_id'] = $result['user_id'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['email'] = $result['email'];
                $_SESSION['logged'] = true;
                header("Location: ../view/homepage.php");
                exit();
            }else{
                echo("Wrong password.<a href='../view/login_form.php'>Try again!</a>");
            }
        }else{
            echo("User not found.<a href='../view/login_form.php'>Try again!</a>");
        }

    }


?>