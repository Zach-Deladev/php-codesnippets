<?php
require_once "connection.php";
// Start session 
session_start();

// If logged in redirect to Dashboard
if (isset($_SESSION['user'])) {
    header("location: dashboard.php");
}

// Handle login submit - if login button pressed filter email and password and check against database
if (isset($_REQUEST['login_btn'])) {
    $email = filter_var(strtolower($_REQUEST['email']), FILTER_SANITIZE_EMAIL);
    $password = strip_tags($_REQUEST['password']);

    // Empty Field errors
    if (empty($email)) {
        $errMessage[] = 'Please enter email address';
    } else if (empty($password)) {
        $errMessage[] = 'Please enter password';
    }

    // Submit login details to database
    else {

        try {
            $select_statement = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $select_statement->execute([
                ':email' => $email
            ]);
            // Pull out row from database
            $row = $select_statement->fetch(PDO::FETCH_ASSOC);
            //  If row exists then check password
            if ($select_statement->rowCount() > 0) {

                // Check if password is valid, using password verify function that takes the password and row to check if theres a match
                if (password_verify($password, $row["password"])) {

                    // If password is correct Store user information to session 
                    $_SESSION['user']['name'] = $row["name"];
                    $_SESSION['user']['email'] = $row["email"];
                    $_SESSION['user']['id'] = $row["id"];


                    header("location: dashboard.php");
                } else {
                    $errMessage[] = 'Wrong email or Password.';
                }
            } else {
                $errMessage[] = 'Wrong email or Password.';
            }
            // Errors
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Logo</h1>
        <nav id="navbar-dash">
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <?php
        // Alert for user to click verification email 
        if (isset($_REQUEST['msg'])) {
            echo "<p class='regsuccess' style='color:green'>" . $_REQUEST['msg'] . "</p>";
        }
        // Form input errors
        if (isset($errMessage)) {
            foreach ($errMessage as $loginErr) {
                echo "<p style='color:red'>" . $loginErr . "</p>";
            }
        }

        ?>
        <form action="index.php" method="post">
            <div class="">
                <label for="email" class="label">Email address</label>
                <input type="email" name="email" class="input" placeholder="jane@doe.com">
            </div>
            <div class="">
                <label for="password" class="label">Password</label>
                <input type="password" name="password" class="input" placeholder="">

            </div>
            <button type="submit" name="login_btn" class="btn">Login</button>
        </form>
        <div class="subTxt">Don't have an account? <a class="register" href="register.php">Register here</a></div>

    </div>

</body>

</html>