<?php
require_once "connection.php";
// Start session for current user
session_start();
// Redirect to dashboard if user is logged in 

if (isset($_SESSION["user"])) {
    header("location: dashboard.php");
}

// Check to see if form is been filled out and submitted

if (isset($_REQUEST["reg_btn"])) {
    // if form has been submitted filter and store information to these variables

    $name = filter_var($_REQUEST['name'], FILTER_UNSAFE_RAW);
    $email = filter_var(strtolower($_REQUEST['email']), FILTER_SANITIZE_EMAIL);
    $password = strip_tags($_REQUEST['password']);
    $rePassword = strip_tags($_REQUEST['repassword']);
    // Errors
    if (empty($name)) {
        $errMessage[0][] = 'Name Required';
    }

    if (empty($email)) {
        $errMessage[1][] = 'Email Required';
    }

    if (empty($password)) {
        $errMessage[2][] = 'Password Required';
    }

    if (strlen($password) < 6) {
        $errMessage[2][] = 'Must be atleast 6 characters';
    }

    if (empty($rePassword)) {
        $errMessage[3][] = 'Please retype password';
    }
    if ($rePassword != $password) {
        $errMessage[3][] = 'Passwords do not match. ';
    }

    // Check if email already exists in database
    if (empty($errMessage)) {
        try {
            $select_statement = $db->prepare("SELECT name, email FROM users WHERE email = :email");
            $select_statement->execute([':email' => $email]);
            $row = $select_statement->fetch(PDO::FETCH_ASSOC);


            if (isset($row['email']) == $email) {
                $errMessage[1][] = "Email Address already exists.";
            } else {
                // hash password for extra security
                $hashedPass = password_hash($password, PASSWORD_DEFAULT);
                $created = new DateTime();
                $created = $created->format('Y-m-d H:i:s');
                // Instert user form input to database
                $insertStatement = $db->prepare("INSERT INTO users (name,email,password,created) VALUES (:name,:email,:password,:created)");

                if ($insertStatement->execute(
                    [
                        ':name' => $name,
                        ':email' => $email,
                        ':password' => $hashedPass,
                        ':created' => $created
                    ]
                )) {

                    header("location: index.php?msg=" . urlencode("Successfully Registered, pleasee log in!"));
                }
            }
        } catch (PDOException $e) {
            $pdoErr = $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav id="navbar">

        <div class="brand">
            <h1>Hello</h1>
        </div>

    </nav>
    <div class="container">

        <form action="register.php" method="post">
            <div class="">

                <!-- Display error messages -->
                <?php
                if (isset($errMessage[0])) {
                    foreach ($errMessage[0] as $nameErrors) {
                        echo "<p class='errmsg' style='color:red;'>" . $nameErrors . "</p>";
                    }
                }
                ?>
                <label for="name" class="label">Name</label>
                <input type="text" name="name" class="input" placeholder="Jane Doe">
            </div>
            <div class="">
                <!-- Display error messages -->
                <?php
                if (isset($errMessage[1])) {
                    foreach ($errMessage[1] as $emailErrors) {
                        echo "<p class='errmsg'  style='color:red;'>" . $emailErrors . "</p>";
                    }
                }
                ?>
                <label for="email" class="label">Email address</label>
                <input type="email" name="email" class="input" placeholder="jane@doe.com">
            </div>

            <div class="">
                <!-- Display error messages -->
                <?php
                if (isset($errMessage[2])) {
                    foreach ($errMessage[2] as $passwordErrors) {
                        echo "<p class='errmsg'  style='color:red;'>" . $passwordErrors . "</p>";
                    }
                }
                ?>
                <label for="password" class="label">Password</label>
                <input type="password" name="password" class="input" placeholder="Create password">

            </div>
            <div class="">
                <!-- Display error messages -->
                <?php
                if (isset($errMessage[3])) {
                    foreach ($errMessage[3] as $rePasswordErrors) {
                        echo "<p class='errmsg'  style='color:red;'>" . $rePasswordErrors . "</p>";
                    }
                }
                ?>
                <label for="password" class="label">Re enter Password</label>
                <input type="password" name="repassword" class="input" placeholder="Re enter password">

            </div>
            <button type="submit" name="reg_btn" class="btn">Register</button>
        </form>
        <div class="subTxt">Already Have an Account? <a class="register" href="index.php">Login</a></div>
    </div>
</body>

</html>