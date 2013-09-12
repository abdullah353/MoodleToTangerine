<!DOCTYPE html>
<html>
<head>
    <title>XAM Plugin</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
    <fieldset>
        <legend>Pick Up detail </legend>
        <div class="stat">
        </div>
        <div class="coursewrapper">
            <label>Select Course</label>
            <select name="cid">
            <option value="-1">Select From List Below</option>
                <?php require 'get-course.php'; ?>
            </select>
        </div>

        <div class="quizwrapper hidden">
            <div>Select Quiz</div>
            <select class="qselect" name="qid[]" multiple="multiple">
            
            </select>
        </div>
        <div class="groupwrapper hidden">
            <div>Type Group Name</div>
            <input name="gname" value="telfortest3" placeholder="telfortest3">
        </div>
        <div class="cloudwrapper hidden">
            <label>Type url </label>
            <input name="cname" value="192.168.1.110:5984" placeholder="192.168.1.110:5984">
        </div>
        <button class="generatebutton" type="submit" name="generate" value="start" disabled="disabled">Generate Assessment</button>
        <button class="localgeneratebutton" name="localgenerate" value="lupdate" disabled="disabled">Submit To Local URL</button>
        <button class="submitToTangerine" name="tangerine" value="upload" disabled="disabled">Submit To Tangerine</button>
    </fieldset>
</form>
<?php 
require_once('../../../config.php');
if(isset($_POST['generate']) && $_POST['generate'] = 'start'){


    $token = '<YOUR TOKEN HERE>';
    $domainname = $CFG->wwwroot;

    /// FUNCTION NAME
    $functionname = 'local_sam_hello_world';

    /// PARAMETERS
    $quizid ='';
    $welcomemsg = $_POST['cid'];
    $groupname = $_POST['gname'];
    if(isset($_POST['qid'])){
        $quizid = implode('#', $_POST['qid']);
    }
    ///// XML-RPC CALL

    $serverurl = $domainname . '/webservice/xmlrpc/server.php'. '?wstoken=' . $token;
    require_once('./curl.php');
    $curl = new curl;
    $post = xmlrpc_encode_request($functionname, array($welcomemsg,$quizid,$groupname));
    $resp = xmlrpc_decode($curl->post($serverurl, $post));
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
$resp = TrimStr($resp);
    $fp = fopen('ggg.json', 'w');
    fwrite($fp, $resp);
    fclose($fp);
    /*echo $resp;*/
}

 ?>
<script src="js/jquery.min.js"></script>
<script type="text/javascript">
var url = "http://localhost/lms/local/sam/client/";
    (function(){
    
    if(jQuery('select[name="cid"]').val() !== '-1'){
            console.log("should disabled"); 
            jQuery.post("get-quiz.php", { "courseid": jQuery('select[name="cid"]').val() },
              function(data){
               
                jQuery(".qselect").html(data);
                jQuery(".quizwrapper").fadeIn();
                jQuery(".groupwrapper").fadeIn();
                jQuery('.generatebutton').removeAttr('disabled');
            });
    }

    jQuery('select[name="cid"]').change(function(){
        var cid= jQuery(this).val();
        console.log(cid); 
        if(cid !== '-1'){
            console.log("should disabled"); 
            var host = "<?php echo $CFG->dbhost; ?>";
            var user = "<?php echo $CFG->dbuser; ?>";
            var pass = "<?php echo $CFG->dbpass; ?>";
            var dbname = "<?php echo $CFG->dbname; ?>";
            var prefix = "<?php echo $CFG->prefix; ?>";
            console.log(host+dbname);
            jQuery.post("get-quiz.php", { "courseid": cid,"host": host,"user": user,"pass": pass,"dbname": dbname,"prefix": prefix },
              function(data){
                jQuery(".qselect").html(data);
                jQuery(".quizwrapper").fadeIn();
                jQuery(".groupwrapper").fadeIn();
                jQuery('.generatebutton').removeAttr('disabled');
            });
        }

    });

    jQuery('select[name="qid"]').change(function(){
        var abc= jQuery(this).val();  
        console.log(abc);       
    });
    jQuery('.localgeneratebutton').click(function(e){
        e.preventDefault();
        var surl = 'http://'+jQuery('input[name="cname"]').val()+'/tangerine/_bulk_docs';
        console.log(' sent to '+surl);
        jQuery.post("samcurl.php", { "url":  surl},
              function(data){
                console.log(data); // John
/*                var obj = jQuery.parseJSON(data);
                var sizeOfObj = obj.length;
                var stat = '';
                for (var i = 0; i < sizeOfObj ; i++) {
                    stat = stat +"<p>";
                    jQuery.each( obj[i], function( key, value ) {
                      stat = stat + key + " : " + value ;
                    });
                    stat = stat +"</p>"
                };*/
                jQuery('.stat').html(data);
        });       
    });

    jQuery('.submitToTangerine').click(function(e){
        e.preventDefault();
        var surl = "http://tangerine.iriscouch.com/tangerine/_bulk_docs";
        console.log(' sent to '+surl);
        jQuery.post("samcurl.php", { "url":  surl},
              function(data){
                console.log(data); // John
                jQuery('.stat').html(data);
        });       
    });    
})();
</script>
<?php if(isset($_POST['generate']) && $_POST['generate'] = 'start'){ ?>

<script type="text/javascript">
    jQuery(".quizwrapper").fadeIn();
    jQuery(".groupwrapper").fadeIn();
    jQuery(".cloudwrapper").fadeIn();
    jQuery('.submitToTangerine').delay(3000).removeAttr('disabled');
    jQuery('.localgeneratebutton').delay(3000).removeAttr('disabled');
</script>
<?php } ?>
</body>
</html>