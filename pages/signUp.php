<?php
/**
 * Created by PhpStorm.
 * User: geekscript
 * Date: 3/4/19
 * Time: 3:04 PM
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
            if (isset($_POST['submit']) && isset($_POST['terms'])) {
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $county = $_POST['county'];
                $password = $_POST['password'];
                $confirmPassword = $_POST['confirmPassword'];


                if (empty($firstName)) {
                    echo "<div class='alert alert-info'>Please enter first Name</div>";
                } elseif (empty($lastName)) {
                    echo "<div class='alert alert-info'>please enter Last Name</div>";
                } elseif (empty($email)) {
                    echo "<div class='alert alert-info'>Please enter Email</div>";
                } elseif (empty($phone)) {
                    echo "<div class='alert alert-info'>Please enter Phone Number</div>";
                } elseif (empty($password)) {
                    echo "<div class='alert alert-info'>please input password</div>";
                } elseif (empty($confirmPassword)) {
                    echo "<div class='alert alert-info'>Please enter a password confirmation</div>";
                } elseif ($password !== $confirmPassword) {
                    echo "<div class='alert alert-info'>Please ensure that passwords match</div>";
                } else {
                    if (!empty($firstName)
                        && !empty($lastName)
                        && !empty($email)
                        && !empty($phone)
                        && !empty($password)
                        && $password == $confirmPassword) {
                        $db = new Database();
                        $pdo = $db->getConnection();

                        $statement = $pdo->prepare("SELECT * FROM `users` WHERE `email`=?");
                        $statement->execute(array($email));
                        if ($statement->rowCount() > 0) {
                            echo "<div class='alert alert-info'>User with email already exist.<strong><a href='login.php'>Login Here</a></strong></div>";
                        }else{
                            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                            $statement = $pdo->prepare("INSERT INTO `users`( `firstName`, `lastName`, `email`, `phone`, `county`, `password`, `type`) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            if ($statement->execute(array($firstName, $lastName, $email, $phone, $county, $password, "member"))){
                                $_SESSION['user'] = $email;
                                header("Location: index.php");
                                exit;
                                //echo "<div class='alert alert-success'>Registration was successful</div>";
                            }else{
                                echo "<div class='alert alert-info'>Registration failed</div>";
                            }
                        }
                    }
                }

            } else {
                echo "<div class='alert alert-info'>Please ensure you read the terms and conditions</div>";
            }
            ?>
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" name="firstName" class="form-control" id="firstName">
            </div>
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" class="form-control">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" class="form-control">
            </div>
            <div class="form-group">
                <label for="county">County</label>
                <select name="county" id="county" class="form-control">
                    <option selected>Choose...</option>
                    <option value='Baringo'>Baringo</option>
                    <option value='Bomet'>Bomet</option>
                    <option value='Bungoma'>Bungoma</option>
                    <option value='Busia'>Busia</option>
                    <option value='Elgeyo-Marakwet'>Elgeyo-Marakwet</option>
                    <option value='Embu'>Embu</option>
                    <option value='Garissa'>Garissa</option>
                    <option value='Homa Bay'>Homa Bay</option>
                    <option value='Isiolo'>Isiolo</option>
                    <option value='Kajiado'>Kajiado</option>
                    <option value='Kakamega'>Kakamega</option>
                    <option value='Kericho'>Kericho</option>
                    <option value='Kiambu'>Kiambu</option>
                    <option value='Kilifi'>Kilifi</option>
                    <option value='Kirinyaga'>Kirinyaga</option>
                    <option value='Kisii'>Kisii</option>
                    <option value='Kisumu'>Kisumu</option>
                    <option value='Kitui'>Kitui</option>
                    <option value='Kwale'>Kwale</option>
                    <option value='Laikipia'>Laikipia</option>
                    <option value='Lamu'>Lamu</option>
                    <option value='Machakos'>Machakos</option>
                    <option value='Makueni'>Makueni</option>
                    <option value='Mandera'>Mandera</option>
                    <option value='Marsabit'>Marsabit</option>
                    <option value='Meru'>Meru</option>
                    <option value='Migori'>Migori</option>
                    <option value='Mombasa'>Mombasa</option>
                    <option value='Muranga'>Muranga</option>
                    <option value='Nairobi'>Nairobi</option>
                    <option value='Nakuru'>Nakuru</option>
                    <option value='Nandi'>Nandi</option>
                    <option value='Narok'>Narok</option>
                    <option value='Nyamira'>Nyamira</option>
                    <option value='Nyandarua'>Nyandarua</option>
                    <option value='Nyeri'>Nyeri</option>
                    <option value='Samburu'>Samburu</option>
                    <option value='Siaya'>Siaya</option>
                    <option value='Taita-Taveta'>Taita-Taveta</option>
                    <option value='Tana River'>Tana River</option>
                    <option value='Tharaka-Nithi'>Tharaka-Nithi</option>
                    <option value='Trans Nzoia'>Trans Nzoia</option>
                    <option value='Turkana'>Turkana</option>
                    <option value='Uasin Gishu'>Uasin Gishu</option>
                    <option value='Vihiga'>Vihiga</option>
                    <option value='West Pokot'>West Pokot</option>
                    <option value='wajir'>Wajir</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Confirm Password</label>
                <input type="password" id="password" name="confirmPassword" class="form-control">
            </div>
            <div class="checkbox">
                <label><input type="checkbox" name="terms" value="">I agree to terms and conditions</label>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-success form-control">Sign Up</button>
            </div>
        </form>
    </div>

</div>

<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/app.js"></script>
</body>
</html>

