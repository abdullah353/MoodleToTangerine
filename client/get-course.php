<?php
require('../../../config.php');
$con=mysqli_connect($CFG->dbhost,$CFG->dbuser,$CFG->dbpass,$CFG->dbname);

// Check connection
if (mysqli_connect_errno($con))
  {
  $json .= "Failed to connect to MySQL: " . mysqli_connect_error();
  }else{
    mysqli_set_charset($con, "utf8");

	$coursequery = mysqli_query($con,'SELECT id, fullname FROM '.$CFG->prefix.'course');
	while($coursefetch = mysqli_fetch_array($coursequery)){
		if(isset($_POST['cid'])){
			if($coursefetch['id'] == $_POST['cid']){
				echo '<option value='.$coursefetch['id'].'>'.$coursefetch['fullname'].'</option>';
			}else{
	    		echo '<option value='.$coursefetch['id'].'>'.$coursefetch['fullname'].'</option>';	
				
			}
		}else{
			echo '<option value='.$coursefetch['id'].'>'.$coursefetch['fullname'].'</option>';
		}
	}
}