<?php
require_once "connection.php";

session_start();
// If user not logged in redirect to index page
if (!isset($_SESSION['user'])) {
    header("location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <?php
        echo "<h1 class='dash-welcome'> User: " . $_SESSION["user"]["name"] . " </h1>";
        ?>
        <nav id="navbar-dash">
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>

        <div class="btn-logout"><a class="logout-btn" href="logout.php">Logout</a></div>
    </header>


    <div class="container-dash">

    </div>

</body>

</html>