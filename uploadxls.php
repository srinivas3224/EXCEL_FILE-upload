<?php

$con = mysql_connect("localhost","srinivas_mvr","mvrmvr");
mysql_select_db("srinivas_gps",$con);


if(!$con)
{
die('could not connect'. mysql_error());
}


include 'reader.php';
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$recins=0;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
        $uploadOk = 1;
    
}
echo "GUPS:".$target_file;

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "xls"  ) {
    echo "Sorry, only xls files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry,your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
<html>
  <head>
    <style type="text/css">
    table {
    	border-collapse: collapse;
    }        
    td {
    	border: 1px solid black;
    	padding: 0 0.5em;
    }        
    </style>
  </head>
  <body>
	<?php
	
    $excel = new Spreadsheet_Excel_Reader();
	?>
	Sheet 1:<br/><br/>
    <table>
    <?php
    $excel->read($target_file);    
    $x=6;
	function getZipCode($address) {
    $ok = preg_match("/(\d\d\d\d\d\d)/", $address, $matches);
    if (!$ok) {
        // This address doesn't have a ZIP code
    }
    return $matches[0];
}
	$sql = "INSERT INTO applications VALUES ";
	$maxid=1;

$maxidq="select Max(regno) from applications;";
$res99=mysql_query($maxidq);
$row99=mysql_fetch_row($res99);
$idno=$row99[0];
$maxid=$idno;


    while($x<=$excel->sheets[0]['numRows']) {
      echo "\t<tr>\n";
      $y=1;
	  
	  $maxid=$maxid+1;
	    $sql = " INSERT INTO applications VALUES (".$maxid.",";
		$pincode="123456";
      while($y<=18) {
	  
	    
	  
	  
	  if($y==14)
	  {
	  $pincode="123456";
	  $cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';

	  $cell=getZipCode($cell);
  echo "\t\t<td>$cell</td>\n";  
      $pincode=$cell;
	  
	   }
        $cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
        echo "\t\t<td>$cell</td>\n";  
        if( $y!=1 &&  $y!=2) 
		{
		
		$sql .="'".$cell."',";
		}
		
		
		$y++;
		
		
      }  
	  if($x==$excel->sheets[0]['numRows'])
	  {
	  $sql .="now(),now(),now(),now(),1,'".$pincode."');";
      
	  }
	  else
	  {
	  $sql .="now(),now(),now(),now(),1,'".$pincode."');";
      }
	  echo "\t</tr>\n";
	  
	  $res99=mysql_query($sql);
	 // echo "<br>dol res is====".$res99;
	  //echo "<br>dol res is====".$sql;
	  if($res99!=1)
	  {
	  echo "<h1>File no Dulicate for ". $excel->sheets[0]['cells'][$x][4]."</h1>";
	  }
	  else
	  {
	  $recins++;
	  }
      $x++;
    }
	
//	echo $sql;



if(!$con)
{
die('could not connect'. mysql_error());
}

    mysql_query( $sql, $con );
	printf("<h1>No of Record Inserted is : %d  And ready To Forward corresponding Stations</h1>\n",$recins);
	?>    
    </table><br/>
	<form method="post" action="../uploadro.php" id="myform">

<input type="submit" value="Back...">

</form>
	<form action="../rodatains.php">

<input type="submit" value="Forward  ALL Applications To Stations ">
</form>
  </body>
</html>
