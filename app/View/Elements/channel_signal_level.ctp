<?php
/****************************************************************************
 * channel_signal_level.ctp	- Show signal level of GSM channels in Text format (covert from dBm)
 * version 		        - 3.0.1500
 * 
 * Version: MPL 1.1
 *
 * The contents of this file are subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 *
 * The Initial Developer of the Original Code is
 *   Louise Berthilson <louise@it46.se>
 *
 *
***************************************************************************/

$result = false;

if(isSet($signal)){


        $data = explode(' ',$signal);
        $level = $data[0];

        $signal = (int)$level;


      if(!$signal){

      $result =  false;

      } elseif ($signal <= -90){

           $result =  __('Poor',true);

      } elseif ($signal > -90 && $signal <= -65){

           $result = __('Good',true);

      } elseif ($signal > -65 ){

           $result = __('Excellent',true);

      } else {

      $result = false;
      }




} elseif(isSet($gsmopen_signal)){


    if($gsmopen_signal== 0){
      $result = __('Poor',true);

    } elseif($gsmopen_signal== 1){

      $result = __('Good',true);
    } elseif($gsmopen_signal== 2){

      $result = __('Excellent',true);
    }
 


} elseif(isSet($gammu_signal)){


    if($gammu_signal <= 25){
      $result = __('Poor',true);

    } elseif($gammu_signal <= 75){

      $result = __('Good',true);
    } elseif($gammu_signal > 75){

      $result = __('Excellent',true);
    }
 

 }


          echo $result;


?>