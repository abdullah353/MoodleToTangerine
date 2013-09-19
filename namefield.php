<?php 
 $enrolquery = mysqli_query($con,'SELECT id FROM '.$CFG->prefix.'enrol WHERE roleid = 5 AND courseid = '.$courseid);
 $usergenerator = mysqli_fetch_array ($enrolquery);
 $userEnrollquery = mysqli_query($con,'SELECT userid FROM `'.$CFG->prefix.'user_enrolments` WHERE enrolid ='.$usergenerator['id']);
 while($userEnrollfetch = mysqli_fetch_array($userEnrollquery)){
 $userquery = mysqli_query($con,'SELECT firstname, lastname, username FROM `'.$CFG->prefix.'user` WHERE id = '.$userEnrollfetch['userid']);
      $usergenerator = mysqli_fetch_array ($userquery);      
		$usergenerated .= '[ "'.$usergenerator['firstname'].' '.$usergenerator['lastname'].' - '.$usergenerator['username'].'" ],';
	}
	$usergenerated = substr($usergenerated, 0,-1);
	
