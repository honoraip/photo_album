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
        $err = "";
    
        //Get the connection info for the DB. 
	require_once '../includes/config.php';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        //Was there an error connecting to the database?
	if ($mysqli->errno) {
	    print($mysqli->error);
	    exit();
        }
        
        $asql = "SELECT * FROM Albums INNER JOIN Images_Albums
        ON Albums.aID = Images_Albums.aID INNER JOIN Images
        ON Images_Albums.pID = Images.pID";
        
        //Get the data
	$aresult = $mysqli->query($asql);
        
        if (isset($_POST['editalbum'])) {
            
            if (!empty($_POST['albumselect']) && !empty($_POST['newtitle'])) {
                $newtitle = filter_input( INPUT_POST, 'newtitle', FILTER_SANITIZE_STRING );
                $aID = $_POST['albumselect'];
                $date = date("Y-m-d");
                
                $edit = "UPDATE Albums SET title='$newtitle', date_modified='$date'
                WHERE Albums.aID='$aID';";
                $update = $mysqli->query($edit);
                
                $msg .= "<p>Album title has been successfully edited.<p>"; 
            }
            
            if (!empty($_POST['albumselect']) && !empty($_POST['coverselect'])) {
                $aID = $_POST['albumselect'];
                $path = $_POST['coverselect'];
                $date = date("Y-m-d");
                
                $edit = "UPDATE Albums SET aCover='$path', date_modified='$date'
                WHERE Albums.aID='$aID';";
                $update = $mysqli->query($edit);
                
                $msg .= "<p>Cover photo has been successfully edited.<p>";
            }
            
            if (!empty($_POST['albumselect']) && !empty($_POST['addimage'])) {
                $aID = $_POST['albumselect'];
                $pID = $_POST['addimage'];
                $date = date("Y-m-d");
                
                function duplicate($pID,$aID) {
		//Get the connection info for the DB. 
                    require_once '../includes/config.php';
                    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            
                    $sql = "SELECT * FROM Images_Albums WHERE aID='$aID';";
                    $result = $mysqli->query($sql);
            
                    while ($row = $result->fetch_assoc()) {
                        if ($row[ 'pID' ] == $pID) {
                            return true;
                        }
                    }
                    
                    return false;
                }
                
                if (!duplicate($pID,$aID)) {
                    $edit = "INSERT INTO Images_Albums (aID,pID) VALUES ('$aID','$pID');";
                    $update = $mysqli->query($edit);
                    
                    $edit = "UPDATE Albums SET date_modified='$date' WHERE Albums.aID='$aID';";
                    $update = $mysqli->query($edit);
                
                    $msg .= "<p>Image has been successfully added to album.<p>";
                }
                
                else{
                    $err .="<p>Image already exists in this album.<p>";
                }
            }
            
            if (!empty($_POST['albumselect']) && !empty($_POST['deleteimage'])) {
                $aID = $_POST['albumselect'];
                $pID = $_POST['deleteimage'];
                $date = date("Y-m-d");
                
                $edit = "DELETE FROM Images_Albums WHERE Images_Albums.pID='$pID'
                AND Images_Albums.aID='$aID';";
                $update = $mysqli->query($edit);
                
                $edit = "UPDATE Albums SET date_modified='$date' WHERE Albums.aID='$aID';";
                $update = $mysqli->query($edit);
                
                $msg .= "<p>Image has been deleted from album.<p>";
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
        
        $psql = "SELECT * FROM Images INNER JOIN Images_Albums
        ON Images.pID = Images_Albums.pID INNER JOIN Albums
        ON Images_Albums.aID = Albums.aID";
        
        //Get the data
        $presult = $mysqli->query($psql);
            
        if (isset($_POST['editimage'])) {
            
            if (!empty($_POST['imageselect']) && !empty($_POST['newcaption'])) {
                $newcaption = filter_input( INPUT_POST, 'newcaption', FILTER_SANITIZE_STRING );
                $pID = $_POST['imageselect'];
                $date = date("Y-m-d");
                
                $edit = "UPDATE Images SET caption='$newcaption' WHERE Images.pID='$pID';";
                $update = $mysqli->query($edit);
                
                $imsg .= "<p>Image caption has been successfully edited.<p>"; 
            }
            
            if (!empty($_POST['imageselect']) && !empty($_POST['newcredit'])) {
                $newcredit = filter_input( INPUT_POST, 'newcredit', FILTER_SANITIZE_STRING );
                $pID = $_POST['imageselect'];
                $date = date("Y-m-d");
                
                $edit = "UPDATE Images SET credit='$newcredit' WHERE Images.pID='$pID';";
                $update = $mysqli->query($edit);
                
                $imsg .= "<p>Image credit has been successfully edited.<p>"; 
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
    print(
    "<div id = 'imageinfo'>
        <p>Only edit the fields you wish to change. Leave the rest blank.</p>
    </div>
    
    <div id = 'form'>
    <div id = 'formalbum'>
    <div class= 'inputstyle'>

    <form action='edit.php' method='post'>
    <h3>Edit album:</h3>
    
    Select Album: <br>
    <select name='albumselect'>
    <option value=''>Select...</option>");
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
    print(
    "</select> <p></p>
    
    New title: <br>
    <input type='text' name='newtitle' maxlength='50'> <p></p>
    
    Select new cover photo from existing images: <br>
    <select name='coverselect'>
    <option value=''>Select...</option>");
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
            $path = $row['file_path'];
            $caption = $row['caption'];
            
            print "<option value=$path>$caption</option>";
        }
        }
    ?>
    
    <?php
    if ( isset( $_SESSION[ 'logged_user' ])) {
    print("</select> <p></p>
    
    Add an image from existing images: <br>
    <select name='addimage'>
    <option value=''>Select...</option>");
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
    print("</select> <p></p>
    
    Delete an image from the album: <br>
    <select name='deleteimage'>
    <option value=''>Select...</option>");
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
    print("</select> <p></p>
    
    <input type='submit' name='editalbum' value='Edit Album'> <br>
    
    </form>
    </div>
    </div>
    
    <div id='formimage'>
    <div class='inputstyle'>
    
    <form action='edit.php' method='post'>
    <h3>Edit image:</h3>
    
    Select Image: <br>
    <select name='imageselect'>
    <option value=''>Select...</option>");
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
    print("</select> <p></p>
    
    New caption: <br>
    <input type='text' name='newcaption' maxlength='50'> <p></p>
    
    New credit: <br>
    <input type='text' name='newcredit' maxlength='50'> <p></p>
    
    <input type='submit' name='editimage' value='Edit Image'> <br>
    
    </form>
    </div>
    </div>
    </div>");

    }
    

    ?>
    
    <div id=success><?php echo $msg ?><?php echo $imsg ?></div>
    <div id=error><?php echo $err ?></div>
    
<?php
if ( !isset( $_SESSION[ 'logged_user' ])) {
print("<div id = 'imageinfo'>
       <p>Please <a href='login.php'>login to proceed.</a></p>
</div>");
}
?>
    
</body>
</html>
    
    
    
    
    
    
    