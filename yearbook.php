<?php include("includes/config.php") ?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
.yearbox {
	float: left;
	width: 200px;
	margin-right: 10px;
	height: 170px;
}
</style>
</head>



<body>


<?php  $query="SELECT * from yearbookStaff order by type,lastname,firstname";
$result=mysql_query($query)or die(mysql_error()."Query is <b>$query</b>");
$numrows=mysql_numrows($result);

while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	
	$fieldlist="";
	$valuelist="";
				foreach($row as $k => $v){$$k = $v;} 
				
				
				
						$lastName=str_replace(" ","_",$lastName);
						$firstName=str_replace(" ","_",$firstName);
					
						$lastName=str_replace("-","_",$lastName);
						$firstName=str_replace("-","_",$firstName);
	
						$lastName=str_replace(".","",$lastName);
						$firstName=str_replace(".","",$firstName);
					
						$lastName=str_replace("_.",".",$lastName);
						$firstName=str_replace("_.",".",$firstName);					
										
						$lastName=str_replace("'","",$lastName);
						$firstName=str_replace("'","",$firstName);	
						
						
				$imageLink=$type.'_'.$lastName.'_'.$firstName.'.jpg';	
				$imageLink=str_replace("_.",".",$imageLink);	
				$imageLink=str_replace("__","_",$imageLink);	
				
						if ($imagePresent=='NO'){
											
											$imageLink='noImage.jpg'; 
										}
										
										
										
			$imageLink=strtolower($imageLink);
			$imageLink= str_replace("winners","winner",$imageLink);	
				
?>


    
  <div class="yearbox">  <img src="yearbook/<?php echo $imageLink ?>" width="146" height="146" border=1><br>
<?php echo "$firstName $lastName" ?></div>
<? } ?>