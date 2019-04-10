<?php
/**
 * Created by PhpStorm.
 * User: geekscript
 * Date: 3/7/19
 * Time: 1:05 PM
 */
session_start();
if (!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}else{
    require_once "../config/database.php";
    $db = new Database();
    $pdo = $db->getConnection();

    $stm = $pdo->prepare("SELECT * FROM `users` WHERE `email`=?");
    $stm ->execute(array($_SESSION['user']));
    if ($user = $stm->fetch()){
        $id = $user['id'];
    }else{
        echo "<div class='alert alert-info'>Couldn't load user info</div>";
    }
}
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
<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">FarmSol</a>
        </div>
        <ul class="nav navbar-nav">
            <li ><a href="index.php">Home</a></li>
            <li ><a href="questionAsk.php">Ask Question</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="statistics.php">Statistics</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="signUp.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
            <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        </ul>
    </div>
</nav>

<div class="container">

    <!-- CONTENT HERE -->

    <div class="login">
        <div>
            <h1 class="page-header text-center text-capitalize"> Question Form</h1>
        </div>
        <form action="" method="post" enctype="multipart/form-data" role="form">
            <?php
            if (isset($_POST['submit'])){
                $category = $_POST['category'];
                $question = $_POST['question'];
                $description = $_POST['description'];
                $image = $_FILES['image'];

                if (empty($category)){
                    echo "<div class='alert alert-info'>Please select category</div>";
                }elseif (empty($question)){
                    echo "<div class='alert alert-info'>Please enter a question</div>";
                }elseif (empty($description)){
                    echo "<div class='alert alert-info'>Please enter a description of the problem</div>";
                }elseif (empty($image)){
                    echo "<div class='alert alert-info'>Please select an image</div>";
                }else{
                    if (!empty($category) && !empty($question) && !empty($description)){
                        $statement = $pdo->prepare("INSERT INTO `questions`( `userId`, `category`, `question`, `description`) VALUES (?, ?, ?, ?)");
                        if($statement->execute(array($id, $category, $question, $description))){
                            header("Location: index.php");
                        }

                    }
                }

            }
            ?>
            <div class="form-group">
                <label for="category">Select Category</label>
                <select name="category" class="form-control" id="category">
                    <option value="livestock">Livestock production</option>
                    <option value="crop">Crop Production</option>
                    <option value="machinery">Farm Machinery</option>
                    <option value="management">Management</option>
                </select>
            </div>
            <div class="form-group">
                <label for="question">Question</label>
                <input type="text" name="question" id="question" class="form-control">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" cols="30" rows="10"></textarea>
            </div>
            <div class="form-group">
                <label for="image">Images</label>
                <input type="file"  name="image" id="image">
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="form-control btn btn-success">Submit</button>
            </div>
        </form>
    </div>

</div>

<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/app.js"></script>
</body>
</html>

