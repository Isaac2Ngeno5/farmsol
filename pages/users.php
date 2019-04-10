<?php
/**
 * Created by PhpStorm.
 * User: geekscript
 * Date: 3/7/19
 * Time: 2:46 PM
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
            <li class="active"><a href="#">Users</a></li>
            <li><a href="statistics.php">Statistics</a></li>
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

<div class="container ">

    <!-- CONTENT HERE -->
    <h2>List of Users</h2>
    <div>
        <p>Get user contacts here</p>
    </div>
    <br>

    <div class="row">
        <form action="users.php" class="form-inline col-md-6" role="form" method="get">
            <div class="form-group">
                <input type="text" name="search" class="form-control" placeholder="Search">
                <button type="submit" name="submit-search" class="btn btn-default form-control">
                    <i class="material-icons">search</i></button>
                <?php
                if (isset($_GET['submit-search'])) {
                    echo "<a href='users.php'><button class='btn btn-info form-control'>" . $_GET['search'] . " &times; </button></a>";
                }
                ?>
            </div>
        </form>
        <div class=""></div>
    </div>

    <div class="table-responsive" style="margin: 10px auto">
        <table class="table table-striped table-hover table-bordered">
            <thead>
            <tr class="bg-success">
                <th>No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>County</th>
            </tr>
            </thead>
            <tbody>
    <?php

    if (isset($_GET['submit-search'])) {

        $search = sanitize($_GET['search']);

        if (empty($search)) {
            echo "<div class=\"alert alert-warning\"><strong>Warning!</strong> please enter a search term.</div>";
        } elseif (!empty($search)) {
            $search_term = "%" . $search . "%";
            $stm = $pdo->prepare("SELECT * FROM `users` WHERE (`firstName` LIKE ? OR `lastName` LIKE ? OR `county` LIKE ? OR `phone` LIKE ? OR `email` LIKE ?)");
            $stm->execute(array($search_term, $search_term, $search_term, $search_term, $search_term));
            $users = $stm->fetchAll();

            if ($stm->rowCount() == 0) {
                echo "<div class=\"alert alert-warning\"><strong>Warning!</strong> No results found for " . $search . ".</div>";
            } else {
                fetch_user_records($users);
            }
        }
    } else {
        fetch_user_records($users);
    }


    function fetch_user_records($users)
    {
        $i = 1;
        foreach ($users as $user) {
            echo "
            <tr>
                <td>{$i}</td>
                <td>{$user['firstName']} {$user['lastName']}</td>
                <td>{$user['email']}</td>
                <td>{$user['phone']}</td>
                <td>{$user['county']}</td>
            </tr>
            ";
            $i++;
        }
    }

    function sanitize($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    ?>
            </tbody>
        </table>
    </div>

</div>

<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/app.js"></script>
</body>
</html>

