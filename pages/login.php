<?php
/**
 * Created by PhpStorm.
 * User: geekscript
 * Date: 3/4/19
 * Time: 3:03 PM
 */
require_once "../config/database.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Title</title>

    <link rel="stylesheet" type="text/css" href="../fonts/roboto/roboto.css">
    <link rel="stylesheet" type="text/css" href="../fonts/material/material-icons.css">

    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
<div class="container">

    <!-- CONTENT HERE -->

    <div class="login">
        <div class="logo">
            <h1 class="text-center page-header">Farmsol</h1>
        </div>
        <form action="" method="post" class="form-horizontal" role="form">
            <?php
                if (isset($_POST['submit'])){
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    if (empty($email)){
                        echo "<div class='alert alert-info'>Please enter an email address</div>";
                    }elseif (empty($password)){
                        echo "<div class='alert alert-info'>Please enter password</div>";
                    }else{
                        if (!empty($email) && !empty($password)){
                            $db = new  Database();
                            $pdo = $db->getConnection();

                            $stm = $pdo->prepare("SELECT * FROM `users` WHERE `email`=?");
                            $stm -> execute(array($email));
                            $results = $stm->fetch();
                            if (password_verify($password, $results['password'])){
                                $_SESSION['user'] = $results['email'];
                                header("Location: index.php");
                                exit;
                            }else{
                                echo "<div class='alert alert-info' >Incorrect login credentials</div>";
                            }
                        }
                    }
                }
            ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <div class="checkbox">
                <label><input type="checkbox" value="remember">Remember me</label>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-success form-control">Sign In</button>
            </div>
        </form>

        <div>
            <p><a href="reset.php">Forgot password</a></p>
            <p>Don't have an account <a href="signUp.php">Sign Up</a></p>
        </div>
    </div>

</div>

<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/app.js"></script>

</body>
</html>

