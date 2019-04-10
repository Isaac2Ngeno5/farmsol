<?php
/**
 * Created by PhpStorm.
 * User: geekscript
 * Date: 4/10/19
 * Time: 11:51 AM
 */

require_once "../config/database.php";
session_start();

$db = new Database();
$pdo = $db->getConnection();

if (isset($_SESSION['user'])) {
    $post = true;
    $statement = $pdo->prepare("SELECT * FROM `users` WHERE `email`=?");
    $statement->execute(array($_SESSION['user']));
    $user = $statement->fetch();
} else {
    $post = false;
}

$statement = $pdo->prepare("SELECT * FROM `users`");
if ($statement->execute()) {
    $num_users = $statement->rowCount();
    $users = $statement->fetchAll();
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
<nav class="navbar navbar-inverse" >
    <div class="container ">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">FarmSol</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
            <li ><a href="questionAsk.php">Ask Question</a></li>
            <li><a href="users.php">Users</a></li>
            <li class="active"><a href="#">Statistics</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php
            if ($post == true) {
                echo " <li class=\"text-capitalize\"><a href=\"#\"><span
                            class=\"glyphicon glyphicon-user\"></span> {$user['firstName']}  {$user['lastName']}
                </a></li>
            <li><a href=\"logout.php\"><span class=\"glyphicon glyphicon-log-in\"></span> Logout</a></li>";
            } else {
                echo "<li><a href=\"signUp.php\"><span class=\"glyphicon glyphicon-user\"></span> Sign Up</a></li>
            <li><a href=\"login.php\"><span class=\"glyphicon glyphicon-log-in\"></span> Login</a></li>";
            }
            ?>

        </ul>
    </div>
</nav>
<div class="container">
    <h1 class="page-header text-capitalize text-center">Site statistics</h1>

    <table class="table  table-striped">
        <tr>
            <th>Property</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Number of Users</td>
            <td><?php echo $num_users;?></td>
        </tr>
        <tr>
            <td>Number of Extension Officers</td>
            <td><?php
                $num_ext = 0;
            foreach ($users as $user){
                if ($user['type'] == 1){
                    $num_ext++;
                }
            }
                echo $num_ext;
                ?>
            </td>
        </tr>
        <tr>
            <td>Number of Questions</td>
            <td><?php
               $statement=$pdo->prepare("SELECT * FROM `questions`");
               $statement->execute();
               $num_questions = $statement->rowCount();

               echo $num_questions;
                ?>
            </td>
        </tr>
        <tr>
            <td>Number of Answers</td>
            <td><?php
                $statement=$pdo->prepare("SELECT * FROM `answers`");
                $statement->execute();
                $num_answers = $statement->rowCount();

                echo $num_answers;
                ?>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
