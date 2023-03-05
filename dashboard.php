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

    <body>
        <div class="container">
            <?php
            echo "<h1 class='welcome'> Welcome User: " . $_SESSION["user"]["name"] . " </h1>";
            ?>

            <div class="btn-logout"><a class="logout-btn" href="logout.php">Logout</a></div>
        </div>
    </body>
</body>

</html>