<?php 
$d = "<p>asfsafasf&nbsp;asf&nbsp;asf&nbsp;asf </p>";
$d = preg_replace('/ /','a',$d);
var_dump($d);