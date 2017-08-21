<!DOCTYPE html>
<html>
<head>
    <title>Image Album</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    
    <?php
	//Try to get the album_id from a URL parameter
	$album_id = filter_input( INPUT_GET, 'album_id', FILTER_SANITIZE_NUMBER_INT );
		if( empty( $album_id ) ) {
			//Try to get it from the POST data (form submission)
			$album_id = filter_input( INPUT_POST, 'album_id', FILTER_SANITIZE_NUMBER_INT );
		}
    
	//Get the connection info for the DB. 
	require_once '../includes/config.php';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        //Was there an error connecting to the database?
	if ($mysqli->errno) {
	    print($mysqli->error);
	    exit();
        }
        
        $sql = "SELECT * FROM Images INNER JOIN Images_Albums
        ON Images.pID=Images_Albums.pID";
	
	//Was there an album specified?
		if( !empty( $album_id ) ) {
			//Build the WHERE clause
			$sql .= " WHERE aID=$album_id ";
		}
        
        //Finish off the SQL statement
	$sql .= ';';
        
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
    
    <div id = "banner">
        <h1>Image Album</h1>
        <h2>Images from Specific Album</h2>
    </div>
    

    <?php include 'nav.php';?>
    
    <div id = "table">
    
   <?php   
        print("<table><tbody>");
	
	print( "<tr>" );
	
	$count = 1;
            
        while ($row = $result->fetch_assoc()) {
            
            $picture_id = $row['pID'];
	    $href = "imagedetail.php?picture_id=$picture_id";
            print("<td><a href='$href' title='$href'>
	    <img src='{$row[ 'file_path' ]}' alt='Image'></a><p></p>
	    {$row[ 'caption' ]}</td>");
	    
	    if ($count%3 == 0) {
		print ("<tr>");
	    }
            
	    $count ++;
        }
        
        print('</tbody>');
        print('</table>')
    ?>
    
    </div>

</body>
</html>




