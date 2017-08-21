<!DOCTYPE html>
<html>
<head>
    <title>Image Album</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css"> 
</head>

<body>

    
    <div id = "banner">
        <h1>Image Album</h1>
        <h2>Search Image</h2>
    </div>
    

    <?php include "nav.php";?>

    <div id = "form">
    <div class= "inputstyle">
    <h3>Search Images:</h3>
    <form action="search.php" method="post">
    
    Search Term: <br>
    <input type="text" name="term" maxlength="50"> <p></p>
    
    Select Fields: <br>
    <input type='checkbox' name='fields[]' value='title'/> Album Title <br>
    <input type='checkbox' name='fields[]' value='caption'/> Caption <br>
    <input type='checkbox' name='fields[]' value='file_path'/> File Path <br>
    <input type='checkbox' name='fields[]' value='credit'/> Credit <br>
    <p></p>
    <input type="submit" name="searchImages" value="Search Images"> <br>
    
    </form>
    </div>
    </div>
    
    <?php
        $none = "";
    
        if (isset($_POST['searchImages'])) {
            
            if (!empty($_POST['term']) && !empty($_POST['fields'])) {
                
                $term = filter_var($_POST['term'], FILTER_SANITIZE_STRING);
                $fields = implode(" LIKE '%$term%' OR ", $_POST['fields']);
                
                //Get the connection info for the DB. 
                require_once '../includes/config.php';
                $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
                //Was there an error connecting to the database?
                if ($mysqli->errno) {
                    print($mysqli->error);
                    exit();
                }
            
                $sql = "SELECT DISTINCT Images.pID, caption, file_path FROM Images
                LEFT JOIN Images_Albums ON Images.pID=Images_Albums.pID LEFT JOIN Albums
                ON Images_Albums.aID=Albums.aID WHERE $fields LIKE '%$term%';";
                $result = $mysqli->query($sql);
                
                print('<div id = "table">');
    
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
                print('</table>');
    
                print("</div>");
            }
        
        else {
            $none = "<p> There are no results. </p>";
        }
    }
    ?>
    
    <div id='none'>
    <?php echo $none ?>
    </div>
    
    
</body>
</html>