<?php
/**
 * Created by PhpStorm.
 * User: geekscript
 * Date: 3/8/19
 * Time: 12:24 PM
 */

session_start();
unset($_SESSION['user']);
session_destroy();
header("Location: login.php");
exit;