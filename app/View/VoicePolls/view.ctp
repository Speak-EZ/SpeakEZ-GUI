<?php
/****************************************************************************
 * view.ctp	- View poll result
 * version 	- 3.0.1500
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

echo $this->Html->addCrumb(__('Voice Polls',true), '/voicePolls');


   if ($data){

        echo $this->Html->addCrumb(__('View',true), '/voicePolls/view/'.$data['poll_id']);
	       echo "<h1>".__("Poll",true).": ".$data['poll_name']." ";

	/*(echo $this->Html->image("icons/edit.png", array("alt" => __("Edit",true), "title" => __("Edit",true), "url" => array("controller" => "polls", "action" => "edit", $data['Poll']['id'])));
        echo "</h1>";*/
    /*
	echo "<h2>".__("Results",true)."</h2>";
      
        $total =  0;
        $total_percentage = 0;
        $total_early =  0;
        $total_closed =  0;
        echo "<table width='400px' cellspacing=0>";
        echo $this->Html->tableHeaders(array(__("Options",true), __("Votes",true), __("Percentage",true),__('Early votes',true),__('Late votes',true)),false, array('align' => 'center'));

	$votes = $data['Vote'];
	$invalid_open   = $data['Poll']['invalid_open'];
	$invalid_early  = $data['Poll']['invalid_early'];
	$invalid_closed = $data['Poll']['invalid_closed'];

	//Calculate total valid votes
	   foreach ($votes as $vote) {
	  
    	    $total = $total + $vote['chvotes'];
    	    $total_closed = $total_closed + $vote['votes_closed'];
    	    $total_early   = $total_early + $vote['votes_early'];

    	    }
    
           $total = $total + $invalid_open;
           $total_closed = $total_closed + $invalid_closed;
    	   $total_early = $total_early + $invalid_early;

	    foreach ($votes as $vote) {

    	    	if (!$total){ 
		   $percentage = 0;
		   } else { 
		   $percentage = $this->Number->toPercentage(100*$vote['chvotes']/$total,0);
		}

                

		$rows[] = array($vote['chtext'],array($vote['chvotes'],array('align'=>'center')),array($percentage,array('align'=>'center')),array($vote['votes_early'],array('align'=>'center')),array($vote['votes_closed'],array('align'=>'center')));
    	      }

	      //Add invalid votes (open)
	      if ($total) {
	      	 $percentage = $this->Number->toPercentage(100*$invalid_open/$total,0);
	      } else {
	      	$percentage=0;
		}


  	      $rows[] = array('"'.__('Invalid',true).'"',array($invalid_open,array('align'=>'center')),array($percentage,array('align'=>'center')),array($invalid_early,array('align'=>'center')), array($invalid_closed,array('align'=>'center')));

	      echo $this->Html->tableCells($rows);

  	      $final = array(false,$total,false, $total_early, $total_closed);
	      
	      echo $this->Html->tableHeaders($final,false, array('align' => 'center'));
	      echo "</table>";


	      //Poll information
     	      echo "<h2>".__("Poll information",true)."</h2>";
     	      echo "<table cellspacing=0 class='stand-alone'>";
	      echo $this->Html->tableCells(array (
     	      	   array(__("Status",true),	  $this->element('poll_status',array('status'=>$data['Poll']['status'],'mode'=>'text'))),
     		   array(__("Start time",true), $data['Poll']['start_time']),
     		   array(__("End time",true),	  $data['Poll']['end_time'])),array('class'=>'stand-alone'),array('class'=>'stand-alone'));
     	      echo "</table>";*/

            echo "<h2>".__("Results",true)."</h2>";

            // prepare response - group by question
            //print_r($data['poll_responses']);
            $result_by_question = array();
            for($i=1;$i<=$data['number_of_question'];$i++) {
                $result_by_question[$i] = array();
            }

            foreach($data['poll_responses'] as $key => $response) {
                if(array_key_exists($response['poll_response']['answer_key'], 
                                    $result_by_question[$response['poll_response']['question_id']])) {
                    // exist -> increase number
                    $result_by_question[$response['poll_response']['question_id']][$response['poll_response']['answer_key']]++;
                } else {
                    $result_by_question[$response['poll_response']['question_id']][$response['poll_response']['answer_key']] = 1;
                }

            }

            //print_r($result_by_question);

            // display result according to questions
            foreach($data['poll_questions'] as $key => $question) {
                echo '<div>';
                    echo '<div>'.$question['poll_question']['question_id'].') '.$question['poll_question']['question_text'].'</div>';
                    echo '<div> available choices: '.$question['poll_question']['available_choice'].'</div>';
                    echo '<blockquote>';
                    
                    $actual_responses = $result_by_question[$question['poll_question']['question_id']];
                    foreach($actual_responses as $answer_key => $val) {
                        echo '<div> answer:'.$answer_key.' count:'.$val.' </div>';
                    }
                    echo '</blockquote>';
                echo '</div>';
            }

            if(count($data['poll_rule']) == 1) {
                $poll_rule = $data['poll_rule'][0]['poll_rule'];
                $triggers = explode('|', $poll_rule['rule_trigger']);
                $actions = json_decode($poll_rule['action_taken']);

                echo '<div>';
                echo '<h2>Rule-Based Triage</h2>';
                echo '<h3>Conditions</h3>';
                echo '<div>The message below will be sent to <b>'.$actions->sms->recipient.'</b> once the following answers are answered by a participant:</div>';
                echo '<div><i>'.$actions->sms->message.'</i></div>';
                    echo '<blockquote>';
                    $trigger_index = 1;
                    foreach($triggers as $trigger){
                        echo '<div>Question: '.$trigger_index.' answer '.$trigger.'</div>';
                        $trigger_index++;
                    }
                    echo '</blockquote>';
                echo '</div>';


                if(count($data['poll_rule_batches']) > 0) {
                    //more data can be shown - future improvement
                    //print_r($data['poll_rule_batches']);
                    echo '<h3>Result</h3>'; 
                    echo '<div>'.count($data['poll_rule_batches']).' messages had been sent to poll adminstrator from the rule-based triage</div>';
                }
            }
        } else {

            echo $this->Html->div("invalid_entry", __("This page does not exist.",true));


        }

?>
