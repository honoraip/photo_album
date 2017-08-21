<!DOCTYPE html>
<html>
<head>
    <title>Image Album</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    
    <?php
	//Try to get the picture_id from a URL parameter
	$picture_id = filter_input( INPUT_GET, 'picture_id', FILTER_SANITIZE_NUMBER_INT );
		if( empty( $picture_id ) ) {
			//Try to get it from the POST data (form submission)
			$picture_id = filter_input( INPUT_POST, 'picture_id', FILTER_SANITIZE_NUMBER_INT );
		}
    
	//Get the connection info for the DB. 
	require_once '../includes/config.php';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        //Was there an error connecting to the database?
	if ($mysqli->errno) {
	    print($mysqli->error);
	    exit();
        }
        
        $sql = "SELECT * FROM Images";
	
	//Was there a picture specified?
		if( !empty( $picture_id ) ) {
			//Build the WHERE clause
			$sql .= " WHERE pID=$picture_id ";
		}
        
        //Finish off the SQL statement
	$sql .= ';';
        
        //Get the data
	$result = $mysqli->query($sql);
        
        $albumsql = "SELECT * FROM Images LEFT JOIN Images_Albums
        ON Images.pID=Images_Albums.pID LEFT JOIN Albums
        ON Images_Albums.aID=Albums.aID";
        
        //Was there a picture specified?
		if( !empty( $picture_id ) ) {
			//Build the WHERE clause
			$albumsql .= " WHERE Images.pID=$picture_id ";
		}
        
        //Finish off the SQL statement
	$albumsql .= ';';
        
        //Get the data
	$albums = $mysqli->query($albumsql);
        
        $albumlist = array();
        
        while ($row = $albums->fetch_assoc()) {
            $albumlist[] = "{$row[ 'title' ]}";
        }
        
        $albumresult = implode(", ",$albumlist);
    ?>
    
</head>

<body>
    
    <div id = "banner">
        <h1>Image Album</h1>
        <h2>Image Details</h2>
    </div>
    

    <?php include 'nav.php';?>
    
    <div>
    <?php
        while ($row = $result->fetch_assoc()) {
            print("<div id = 'imageinfo'>");
            print ("<p>Caption: {$row[ 'caption' ]}</p>");
            print ("<p>Albums: $albumresult</p>");
            print ("<p>Credit: {$row[ 'credit' ]}</p>");
            
            print ("<button onclick='goBack()'>Back to search results</button>");

            print ("<script>");
            print ("function goBack() {
            window.history.back();
            }");
            print ("</script>");
            
            print ("</div>");
            
            
            print ("<div id = 'imagedetail'>");
            print ("<img src='{$row[ 'file_path' ]}' alt='Image'>");
            print ("</div>");
            
        }
    ?>
    </div>

</body>
</html>