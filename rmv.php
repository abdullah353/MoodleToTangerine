<?php
/*$last = TrimStr($last);*/
               $last =preg_replace('/   <\//', '</', $last);
               $last =preg_replace('/  <\//', '</', $last);
               $last =preg_replace('/ <\//', '</', $last);
               $last = preg_replace('/^|\n|\r+$/m', '', $last);
              $last =  str_replace('"', '\'', $last);
              