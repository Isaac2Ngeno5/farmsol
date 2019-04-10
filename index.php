<?php
require_once "config/database.php";

$db = new Database();
$pdo = $db->getConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Title</title>

    <link rel="stylesheet" type="text/css" href="fonts/roboto/roboto.css">
    <link rel="stylesheet" type="text/css" href="fonts/material/material-icons.css">

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body{
            background-image: url("Greenery.jpg") ;
        }
    </style>
</head>
<body id="landing">
<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">FarmSol</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="pages/index.php">Home</a></li>
            <li><a href="pages/questionAsk.php">Answer</a></li>
            <li><a href="pages/users.php">Users</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="pages/signUp.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
            <li><a href="pages/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        </ul>
    </div>
</nav>

<div class="container">

    <!-- CONTENT HERE -->
        <div class="jumbotron">
            <h1>Welcome to FarmSol</h1>
            <p>Platform for farmers to posse their challenges and help one another in finding solutions based on
                experiance and professional advice from Extension officers.</p>
        </div>

    <div class="search post">
        <form action="" class="form-inline" method="post">
            <input type="text" name="search" class="form-control" placeholder="Search ">
            <button type="submit" name="submit" class="btn btn-primary">Search</button>
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
            echo "<a href=\"pages/questionDetails.php?questionId={$result['id']}\">";
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

<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/app.js"></script>
<footer>
    <div class="text-center">FarmSol &copy 2019</div>
</footer>
</body>
</html>
