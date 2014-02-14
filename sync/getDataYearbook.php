<?php 

  //--------------------------------------------------------------------------
  // Example php script for fetching data from mysql database
  //--------------------------------------------------------------------------
  include "../includes/config.php";
  		$rows = array();
		
		$modifiedSince=$_REQUEST['modifiedSince'];
		
			
			
		if ($modifiedSince) { $sqlCriteria=" where lastModified > '$modifiedSince' "; }
		
		else {
						 $sqlCriteria="	 where lastModified >  '2013-04-02 00:00:00' ";
							
						}
		
		
	 $query="SELECT * FROM yearbookStaff $sqlCriteria";
	 

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