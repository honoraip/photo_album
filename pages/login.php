<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Image Album</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>
    
    <div id = "banner">
        <h1>Image Album</h1>
        <h2>Login</h2>
    </div>
    

    <?php include "nav.php";?>
    
    <?php
    $username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
    $password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );
   
    //Get the connection info for the DB. 
    require_once '../includes/config.php';
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
    //Was there an error connecting to the database?
    if ($mysqli->errno) {
        print($mysqli->error);
        exit();
    }
    
    $sql = "SELECT * FROM users WHERE username = '$username'";
    
    $result = $mysqli->query($sql);
    
    $msg = "";

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $db_hash_password = $row[ 'hashpassword' ];
        
        if( password_verify( $password, $db_hash_password ) ) {
            $_SESSION['logged_user'] = $username;
            $msg .= "<p>Congratulations, $username, you have successfully logged in.<p>";
        }
    }
    
    elseif ( empty( $username ) || empty( $password ) ) {
        print("<div id = 'form'>");
        print("<div class= 'inputstyle'>");
        
        print ("<h3>Log in</h3>");
        
        print("<form action='login.php' method='post'>");
        print("Username: <input type='text' name='username'> <br>");
        print("Password: <input type='password' name='password'> <br>");
        print("<p></p>");
        print("<input type='submit' value='Submit'>");
        print("</form>");
    }
    
    else {
        $msg .= "<p>You did not login successfully.</p>";
        $msg .= "<p>Please <a href='login.php'>login</a></p>";
    }
?>
    <div id = 'imageinfo'>
        <?php echo $msg ?>
    </div>

</body>
</html>