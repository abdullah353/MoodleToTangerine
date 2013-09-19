<?php
                              $test23 = preg_split("/frac{/", $prompt);
                              $test233 = explode("{", $test23['1'],2);
                              $grabedNumber2 =str_replace("}", "", $test233['0']);
                              $intermidate =explode("}",$test233['1'], 2);
                              $grabedNumber3 = $intermidate['0'];
                              if(preg_match("/[0-9]\z/", $test23['0'])){
                                $got = -1;
                                if(preg_match("/[0-9][0-9]\z/", $test23['0'])){
                                  $got = -2;
                                }
                                if (preg_match("/[0-9][0-9][0-9]\z/", $test23['0'])) {
                                  $got = -3;
                                }
                              }else{
                                $got = '';
                              }
                              if($got != ''){
                                $fracconstant = substr($test23['0'], $got);
                              }else{
                                $fracconstant = '';
                              }
                              $seq9 = $fracconstant.'frac{'.$grabedNumber2.'}{'.$grabedNumber3.'}';
                              if($fracconstant != ''){
                                $reqfrac1 = '<div style=\'float: left;padding-top: 3px;padding-right: 5px;\'>'.$fracconstant.'</div>';
                                $reqfrac1 .= '<div style=\'float:left;font-size: 14px;text-align: center;\'><div style=\'border-bottom:2px solid;\'><strong>'.$grabedNumber2.'</strong></div><div><strong>'.$grabedNumber3.'</strong></div></div>'; 
                              }else{
                                $reqfrac1 = '<div style=\'float:left;font-size: 14px;text-align: center;\'><div style=\'border-bottom:2px solid;\'><strong>'.$grabedNumber2.'</strong></div><div><strong>'.$grabedNumber3.'</strong></div></div>'; 
                              }
                              if(sizeof($test23 > 2)){
                                $test234 = explode("{", $test23['2'],2);
                                $grabedNumber4 =str_replace("}", "", $test234['0']);
                                $intermidate =explode("}",$test234['1'], 2);
                                $grabedNumber5 = $intermidate['0'];
                                if(preg_match("/[0-9]\z/", $test23['1'])){
                                $got = -1;
                                if(preg_match("/[0-9][0-9]\z/", $test23['1'])){
                                  $got = -2;
                                }
                                if (preg_match("/[0-9][0-9][0-9]\z/", $test23['1'])) {
                                  $got = -3;
                                }
                              }else{
                                $got = '';
                              }
                              if($got != ''){
                                $fracconstant = substr($test23['1'], $got);
                              }else{
                                $fracconstant = '';
                              }
                              }
                              $seq10 = $fracconstant.'frac{'.$grabedNumber4.'}{'.$grabedNumber5.'}';
                              if($fracconstant != ''){
                                $reqfrac2 = '<div style=\'float: left;padding-top: 3px;padding-right: 5px;\'>'.$fracconstant.'</div>';
                                $reqfrac2 .= '<div style=\'float:left;font-size: 14px;text-align: center;\'><div style=\'border-bottom:2px solid;\'><strong>'.$grabedNumber4.'</strong></div><div><strong>'.$grabedNumber5.'</strong></div></div>'; 
                              }else{
                                $reqfrac2 = '<div style=\'float:left;font-size: 14px;text-align: center;\'><div style=\'border-bottom:2px solid;\'><strong>'.$grabedNumber4.'</strong></div><div><strong>'.$grabedNumber5.'</strong></div></div>'; 
                              }

                              $timeSeries = array($seq9.' \times'.$seq10, $seq9.' \times '.$seq10, $seq9.'\times '.$seq10, $seq9.'\times'.$seq10);
                              $prompt = str_replace($timeSeries, '<div>&nbsp;</div>'.$reqfrac1.'<div style="float: left;margin: 0px 10px;">x</div>'.$reqfrac2.'<div>&nbsp;</div>', $prompt);

                              $timeSeries = array($seq9.' \div'.$seq10, $seq9.' \div '.$seq10, $seq9.'\div '.$seq10, $seq9.'\div'.$seq10);
                              $prompt = str_replace($timeSeries, '<div>&nbsp;</div>'.$reqfrac1.'<div style="float: left;margin: 0px 10px;">&divide;</div>'.$reqfrac2.'<div>&nbsp;</div>', $prompt);

                              $timeSeries = array($seq9.' -'.$seq10, $seq9.' - '.$seq10, $seq9.'- '.$seq10, $seq9.'-'.$seq10);
                              $prompt = str_replace($timeSeries, '<div>&nbsp;</div>'.$reqfrac1.'<div style="float: left;margin: 0px 10px;">-</div>'.$reqfrac2.'<div>&nbsp;</div>', $prompt);

                              $timeSeries = array($seq9.' +'.$seq10, $seq9.' + '.$seq10, $seq9.'+ '.$seq10, $seq9.'+'.$seq10);
                              $prompt = str_replace($timeSeries, '<div>&nbsp;</div>'.$reqfrac1.'<div style="float: left;margin: 0px 10px;">+</div>'.$reqfrac2.'<div>&nbsp;</div>', $prompt);

                              $timeSeries = array($seq9.' ='.$seq10, $seq9.' = '.$seq10, $seq9.'= '.$seq10, $seq9.'='.$seq10);
                              $prompt = str_replace($timeSeries, '<div>&nbsp;</div>'.$reqfrac1.'<div style="float: left;margin: 0px 10px;">=</div>'.$reqfrac2.'<div>&nbsp;</div>', $prompt);

                              $timeSeries = array($seq9);
                              $prompt = str_replace($timeSeries, '<div class=\'display:inline;margin-right:10px;\'>'.$reqfrac1.'</div>', $prompt);

                              $timeSeries = array($seq10);
                              $prompt = str_replace($timeSeries, '<div class=\'display:inline;margin-right:10px;\'>'.$reqfrac2.'</div>', $prompt);