<?php 

  //--------------------------------------------------------------------------
  // Example php script for fetching data from mysql database
  //--------------------------------------------------------------------------
  include "../includes/config.php";
  		$rows = array();
		 
		$modifiedSince=$_REQUEST['modifiedSince'];
			
			
		if ($modifiedSince) { $sqlCriteria=" and lastModified > '$modifiedSince' "; }
		
	 $query="SELECT bulletID,bulletTitle,bulletDesc,dateAdded,lastModified from bulletins where 1=1 $sqlCriteria and published='yes' ";
	 

$result=mysql_query($query)or die(mysql_error()."Query is <b>$query</b>");	
		
while($row = mysql_fetch_assoc($result)){
    while($elm=each($row))
    {
        if(is_numeric($row[$elm["key"]])){
                    $row[$elm["key"]]=intval($row[$elm["key"]]);
        }
    }
    $rows[] = $row;
}   			
		 

		echo json_encode( $rows );

 

?>