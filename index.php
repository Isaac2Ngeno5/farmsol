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

    <form action="" method="post" class="form-inline" role="form">
        <div class="form-group">
            <input type="text" class="form-control" name="search" placeholder="Enter keywords">
            <button type="submit" class="btn btn-default">Search</button>
        </div>
    </form>
        <p>This is some text.</p>
        <p>This is another text.</p>


</div>

<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/app.js"></script>
<footer>
    <div class="text-center">FarmSol &copy 2019</div>
</footer>
</body>
</html>
