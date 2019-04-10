<?php
/**
 * Created by PhpStorm.
 * User: geekscript
 * Date: 3/4/19
 * Time: 3:03 PM
 */
require_once "../config/database.php";
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}
$db = new Database();
$pdo = $db->getConnection();

$statement = $pdo->prepare("SELECT * FROM `users` WHERE `email`=?");
$statement->execute(array($_SESSION['user']));
$user = $statement->fetch();
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
            <li class="active"><a href="#">Home</a></li>
            <li><a href="questionAsk.php">Ask Question</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="statistics.php">Statistics</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="text-capitalize"><a href="#"><span
                            class="glyphicon glyphicon-user"></span> <?php echo $user['firstName'] . " " . $user['lastName']; ?>
                </a></li>
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container">

    <!-- CONTENT HERE -->
    <div class="search post">
        <form action="" class="form-inline" method="post">
            <input type="text" name="search" class="form-control" placeholder="Search ">
            <button type="submit" name="submit" class="btn btn-primary">Search</button>
        </form>

        <form action="" class="form-inline" method="post">
            <label for="cat">Select category</label>
            <select name="cat" class="form-control" id="cat">
                <option value="management">Management</option>
                <option value="machinery">Machinery</option>
                <option value="crop">Crop Production</option>
                <option value="livestock">Livestock production</option>
            </select>
            <button type="submit" name="filter" class="btn btn-success">Filter</button>
        </form>
    </div>

    <?php
    if (isset($_POST['submit']) && isset($_POST['search'])){
        $search_term = "%" . $_POST['search'] . "%";
        $stm = $pdo->prepare("SELECT * FROM `questions` WHERE (`category` LIKE ? OR `question` LIKE ? OR `description` LIKE ?)");
        $stm->execute(array($search_term, $search_term, $search_term));
    }else if (isset($_POST['cat']) && isset($_POST['filter'])){
        $stm = $pdo->prepare("SELECT * FROM `questions` WHERE `category` = ?");
        $stm->execute(array($_POST['cat']));
    }else{
        $stm = $pdo->prepare("SELECT * FROM `questions` ");
        $stm->execute();
    }

    if ($stm->rowCount() < 0) {
        echo "<div class='alert alert-primary'>No Questions asked Yet!</div>";
    } else {
        $results = $stm->fetchAll();

        foreach ($results as $result) {
            echo '<div class="post">';
            echo "<a href=\"questionDetails.php?questionId={$result['id']}\">";
            echo "<h5><i class=\"material-icons\">account_circle</i> {$result['category']}</h5>";
            echo "<h3>{$result['question']}</h3>";
            $state = $pdo->prepare("SELECT * FROM `answers` WHERE `questionId`=?");
            $state->execute(array($result['id']));
            $answers = $state->rowCount();

            $statement = $pdo->prepare("SELECT * FROM `question_votes` WHERE `questionId`=? AND `upvotes`=?");
            $statement->execute(array($result['id'], "1"));
            $upvotes = $statement->rowCount();

            $statement = $pdo->prepare("SELECT * FROM `question_votes` WHERE `questionId`=? AND `downvotes`=?");
            $statement->execute(array($result['id'], "1"));
            $downvotes = $statement->rowCount();
            echo "<div class=\"post_footer\">
            <ul>
                <li> {$upvotes} Upvotes</li>
                <li> {$downvotes} Downvotes</li>
                <li> {$answers} Answers</li>
            </ul>
        </div>
        </a>
    </div>";
        }
    }
    ?>

</div>
<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/app.js"></script>
</body>
</html>
