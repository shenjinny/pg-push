<?php 

  //--------------------------------------------------------------------------
  // Example php script for fetching data from mysql database
  //--------------------------------------------------------------------------
  include "../includes/config.php";
  
  
   $posted=($_POST);
foreach ($posted  as $key => $value) {
	
				$value = iconv("UTF-8","ISO-8859-1//IGNORE",$value);
	
   	 			$$key=$value;
				$debug.="$key=$value \n";	
				
			
							
	            }
		
  $ip=$_SERVER['REMOTE_ADDR'];
  $debug.="ip is $ip";
  
 $mail = "output is $debug";

mail("scott@dreamshock.com","iPad Form", $mail, "From: PHPTesting <scott@dreamshock.com>");  
  
  
  
$query="insert into questionnaire (PE_fullName,PE_registration,PE_toursWeb,PE_infoAct,PE_amexService,PE_amexTime,PE_amexHelp,PE_accessSite,PE_webUseful,PE_websiteNav,PE_Comments,OS1_overall,OS1_overalTarget,OS1_CommentsHighlight,OS1_CommentsExpectations,OS1_CommentsEnhance,OS1_ManagementOverall,OS1_ManagementHelp,OS1_RegOnsite,OS1_RegHelp,OS1_TravelService,OS1_TravelDepInfo,OS1_TravelHelp,OS1_HotelQuality,OS1_Hotelfacilities,OS1_HotelHelp,OS1_toursHelp,OS1_CommentsTours,OS1_circleOverall,OS1_CommentsCircle,OS2_receptionOverall,OS2_receptionBuffet,OS2_kickoffOverall,OS2_kickoffspeaker,OS2_teamRest,OS2_TeamOverall,OS2_TeamDinner,OS2_miamiOverall,OS2_miamiBuffet,OS2_miamiEntertainment,OS2_GalaOverall,OS2_GalaDinner,OS2_GalaEntertainment,OS2_GiftsBags,OS2_GiftsYearbook,OS2_boseGifts,OS2_CommentsGeneral,dateAdded,debug,ipAddress) values ('$PE_fullName','$PE_registration','$PE_toursWeb','$PE_infoAct','$PE_amexService','$PE_amexTime','$PE_amexHelp','$PE_accessSite','$PE_webUseful','$PE_websiteNav','$PE_Comments','$OS1_overall','$OS1_overalTarget','$OS1_CommentsHighlight','$OS1_CommentsExpectations','$OS1_CommentsEnhance','$OS1_ManagementOverall','$OS1_ManagementHelp','$OS1_RegOnsite','$OS1_RegHelp','$OS1_TravelService','$OS1_TravelDepInfo','$OS1_TravelHelp','$OS1_HotelQuality','$OS1_Hotelfacilities','$OS1_HotelHelp','$OS1_toursHelp','$OS1_CommentsTours','$OS1_circleOverall','$OS1_CommentsCircle','$OS2_receptionOverall','$OS2_receptionBuffet','$OS2_kickoffOverall','$OS2_kickoffspeaker','$OS2_teamRest','$OS2_TeamOverall','$OS2_TeamDinner','$OS2_miamiOverall','$OS2_miamiBuffet','$OS2_miamiEntertainment','$OS2_GalaOverall','$OS2_GalaDinner','$OS2_GalaEntertainment','$OS2_GiftsBags','$OS2_GiftsYearbook','$OS2_boseGifts','$OS2_CommentsGeneral',Now(),'$debug','$ip')";
$result=mysql_query($query)or die(mysql_error());

	$id = mysql_insert_id();
	
	echo "Added Row - $id";

 

?>