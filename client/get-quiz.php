<?php
$courseid = $_POST['courseid'];
$con=mysqli_connect($_POST['host'],$_POST['user'],$_POST['pass'],$_POST['dbname']);

// Check connection
if (mysqli_connect_errno($con))
  {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }else{
    mysqli_set_charset($con, "utf8");
	$quizquery = mysqli_query($con,'SELECT id, name FROM '.$_POST['prefix'].'quiz WHERE course ="'.$courseid.'" ');
	
	 while($quizfetch = mysqli_fetch_array($quizquery)){
	 	echo '<option value='.$quizfetch['id'].'>'.$quizfetch['name'].'</option>';
	 }
}