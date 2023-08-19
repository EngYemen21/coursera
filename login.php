<?php
session_start();
require_once "pdo.php";
$erro=$passErr=$emailError='';

if (isset($_POST['cancel'])) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
if (isset($_POST['pass']) and isset($_POST['email'])) {
    $check = hash('md5', $salt . $_POST['pass']);

    $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');

    $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));


    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r ($row) ;

    if ($row !== false) {

        $_SESSION['name'] = $row['name'];

        $_SESSION['user_id'] = $row['user_id'];

// Redirect the browser to index.php

        header("Location:index.php");

        return;
    }
    if (!isset($_POST['pass'])){

            $passErr="The password is require";
    }
    if  (isset($_POST['email'])){

        $emailError="The Email is require";
}


// Fall through into the View
}
else {
    $erro='The Email and password is required';

}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once "bootstrap.php"; ?>
    <title>mohammed bander</title>
</head>
<body>
<div class="container">
    <h1>Please Log In</h1>
    <?php
    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
        unset($_SESSION['error']);
    }
    ?>
    <?php
    if (isset($erro)){
        echo "
        <div style='color:red'>
           $erro;
        </div>
        ";
     
        
    }
    ?>
    <form method="POST" action="login.php">
        User Name <input type="text" name="email"><br/>
     <p style="color:red">  <?php echo $emailError?></p>
        Password <input type="text" name="pass"><br/>
     <p style="color:red"> <?php echo $passErr ?></p>
        <input type="submit" value="Log In">
        <input type="submit" name="cancel" value="Cancel">
    </form>
    <p>
        For a password hint, view source and find a password hint
        in the HTML comments.
        <!-- Hint: The password is the four character sound a cat
        makes (all lower case) followed by 123. -->
    </p>
</div>
</body>
</html>