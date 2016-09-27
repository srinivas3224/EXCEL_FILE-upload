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
	include 'reader.php';
    $excel = new Spreadsheet_Excel_Reader();
	?>
	Sheet 1:<br/><br/>
    <table>
    <?php
    $excel->read('7-5-15.xls');    
    $x=6;
    while($x<=$excel->sheets[0]['numRows']) {
      echo "\t<tr>\n";
      $y=1;
      while($y<=18) {
        $cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
        echo "\t\t<td>$cell</td>\n";  
        $y++;
      }  
      echo "\t</tr>\n";
      $x++;
    }
    ?>    
    </table><br/>
	
	
  </body>
</html>
