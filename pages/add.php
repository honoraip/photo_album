<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Image Album</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
        
    <?php
	//Get the connection info for the DB. 
	require_once '../includes/config.php';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        //Was there an error connecting to the database?
	if ($mysqli->errno) {
	    print($mysqli->error);
	    exit();
        }
        
        $sql = "SELECT * FROM Albums";
        
        //Get the data
	$result = $mysqli->query($sql);
			
	//If no result, print the error
	if (!$result) {
	    print($mysqli->error);
	    exit();
        }
        
    ?>
    
</head>

<body>
    
<?php
    $albummsg = "";
    $albumerr = "";

    if (isset($_POST['addAlbum'])) {
    
	if (!empty($_POST['title'])) {
	    /*Check for duplicates. If there is a duplicate, return true*/
	    function duplicate($input) {
		//Get the connection info for the DB. 
		require_once '../includes/config.php';
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            
		$sql = "SELECT * FROM Albums;";
		$result = $mysqli->query($sql);
            
		while ($row = $result->fetch_assoc()) {
		    if ($row[ 'title' ] == $input) {
			return true;
		    }
		}
            
		//Otherwise
		return false;
	    }
        
	    if (duplicate(ucwords($_POST['title']))) {
		$title = "";
		$albumerr.= "<p>There is already an entry with the same title</p>";
	    }
        
	    else {
		$title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
		$date = date("Y-m-d");
	    }
        
	    /*Adding new album */
	    if ($title != "") {
		$field_values = array (
		    'title' => $title,
		    'date_created' => $date,
		    'date_modified' => $date,
		    'aCover' => '../images/default.jpg'
		);
            
		$field_name_array = array_keys($field_values);
		$field_list = implode( ',', $field_name_array );
		$value_list = implode( "','", $field_values );
            
		$addAlbum = "INSERT INTO Albums ($field_list) VALUES ('$value_list');";
		$mysqli->query( $addAlbum );
		$albummsg .= "<p>Successfully added album.<p>";
	    }
	}
	
	else {
		$albumerr .= "<p> Album is not added.<p>";
	}
    }
    
?>

<?php
    print '<pre style="display:none;">' . print_r( $_FILES, true ) . '</pre>';
    
    $msg = "";
    $err = "";
    
    function uploadtoimages($caption,$file_path,$credit) {
        //Get the connection info for the DB. 
        require_once '../includes/config.php';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        $sql = "SELECT * FROM Images;";
        $result = $mysqli->query($sql);
        
        $field_values = array (
                'caption' => $caption,
                'file_path' => $file_path,
                'credit' => $credit
            );
            
            $field_name_array = array_keys($field_values);
            $field_list = implode( ',', $field_name_array );
            $value_list = implode( "','", $field_values );
            
            $addImage = "INSERT INTO Images ($field_list) VALUES ('$value_list');";
            $mysqli->query( $addImage );
            
            $pID = $mysqli->insert_id;
            return $pID;
    }
    
    function linktoalbum($pID,$aID) {
        //Get the connection info for the DB. 
        require_once '../includes/config.php';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        $sql = "SELECT * FROM Images_Albums;";
        $result = $mysqli->query($sql);
        
        $field_values = array (
                'pID' => $pID,
                'aID' => $aID
            );
        
        $field_name_array = array_keys($field_values);
        $field_list = implode( ',', $field_name_array );
        $value_list = implode( "','", $field_values );
        
        $linkAlbum = "INSERT INTO Images_Albums ($field_list) VALUES ('$value_list');";
        $mysqli->query( $linkAlbum );
    }
    
    function updatealbum($aID,$date) {
        //Get the connection info for the DB. 
        require_once '../includes/config.php';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        $sql = "SELECT * FROM Albums.aID WHERE aID=$aID;";
        $result = $mysqli->query($sql);
          
        $field_values = array (
                'date_modified' => $date
            );
        
        $update_fields = array();
        
        foreach( $field_values as $field_name => $field_value ) {
            $update_fields[] = "$field_name = '$field_value'";
        }   
        $sets = implode( ', ', $update_fields );
        
        $updateAlbum = "UPDATE Albums SET $sets WHERE aID=$aID";
        $mysqli->query( $updateAlbum );
    }
    
    if (isset($_POST['uploadPhoto'])) {
	
	if (!empty( $_FILES[ 'newphoto' ])
	&& !empty($_POST['caption']) && !empty($_POST['credit'])) {
	    $newPhoto = $_FILES[ 'newphoto' ];
	    $originalName = $newPhoto[ 'name'];

	    if ($newPhoto[ 'error' ] == 0) {
		$tempName = $newPhoto[ 'tmp_name' ];
		move_uploaded_file( $tempName, "../images/$originalName");
		$_SESSION['photos'][] = $originalName;
            
		$caption = filter_var($_POST['caption'], FILTER_SANITIZE_STRING);
		$file_path = "../images/$originalName";
		$credit = filter_var($_POST['credit'], FILTER_SANITIZE_STRING);
		$date = date("Y-m-d");
            
		if (!empty($_POST['albums'])) {
		    $albums = $_POST['albums'];
		}
            
		else {
		$albums = array(0);
		}
            
		foreach($albums as $album) {
                
		    if ($album > 0) {
			linktoalbum(uploadtoimages($caption,$file_path,$credit),$album);
			updatealbum($album,$date);
			$msg .= "<p>Successfully added image.<p>";
			$msg .= "<p>Successfully updated album.<p>";
		    }
                
		    else {
			linktoalbum(uploadtoimages($caption,$file_path,$credit),$album);
			$msg .= "<p>Successfully added image.<p>";
		    }
		}
	    }
	}
	
	else {
	    $err .= "<p> Image is not added. <p>";
	}
    }
?>
    
    <div id = "banner">
        <h1>Image Album</h1>
        <h2>Add New Albums & Images</h2>
    </div>
    

    <?php include "nav.php";?>
    
    <?php
    if ( isset( $_SESSION[ 'logged_user' ])) {
    print
    ("<div id = 'form'>
    <div id = 'formalbum'>
    <div class= 'inputstyle'>
    <form action='add.php' method='post'>
    <h3>Add a new album:</h3>
    
    Album Title: <br>
    <input type='text' name='title' maxlength='50'> <p></p>
    
    <input type='submit' name='addAlbum' value='Add Album'> <br>
    
    </form>
    </div>
    </div>");
    }
    ?>
    
<?php
    if ( isset( $_SESSION[ 'logged_user' ])) {
    print('<div id="formimage">');
    print('<div class="inputstyle">');
    print('<h3>Add a new image:</h3>');
    
    print("<form method='post' enctype='multipart/form-data'>");
    
    print('Caption: <br>');
    print("<input type='text' name='caption' maxlength='50'> <p></p>");
    
    print("Single photo upload: <input type='file' name='newphoto'/><p></p>");
    
    print("Upload to albums:<br>");
    while ($row = $result->fetch_assoc()) {
        print("<input type='checkbox' name='albums[]' value='{$row['aID']}'/>
        {$row['title']} <br>");
    }
    
    print('<p></p>');
    print('Credit: <br>');
    print("<input type='text' name='credit' maxlength='50'> <p></p>");
    
    print('<p></p>');
    print("<input type='submit' name = 'uploadPhoto' value='Upload photo'/>");
    print('</div>');
    print('</form>');
    
    print('</div>');
    print('</div>');
    }
?>
 
<div id=success><?php echo $albummsg ?><?php echo $msg ?></div>

<div id=error><?php echo $albumerr ?><?php echo $err ?></div>

<?php
    if ( !isset( $_SESSION[ 'logged_user' ])) {
    print("<div id = 'imageinfo'>
    <p>Please <a href='login.php'>login to proceed.</a></p>
    </div>");
    }
?>

</body>
</html>


