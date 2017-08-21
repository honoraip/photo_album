<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Image Album</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">  
</head>

<body>
    
    <?php
        $msg = "";
    
        //Get the connection info for the DB. 
	require_once '../includes/config.php';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        //Was there an error connecting to the database?
	if ($mysqli->errno) {
	    print($mysqli->error);
	    exit();
        }
        
        $asql = "SELECT * FROM Albums;";
        
        //Get the data
	$aresult = $mysqli->query($asql);
        
        if (isset($_POST['deletealbum'])) {
            
            if (!empty($_POST['albumselect'])) {
                $aID = $_POST['albumselect'];
                
                $edit = "DELETE FROM Albums WHERE aID='$aID';";
                $update = $mysqli->query($edit);
                $msg .= "<p>Album has been deleted.<p>";
            }
        }
    ?>
    
    <?php
        $imsg = "";
    
        //Get the connection info for the DB. 
	require_once '../includes/config.php';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        //Was there an error connecting to the database?
	if ($mysqli->errno) {
	    print($mysqli->error);
	    exit();
        }
        
        $asql = "SELECT * FROM Images;";
        
        //Get the data
	$aresult = $mysqli->query($asql);
        
        if (isset($_POST['deleteimage'])) {
            
            if (!empty($_POST['imageselect'])) {
                $pID = $_POST['imageselect'];
                
                $edit = "DELETE FROM Images WHERE pID='$pID';";
                $update = $mysqli->query($edit);
                $imsg .= "<p>Image has been deleted from all albums.<p>";
            }
        }
    ?>
    
    <div id = "banner">
        <h1>Image Album</h1>
        <h2>Edit Albums and Images</h2>
    </div>
    
    <?php include "nav.php";?>
    
    <?php
        if ( isset( $_SESSION[ 'logged_user' ])) {
    
            print ("<div id = 'form'>");
            print("<div id = 'formalbum'>");
            print("<div class= 'inputstyle'>");

            print("<form action='delete.php' method='post'>");
            print("<h3>Delete album:</h3>");
    
            print("Select Album: <br>");
            print("<select name='albumselect'>");
            print("<option value=''>Select...</option>");
        }
    ?>
    
    <?php
        if ( isset( $_SESSION[ 'logged_user' ])) {
        //Get the connection info for the DB. 
	require_once '../includes/config.php';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        //Was there an error connecting to the database?
	if ($mysqli->errno) {
	    print($mysqli->error);
	    exit();
        }
        
        $albums = "SELECT * FROM Albums";
        
        //Get the data
	$albumresult = $mysqli->query($albums);
    
        while ($row = $albumresult->fetch_assoc()) {
            $aID = $row['aID'];
            $title = $row['title'];
            
            print "<option value=$aID>$title</option>";
        }
        }
    ?>
    
    <?php
        if ( isset( $_SESSION[ 'logged_user' ])) {
            print("</select> <p></p>");
    
            print("<input type='submit' name='deletealbum' value='Delete Album'> <br>");
    
            print("</form>");
            print("</div>");
            print("</div>");
    
            print("<div id='formimage'>");
            print("<div class='inputstyle'>");

            print("<form action='delete.php' method='post'>");
            print("<h3>Delete image from all albums:</h3>");
    
            print("Select Image: <br>");
            print("<select name='imageselect'>");
            print("<option value=''>Select...</option>");
        }
    ?>
    
    <?php
        if ( isset( $_SESSION[ 'logged_user' ])) {
        //Get the connection info for the DB. 
	require_once '../includes/config.php';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        //Was there an error connecting to the database?
	if ($mysqli->errno) {
	    print($mysqli->error);
	    exit();
        }
        
        $images = "SELECT * FROM Images";
        
        //Get the data
	$imageresult = $mysqli->query($images);
    
        while ($row = $imageresult->fetch_assoc()) {
            $pID = $row['pID'];
            $caption = $row['caption'];
            
            print "<option value=$pID>$caption</option>";
        }
        }
    ?>
    
    <?php
    if ( isset( $_SESSION[ 'logged_user' ])) {
        print("</select> <p></p>");
    
        print("<input type='submit' name='deleteimage' value='Delete Image'> <br>");
    
        print("</form>");
        print("</div>");
        print("</div>");
        print("</div>");
    
        print("<div id=success><?php echo $msg ?><?php echo $imsg ?></div>");
    }
    
    
    ?>
    
<?php
if ( !isset( $_SESSION[ 'logged_user' ])) {
print("<div id = 'imageinfo'>
       <p>Please <a href='login.php'>login to proceed.</a></p>
</div>");
}
?>
    
</body>
</html>