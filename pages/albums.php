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
        
       $sqlrows = "SELECT * FROM Albums;";
        
        //Get the data
	$rows = $mysqli->query($sqlrows);
			
	//If no result, print the error
	if (!$rows) {
	    print($mysqli->error);
	    exit();
        }
        
        $row_count = $rows->num_rows;
        
        $count = 1;
        
        while ($count <= $row_count) {
            
            $cover = "SELECT aCover FROM Albums WHERE aID=$count;";
            $coverdata = $mysqli->query($cover);
            
            while ($row = $coverdata->fetch_assoc()) {
            
                if (empty($row[ 'aCover' ])) {
                    $sets = "aCover = '../images/default.jpg'";
                
                    $setcover = "UPDATE Albums SET $sets WHERE aID=$count;";
                    $mysqli->query($setcover);
                }
            }
            
            $count=$count+1;
        }
        
        $sql = "SELECT * FROM Albums;";
        
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
        <h2>All Albums</h2>
    </div>
    

    <?php include "nav.php";?>
    
    <div id = "table">
    
    <?php   
        print("<table><tbody>");
	
	$count = 1;
	
	print ("<tr>");
            
        while ($row = $result->fetch_assoc()) {
            
            $album_id = $row['aID'];
	    $href = "images.php?album_id=$album_id";
            print("<td><a href='$href' title='$href'>
	    <img src='{$row[ 'aCover' ]}' alt='Image'></a><p></p>
	    {$row[ 'title' ]}</td>");
	    
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






