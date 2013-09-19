<?php

require_once($CFG->libdir . "/externallib.php");

class local_sam_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function hello_world_parameters() {
        return new external_function_parameters(
                array('welcomemessage' => new external_value(PARAM_TEXT, 'The welcome message. By default it is "Hello world,"', VALUE_DEFAULT, 'Hello world, '),
                  'grabquizid' => new external_value(PARAM_TEXT, 'Quiz id', VALUE_DEFAULT, ''),
                  'autoGrade' => new external_value(PARAM_TEXT, 'Group Name', VALUE_DEFAULT, 'telfortest3'))
        );
    }

    /**
     * Returns welcome message
     * @return string welcome message
     */
    public static function hello_world($welcomemessage = 'Hello world, ',$grabquizid='',$autoGrade= 'disable') {
        global $USER;
        global $CFG;
        global $DB; 

        $params = self::validate_parameters(self::hello_world_parameters(),
        array('welcomemessage' => $welcomemessage,'grabquizid' => $grabquizid,'autoGrade'=>$autoGrade));
$json = '';
$json .= '{
  "docs": [
  ';
/*header('Content-Type: text/plain');
*/
$con=mysqli_connect($CFG->dbhost,$CFG->dbuser,$CFG->dbpass,$CFG->dbname);

// Check connection
if (mysqli_connect_errno($con))
  {
  $json .= "Failed to connect to MySQL: " . mysqli_connect_error();
  }else{
    mysqli_set_charset($con, "utf8");
/**
FETCHING CIURSE FIRST BASED ON ID OR NAME
*/
    /********************
    1) Getting The The id Of the Course
    *********************/
/*$courseid = 5;*/
$courseid = $params['welcomemessage'];
$autoGrade = $params['autoGrade'];
$explicitQuizid = $params['grabquizid'];
$convertingid = explode('#', $explicitQuizid);
$coursename = '';
  if ($courseid != '' || $coursename!= '' ) {
    if ($coursename!= ''){
        $coursequery = mysqli_query($con,'SELECT id FROM '.$CFG->prefix.'course  WHERE fullname ="'.$coursename.'" ');
        while($coursefetch = mysqli_fetch_array($coursequery)){
            $courseid = $coursefetch['id'];
        }
    }

    $coursequery2 = mysqli_query($con,'SELECT fullname,shortname FROM '.$CFG->prefix.'course  WHERE id ="'.$courseid.'" ');
    while($coursefetch2 = mysqli_fetch_array($coursequery2)){
        $assessName = $coursefetch2['fullname'];
        $grabedgroup = $coursefetch2['shortname'];
    }
    $grabedgroup = str_replace(' ', '_', $grabedgroup);
    $allRand = mt_rand();
    $allRand2 = mt_rand();

    /********************
    2) NoW Getting The list of Quiz For the Course ANd Making Survey Pages
    *********************/
    foreach ($convertingid as $value2) {

      if($value2 == ''){
        $quizquery = mysqli_query($con,'SELECT id, name FROM '.$CFG->prefix.'quiz WHERE course ="'.$courseid.'" ');
      }else{
        $quizquery = mysqli_query($con,'SELECT id, name FROM '.$CFG->prefix.'quiz WHERE id ="'.$value2.'" ');
      }

      $quizcount = mysqli_num_rows($quizquery);
      $quizcheck = 1;
      $quizIdArray = array();
      while($quizfetch = mysqli_fetch_array($quizquery)){
      $json .= '{
          "_id": "moodle-assess-'.$quizfetch['id'].'-'.$allRand.'",
          "assessmentId": "moodle-assess-'.$quizfetch['id'].'",
          "archived": false,
          "name": "'.$quizfetch['name'].'",
          "group": "'.$grabedgroup.'",
          "collection": "assessment",
          "sequences": [
              [
                null
              ]
          ]
      },{
   "studentDialog": "",
   "enumeratorHelp": "",
   "order": 1,
   "skippable": false,
   "prototype": "datetime",
   "name": "Time",
   "assessmentId": "moodle-assess-'.$quizfetch['id'].'-'.$allRand.'",
   "collection": "subtest"
},
      {
   "studentDialog": "",
   "enumeratorHelp": "",
   "order": 1,
   "skippable": false,
   "prototype": "location",
   "locations": [';
    require 'namefield.php';
    $json .= $usergenerated; 
   $json .='],
   "name": "What is your Name",
   "assessmentId": "moodle-assess-'.$quizfetch['id'].'-'.$allRand.'",
   "collection": "subtest",
   "transitionComment": "",
   "levels": [
       "Name"
   ],
   "gridLinkId": ""
},';
      array_push($quizIdArray, $quizfetch['id']);
      }
    
    /********************
    3) Time To Get Questions For Each Quiz
    *********************/

    $lastquiz = count($quizIdArray);
    $quizcount = 1;
    foreach ($quizIdArray as $value) {
        $quizQuestionquery = mysqli_query($con,'SELECT question FROM '.$CFG->prefix.'quiz_question_instances WHERE quiz ="'.$value.'" ');
        $number2 = mysqli_num_rows($quizQuestionquery);
        $lastQuestionCheck = 0;
        while($quizQuestion = mysqli_fetch_array($quizQuestionquery)){
            $question = mysqli_query($con,'SELECT * FROM '.$CFG->prefix.'question  WHERE id = "'.$quizQuestion['question'].'" ');
            $count2 = 1;
          
            while($row = mysqli_fetch_array($question)){
                $lastQuestionCheck++;
                if($row['qtype']== "multichoice"){$type = "single";
                }else{
                    $type = "open";
                }
                $question_answer = mysqli_query($con,'SELECT * FROM '.$CFG->prefix.'question_answers WHERE question ='.$row['id']);
               $last = $row['generalfeedback'];
               /*$last = str_replace(' </', '</', $last);*/
               require_once('rmv.php');
            $last = strtr($last, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
            $last = preg_replace('/^|\n|\r+$/m', '', $last);
            $last =  str_replace('"', '\'', $last);
            $last = str_replace(' ', '',$last);
            $last = str_replace('×', '&times;',$last);
            $empattern  = array('<!--[if gte mso 9]>','<!--[if gte mso 10]>','<![endif]-->');
            $last = str_replace($empattern, '', $last);
            $last = preg_replace("/<xml>(.+?)<\/xml>/s", '', $last);
            $last = preg_replace("/<style>(.+?)<\/style>/s", '', $last);
/*            $last = str_replace("dir='RTL'", '', $last);//should be last
            $last = str_replace("dir='rtl'", '', $last);//should be last*/
                $json .= '{
            "_id": "moodle-survey-'.$row['id'].'-'.$allRand2.'",
            "studentDialog": "",
            "enumeratorHelp": "'.$last.'",
            "order": '.$quizcheck.',
            "skippable": false,
            "prototype": "survey",
            "gridLinkId": "",
            "name": "Question: '.$row['id'].'",
            "assessmentId": "moodle-assess-'.$value.'-'.$allRand.'",
            "collection": "subtest",
            "transitionComment": "",
            "autostopLimit": 0
    },';

                $json .= '
                  { ';

                  $questionPrompt = mysqli_query($con,'SELECT DISTINCT * FROM '.$CFG->prefix.'question  WHERE id = "'.$row['id'].'" '); 
                        $varName = array('');
                        $randNumber = array('');
                  while($questionPromptResult = mysqli_fetch_array($questionPrompt)){
                    $prompt = $questionPromptResult['questiontext'];
                        $questionDatasets = mysqli_query($con,'SELECT * FROM `'.$CFG->prefix.'question_datasets`  WHERE question = "'.$row['id'].'" ');
                        /*$datasetCount = mysqli_num_rows($questionDatasets);*/
                        $datasetCounter = 0;
                        while($DatasetId = mysqli_fetch_array($questionDatasets)){
                          /*$id2 .= $DatasetId['datasetdefinition'].' \n';*/
                          $DatasetsOption = mysqli_query($con,'SELECT * FROM `'.$CFG->prefix.'question_dataset_definitions` WHERE id = "'.$DatasetId['datasetdefinition'].'" ');
                          while($DatasetFields = mysqli_fetch_array($DatasetsOption)){
                            $varOption = $DatasetFields['options'];
                            $arrayOption = explode(":",$varOption);
                            if (strpos($arrayOption['1'],'.') !== false || strpos($arrayOption['2'],'.') !== false) {
                              $min = intval($arrayOption['1']);
                              $max = intval($arrayOption['2']);
                              $randNumber[$datasetCounter] = $min + mt_rand() / mt_getrandmax() * ($max - $min);
                              $randNumber[$datasetCounter] = substr($randNumber[$datasetCounter], 0, 5);
                            }else{
                              $randNumber[$datasetCounter] = mt_rand($arrayOption['1'], $arrayOption['2']);
                            }
                            $varName[$datasetCounter] = $DatasetFields['name'];
                            /*$id2 .= $DatasetFields['name'].'\n'.$prompt.'<br>';*/
                            if(count($varName)==2 ){

                              /* $$ \frac{{'.$varName['0'].'}}{{'.$varName['1'].'}}\ $$ */
                              $seq1 = '$$ \frac{{'.$varName['0'].'}}{{'.$varName['1'].'}}\ $$';
                              $seq2 = '$$ \frac{{'.$varName['1'].'}}{{'.$varName['0'].'}}\ $$';
                              $reqSeq1 = '<div class=\'clear:both\'></div><div style=\'float:left;text-align:center;\'><div style=\'border-bottom:2px solid;\'>'.$randNumber['0'].'</div><div>'.$randNumber['1'].'</div></div><div class=\'clear:both\'></div>';
                              $prompt = str_replace($seq1 ,$reqSeq1 , $prompt);
                              $prompt = str_replace($seq2 ,$reqSeq1 , $prompt);

                              /* {'.$varName['0'].'} + {'.$varName['1'].'} */
                              $seq3 = '{'.$varName['0'].'} + {'.$varName['1'].'}';
                              $seq4 = '{'.$varName['1'].'} + {'.$varName['0'].'}';
                              $reSeq2 = $randNumber['0'].' + '.$randNumber['1'];
                              $prompt = str_replace($seq3 , $reSeq2 , $prompt);
                              $prompt = str_replace($seq4 , $reSeq2 , $prompt);

                              /* {'.$varName['0'].'} */
                              $seq5 = '{'.$varName['0'].'}';
                              $seq6 = '{'.$varName['1'].'}';
                              $reSeq3 = $randNumber['0'];
                              $reSeq4 = $randNumber['1'];
                              $prompt = str_replace($seq5 , $reSeq3 , $prompt);
                              $prompt = str_replace($seq6 , $reSeq4 , $prompt);

                              /* frac{{'.$varName['0'].'}}{{'.$varName['1'].'}} */
                              $seq7 = 'frac{{'.$varName['0'].'}}{{'.$varName['1'].'}}';
                              $seq8 = 'frac{{'.$varName['1'].'}}{{'.$varName['0'].'}}';
                              $reSeq5 = 'frac{{'.$randNumber['0'].'}}{{'.$randNumber['1'].'}}';
                              $prompt = str_replace($seq7 , $reSeq5 , $prompt);
                              $prompt = str_replace($seq8 , $reSeq5 , $prompt);

                            }
                          }
                          $datasetCounter++;
                          $datasetCount = $varName['0'].'  \n '.$varName['1'];
                          /*$prompt = str_replace('{'.$varName.'}' , $randNumber , $prompt);*/
                        }
                        

                        $prompt = str_replace('\ ','',$prompt);/*$$ \frac{2}{5}\ \div \frac{3}{5}\ $$$$\frac{3}{5}\ + \frac{1}{5}\ $$*/
                        $prompt = str_replace('\f','f',$prompt);

                        /*$prompt = str_replace('\frac{1}{5}\ ', '');*/
                        /*$prompt1 = trim($prompt,chr(0xC2).chr(0xA0));;*/
                        require('times.php');
                        require('plus.php');
                        $prompt = str_replace('\times', ' <span style=\'margin: 0px 10px;margin: 0px 2px 0px 15px;\'> &times; </span> ', $prompt);
                        $prompt = str_replace('\div', ' &divide; ', $prompt);
                        $prompt = str_replace('$$', ' ', $prompt);
                        $prompt = str_replace('÷', '&divide;', $prompt);
                        $prompt1 = preg_replace('/^|\n|\r+$/m', '', $prompt);
                        /*$prompt1 =  preg_replace('/"/', '\\\\\\\\\\\"', $prompt1);*/
                        $prompt1 =  str_replace('"', '\'', $prompt1);
                        $prompt1 = str_replace(' ', '',$prompt1);
                        $empattern = array('<!--[if gte mso 9]>','<!--[if gte mso 10]>','<![endif]-->');
                        $prompt1 = str_replace($empattern, '', $prompt1);
                        $prompt1 = preg_replace("/<xml>(.+?)<\/xml>/s", '', $prompt1);
                        $prompt1 = preg_replace("/<style>(.+?)<\/style>/s", '', $prompt1);
                        $prompt1 = str_replace('×', '&times;',$prompt1);
                  }     
                  $json .= '
                  "_id" : "question-'.$row['id'].'-'.mt_rand().'",
                  "prompt" : "<div class=\'clearfix\' style=\'direction:RTL;\'>'.$prompt1.'</div>",
                  "name" :"'.$row['name'].'",
                  "type" : "'.$type.'",
                  "assessmentId": "moodle-assess-'.$value.'-'.$allRand.'",
                  "collection": "question",
                  "subtestId":"moodle-survey-'.$row['id'].'-'.$allRand2.'",
                  "skipLogic": "",
                  "skippable": false,
                  "customValidationCode": "",
                  "customValidationMessage": "",
                  "options" : [
                        ';
                    if($type == "multiple" || $type == "single"){
                    $number = mysqli_num_rows($question_answer);
                  $count = 1;
                    while($row2 = mysqli_fetch_array($question_answer)){
                      $label = str_replace('\ ','',$row2['answer']);
                      $label = str_replace('\f','f', $label);
                      if(preg_match("/[0-9] frac{/", $label)){
                        if(preg_match("/[0-9][0-9] frac{/", $label)){
                          $label = str_replace(' frac{', 'frac{', $label);
                        }
                        $label = str_replace(' frac{', 'frac{', $label);
                      }
                      require('times-label.php');
                      $label = str_replace('$$',' ', $label);
                      $label = preg_replace('/^|\n|\r+$/m', '', $label);
                      $label =  str_replace('"', '\'', $label);
                      $label = str_replace(' ', '',$label);
                      $label = str_replace('×', '&times;',$label);
                      $empattern = array('<!--[if gte mso 9]>','<!--[if gte mso 10]>','<![endif]-->');
                      $label = str_replace($empattern, '', $label);
                      $label = preg_replace("/<xml>(.+?)<\/xml>/s", '', $label);
                      $label = preg_replace("/<style>(.+?)<\/style>/s", '', $label);
                      if($autoGrade == 'enabled'){
                        $json .= ' {
                              "label" : "<div class=\'clearfix\' style=\'margin-right: 15px;\'>'.$label.'</div>",
                              "value" : "'.intval($row2['fraction']).'"
                            ';
                      }else{
                        $empattern = array('<!--[if gte mso 9]>','<!--[if gte mso 10]>','<![endif]-->');
                        $ans = str_replace($empattern, '', $label);
                        $ans = preg_replace("/<xml>(.+?)<\/xml>/s", '', $label);
                        $ans = preg_replace("/<style>(.+?)<\/style>/s", '',$label);
                        $ans = strip_tags($label);
                        $json .= ' {
                              "label" : "<div class=\'clearfix\' style=\'margin-right: 15px;\'>'.$label.'</div>",
                              "value" : "'.$ans.'"
                            ';
                      }

                    if ($count < $number)
                       {
                           $json .= '},';
                       }else{
                          $json .= '}';
                       }
                       $count++;
                    }
                }
                  $json .= '
                    ]
                  ';
                  if ($quizcount < $lastquiz)
                     {
                         $json .= "},";
                     }else{
                        if($lastQuestionCheck < $number2 ){
                            $json .= '},';
                        }else{
                            $json .= '},';
                        }

                     }
            $count2++;
            }
        }
        $quizcount++;   
    }
  }
}
$json = substr($json, 0,-1);
$json .= '  ]
}';  
  }// For Else If Connected to database
  function TrimStr($str) 
{ 
    $str = trim($str); 
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
        return $json;;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function hello_world_returns() {
        return new external_value(PARAM_RAW, 'The welcome message + user first name');
    }



}
  