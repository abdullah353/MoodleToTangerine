<!DOCTYPE html>
<html>
<head>
    <title>XAM Plugin</title>
<script src="jquery.js"></script>
<script src="a.js"></script>

</head>
<body>
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
    <fieldset>
        <legend>Pick Up detail </legend>
        <div class="coursewrapper">
            <label>Select Course</label>
            <select name="cid" class="cidSelect" >
            <option value="-1">Select From List Below</option>
                <?php require 'get-course.php'; ?>
            </select>
        </div>
        <div class="quizwrapper">
            <label>Select Quiz</label>
            <select name="qid" class="qidSelect" multiple="multiple">
            <option value="-1">Select From List Below</option>
                <?php require 'get-quiz.php'; ?>
            </select>
        </div>
        <button class="generatebutton" type="submit" name="generate" value="start">Generate Assessment</button>
        <button class="submitToTangerine" type="submit" name="tangerine" value="upload">Submit To Tangerine</button>
        <?php 
        if(isset($_POST['cid']))
     /*   {   
            $courseid = $_POST['cid'];
            echo '<label>Select Quiz</label>
            <select name="qid"><option value="0">NONE SELECTED</option>';
                require 'get-quiz.php';
            echo '</select>';
            echo '<button type="submit" name="generate" value="start">Generate</button>';
        }else{
            echo '<button typ="submit">Search</button>';
        }*/
        ?>
        
    </fieldset>
</form>

<?php
/*$url = "http://tangerine.iriscouch.com/tangerine/_bulk_docs";
$ch = curl_init();
$post_data3= file_get_contents('new3.json');;
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// we are doing a POST request
curl_setopt($ch, CURLOPT_POST, 1);
// adding the post variables to the request
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data3);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));  
$output = curl_exec($ch);

curl_close($ch);

echo $output;*/
if(isset($_POST['generate']) && $_POST['generate'] = 'start'){


    $token = '8a3a48b9ccbbcced09d55f0a4a3e2624';
    $domainname = 'http://localhost/lms';

    /// FUNCTION NAME
    $functionname = 'local_sam_hello_world';

    /// PARAMETERS
    $welcomemsg = $_POST['cid'];
    $quizid = $_POST['qid'];
    ///// XML-RPC CALL

    $serverurl = $domainname . '/webservice/xmlrpc/server.php'. '?wstoken=' . $token;
    require_once('./curl.php');
    $curl = new curl;
    $post = xmlrpc_encode_request($functionname, array($welcomemsg,$quizid));
    $resp = xmlrpc_decode($curl->post($serverurl, $post));
    /*$resp= ;;
    $resp = removeEmptyTags($resp);

    $fp = fopen('new3.json', 'w');
    fwrite($fp, $resp);
    fclose($fp);
    /*echo $resp;*/

}







    function removeEmptyTags($html_replace) 
    { 
    $pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/"; 
    return preg_replace($pattern, '', $html_replace); 
    }

 function TrimStr($str){ 
    $str = trim($str);
    $ret_str = ''; 
    for($i=0;$i < strlen($str);$i++) 
    { 

        if(substr($str, $i, 1) != " ") 
        { 

            $ret_str .= trim(substr($str, $i, 1)); 

        } 
        else 
        { 
            while(substr($str,$i,1) == " ") 
           
            { 
                $i++; 
            } 
            $ret_str.= " "; 
            $i--; // *** 
        } 
    } 
    return $ret_str; 
} 
?>

</body>
</html>