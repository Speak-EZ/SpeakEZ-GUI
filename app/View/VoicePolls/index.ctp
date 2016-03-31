<?php
/****************************************************************************
 * index.ctp    - List polls with view, edit and delete options
 * version      - 3.0.1500
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
echo $this->Html->addCrumb(__('VoicePoll',true), '/voicePoll');


    $this->Session->flash();

   $this->Access->showButton($authGroup, 'voicePoll', 'add', 'frameRightTrans', __('Create New Poll',true), 'submit', 'button');
   $this->Access->showButton($authGroup, 'voicePoll', 'sendvoicepoll', 'frameRightTrans', __('Deliver Poll',true), 'submit', 'button');

   echo "<h1>".__("Voice Polls",true)."</h1>";


   /*if ($polls){

     foreach ($polls as $key => $poll){

             $votes=$poll['Poll']['invalid_open'];
             foreach($poll['Vote'] as $option){

                $votes = $votes + $option['chvotes'];
             
             }

           $question = $this->Html->link($poll['Poll']['question'],"/polls/view/{$poll['Poll']['id']}");
           $code     = $poll['Poll']['code'];
           $start    = $this->Time->format('Y/m/d H:i',$poll['Poll']['start_time']);
           $end      = $this->Time->format('Y/m/d H:i',$poll['Poll']['end_time']);

           $view = $this->Html->link(
                        $this->Html->image("icons/view.png"),
                        "/polls/view/".$poll['Poll']['id'],
                        array("escape" => false, "title" => __("View results", true), "onClick" => "Modalbox.show(this.href, {title: this.title, width: 500}); return false;"),
                        false);



           $edit     = $this->Access->showBlock($authGroup , $this->Html->image("icons/edit.png", array("alt" => __("Edit",true), "title" => __("Edit",true), "url" => array("controller" => "polls", "action" =$

           $delete   = $this->Access->showBlock($authGroup, $this->Html->image("icons/delete.png", array("alt" => __("Delete",true), "title" => __("Delete",true), "url" => array("controller" => "polls", "acti$

        $row[$key] = array(
                array($this->element('poll_status',array('status'=>$poll['Poll']['status'],'mode'=>'image')),array('align'=>'center')),
                array($question,array('align'=>'left','width' => '200px')),
                array($code,array('align'=>'center','width' => '50px')),
                array($votes,array('align' =>'center')),
                array($start,array('align' =>'center', 'width' => '150px')),
                array($end,array('align' =>'center', 'width' => '150px')),
                array($view.' '.$edit.' '.$delete,array('align'=>'center','width' => '100px')));

     }

    echo "<table cellspacing =0 width='90%'>";
    echo $this->Html->tableHeaders(array(__("Status",true),__("Question",true),__("Code",true),__("Total Votes",true),__("Open",true),__("Close",true),__("Actions",true)), false, array('align' => 'center'));
    echo $this->Html->tableCells($row);
    echo "</table>";

    echo $this->Html->div('system_time',__('System time',true).' : '.$this->Time->format('H:i:s A (e \G\M\T O)',time()));

   }  else {

        echo $this->Html->div('feedback', __('No polls exist. Please create one by clicking the <i>Create new</i> button to the right.',true));

   }*/

   //preparing poll data
    foreach($poll_group as $key => $poll) {
        $unique_ptcp = 0;
        foreach ($poll_response_unique_ptcp as $resp_key => $object) {
            if($object['poll_response']['poll_id'] == $poll['poll_group']['poll_id']) {
                $unique_ptcp = $object[0]['count_unique'];
                break;
            }
        }

        $row[$key] = array(
                      array($poll['poll_group']['poll_id'], array('align'=>'center')),
                      array($poll['poll_group']['poll_name'], array('align'=>'left','width' => '200px')),
                      array($poll['poll_group']['number_of_question'], array('align'=>'center','width' => '50px')),
                      array($unique_ptcp, array('align' =>'center')),
                      array($poll['poll_group']['date_created'], array('align' =>'center', 'width' => '150px')),
                      array( $this->Html->link(__("Views",true),'/voicePolls/view/'.$poll['poll_group']['poll_id']), array('align' =>'center', 'width' => '150px'))
                  );
    }

    echo "<table cellspacing =0 width='90%'>";
    echo $this->Html->tableHeaders(
          array(__("ID",true),__("Poll Name",true),__("No of Questions",true),__("No of PTCPs",true),__("Date",true),__("Actions",true)), false, array('align' => 'center'));
    echo $this->Html->tableCells($row);
    echo "</table>";

    echo $this->Html->div('system_time',__('System time',true).' : '.$this->Time->format('H:i:s A (e \G\M\T O)',time()));



?>




