<?php
/**
 * Created by PhpStorm.
 * User: geekscript
 * Date: 4/10/19
 * Time: 11:24 AM
 */

require_once "../config/database.php";

session_start();
if (!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$db = new  Database();
$pdo = $db->getConnection();

$stm = $pdo->prepare("SELECT * FROM `users` WHERE `email`=?");
$stm->execute(array($_SESSION['user']));
$user = $stm->fetch();

$voterId = $user['id'];

if (isset($_GET['upvote_id'])){
    $answerId =  $_GET['upvote_id'];
    $statement = $pdo->prepare("SELECT * FROM `answer_votes` WHERE `userId`=? AND `answerId`=?");
    $statement->execute(array($voterId, $answerId));
    $num_rows = $statement->rowCount();
    //echo $num_rows;

    if ($num_rows == 0){
        $stm = $pdo->prepare("INSERT INTO `answer_votes`( `answerId`, `userId`, `upvotes`) VALUES (?, ?, ?)");
        if ($stm->execute(array($_GET['upvote_id'], $voterId, "1"))){
            //echo "seccessful";
            header("Location: index.php");
        }

    }else{
        $stm = $pdo->prepare("UPDATE `answer_votes` SET `upvotes`=?,`downvotes`=? WHERE `answerId`=? AND `userId`=?");
        if($stm->execute(array("1", "0", $_GET['upvote_id'], $voterId))){
            header("Location: index.php");
        }
    }

}

if (isset($_GET['downvote_id'])) {
    $answerId = $_GET['downvote_id'];
    $statement = $pdo->prepare("SELECT * FROM `answer_votes` WHERE `userId`=? AND `answerId`=?");
    $statement->execute(array($voterId, $answerId));
    $num_rows = $statement->rowCount();

    if ($num_rows == 0) {
        $stm = $pdo->prepare("INSERT INTO `answer_votes`( `answerId`, `userId`, `upvotes`, `downvotes`) VALUES (?, ?, ?, ?)");
        if ($stm->execute(array($_GET['upvote_id'], $voterId, "0", "1"))) {
            header("Location: index.php");
        }

    } else {
        $stm = $pdo->prepare("UPDATE `answer_votes` SET `upvotes`=?,`downvotes`=? WHERE `answerId`=? AND `userId`=?");
        if ($stm->execute(array("0", "1", $_GET['downvote_id'], $voterId))) {
            header("Location: index.php");
        }
    }

}