<?php
$url = $_POST['url'];
function file_get_contents_utf8($fn) { 
    $opts = array( 
        'http' => array( 
            'method'=>"GET", 
            'header'=>"Content-Type: text/html; charset=utf-8" 
        ) 
    );
}
$ch = curl_init();
$post_data3= file_get_contents('ggg.json');;
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// we are doing a POST request
curl_setopt($ch, CURLOPT_POST, 1);
// adding the post variables to the request
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data3);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));  
$output = curl_exec($ch);

curl_close($ch);

echo $output;