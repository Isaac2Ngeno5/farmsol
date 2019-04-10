<?php
/**
 * Created by PhpStorm.
 * User: geekscript
 * Date: 3/7/19
 * Time: 1:31 PM
 */
require_once "../config/database.php";
session_start();

$db = new Database();
$pdo = $db->getConnection();

if (!isset($_GET['questionId'])) {
    header("Location: ../");
    exit;
}

if (isset($_SESSION['user'])) {
    $post = true;
    $statement = $pdo->prepare("SELECT * FROM `users` WHERE `email`=?");
    $statement->execute(array($_SESSION['user']));
    $user = $statement->fetch();
} else {
    $post = false;
}

$statement = $pdo->prepare("SELECT * FROM `questions` WHERE `id`=?");
$statement->execute(array($_GET['questionId']));
$details = $statement->fetch();

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
            <li>
                <?php
                if ($post == true){
                    echo "<a href=\"index.php\">Home</a>";
                }else{
                    echo "<a href=\"../index.php\">Home</a>";
                }
                ?>
            </li>
            <li ><a href="questionAsk.php">Ask Question</a></li>
            <li><a href="users.php">Users</a></li>
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
<div class="container">

    <!-- CONTENT HERE -->
    <div class="post">
        <div class="question_header">
            <h5 class="text-capitalize"><i class="material-icons">account_circle</i>
                <?php
                $stm = $pdo->prepare("SELECT * FROM `users` WHERE `id`=?");
                $stm->execute(array($details['userId']));
                $poster = $stm->fetch();

                echo $poster['firstName'] . " " . $poster['lastName'];
                ?>
            </h5>
        </div>

        <div class="question">
            <h3><?php echo $details['question']; ?></h3>
        </div>
        <!--        <div class="question_media">-->
        <!--            <image src="images/" alt="" class="img-responsive">-->
        <!--        </div>-->
        <div class="question_description">
            <p><?php echo $details['description']; ?></p>
        </div>
        <div class="post_answer">
            <form action="" class="form-inline" role="form" method="post">
                <?php
                if (isset($_POST['send'])) {
                    $qResponse = $_POST['answer'];

                    if (empty($qResponse)) {
                        echo "<div class='alert alert-info'>Can't submit empty answer</div>";
                    } else {
                        $query = $pdo->prepare("INSERT INTO `answers`( `questionId`, `answer`, `userId`) VALUES (?, ?, ?)");
                        if ($query->execute(array($details['id'], $qResponse, $user['id']))) {
                            echo "<div class='alert alert-success'>Answer submitted successfully</div>";
                        } else {
                            echo "<div class='alert alert-info'><strong>Error</strong>failed to submit answer</div>";
                        }
                    }
                }
                ?>
                <textarea name="answer" id="" class="form-control" cols="30" rows="2" placeholder="Write answer here.."></textarea>
                <?php
                if ($post == true) {
                    echo "<button type=\"submit\" name='send' class=\"btn btn-primary\">Submit</button>";
                } else {
                    echo "<button type=\"submit\" class=\"btn btn-default\" disabled>Submit</button>";
                }
                ?>

            </form>
        </div>


        <div class="post_footer">
            <?php
            $state = $pdo->prepare("SELECT * FROM `answers` WHERE `questionId`=?");
            $state->execute(array($details['id']));
            $numberAnswers = $state->rowCount();

            $statement = $pdo->prepare("SELECT * FROM `question_votes` WHERE `questionId`=? AND `upvotes`=?");
            $statement->execute(array($details['id'], "1"));
            $upvotes = $statement->rowCount();

            $statement = $pdo->prepare("SELECT * FROM `question_votes` WHERE `questionId`=? AND `downvotes`=?");
            $statement->execute(array($details['id'], "1"));
            $downvotes = $statement->rowCount();
            ?>
            <ul>
                <li><a href="vote.php?upvoteId=<?php echo $details['id']?>"><span class="glyphicon glyphicon-thumbs-up"></span>  <?php echo $upvotes;?></a></li>
                <li><a href="vote.php?downvoteId=<?php echo $details['id']?>"><span class="glyphicon glyphicon-thumbs-down"></span>  <?php echo $downvotes; ?></a></li>
                <li> <?php echo $numberAnswers ?> Answers</li>
            </ul>
        </div>
        <hr>

        <?php

        if ($numberAnswers > 0) {
            $answers = $state->fetchAll();

            foreach ($answers as $answer) {
                $stm = $pdo->prepare("SELECT * FROM `users` WHERE `id`=?");
                $stm->execute(array($answer['userId']));
                $answerer = $stm->fetch();

                $statement = $pdo->prepare("SELECT * FROM `answer_votes` WHERE `answerId`=? AND `upvotes`=?");
                $statement->execute(array($details['id'], "1"));
                $upvotes = $statement->rowCount();

                $statement = $pdo->prepare("SELECT * FROM `answer_votes` WHERE `answerId`=? AND `downvotes`=?");
                $statement->execute(array($details['id'], "1"));
                $downvotes = $statement->rowCount();

                if ($answerer['type'] == "1") {
                    echo "<div class=\"answer ext\">";
                } else {
                    echo "<div class=\"answer\">";
                }
//TODO : fix the answer vote problem
                echo "<h5 class='text-capitalize'><i class=\"material-icons\">account_circle</i> {$answerer['firstName']} {$answerer['lastName']}</h5>
            <div class=\"answer_body\">
                <p>{$answer['answer']}</p>
            </div>
            <div class=\"answer_footer post_footer\">
                <ul>
                    <li><a href='vote.php?upvote_id={$answer['id']}'><span class='glyphicon glyphicon-thumbs-up'></span> {$upvotes} Upvotes</a> </li>
                    <a><a href='vote.php?downvote_id={$answer['id']}'><span class='glyphicon glyphicon-thumbs-down'></span> {$downvotes} Downvotes</a></li>
                </ul>
            </div>
            </div>
";
            }
        } else {
            echo "<div class='alert alert-info'>No answers yet!</div>";
        }
        ?>

    </div>

</div>

<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/app.js"></script>
</body>
</html>

