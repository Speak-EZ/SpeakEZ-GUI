<?php
/****************************************************************************
 * add.ctp      - Create new poll
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
echo $this->Html->addCrumb(__('VoicePolls',true), '/voicePolls');
echo $this->Html->addCrumb(__('Create voice poll',true), '/voicePolls/add');

echo $this->Html->script('addRemoveElements');

echo $this->Session->flash();

echo "<h1>".__("Create Poll",true)."</h1>";
echo $this->Form->create('VoicePolls', array('type' => 'post','action'=> 'send','enctype' => 'multipart/form-data'));


echo $this->Html->div('frameLeft');
echo "<table cellpadding=0 class='blue'>";
echo $this->Html->tableCells(array(
    array(__("Poll Name",true), $this->Form->input('UploadMenu.name',array('label'=>false,'size' => '70'))),
    array(array(__("Poll name to refer when delivering",true),"colspan='2' class='formComment'")),
    array(__("Triage Rule: Text Message",true), $this->Form->input('UploadMenu.message',array('label'=>false,'size' => '70'))),
    array(array(__("Leave blank if not applicable. Use [poll_recipient] to substitute participant's phone number in message.",true),"colspan='2' class='formComment'")),
    array(__("Triage Rule: Recipient",true), $this->Form->input('UploadMenu.recipient',array('label'=>false,'size' => '70'))),
    array(array(__("Leave blank if not applicable. Use comma to split numbers. Put country code in front of each e.g. 13159483728",true),"colspan='2' class='formComment'"))
     ),array('class'=>'blue'),array('class'=>'blue'));
echo "</table>";




echo "<h2>".__("Poll Information",true)."</h2>";
echo "<div class='formComment'>".__("Question",true)."</div>";

echo "<table cellpadding=0 class='blue question_form'>";


echo $this->Html->tableCells(array (
    array(__("Question 1",true), $this->Form->input('UploadMenu.0.question',array('label'=>false))),
    array(__("Choice",true), $this->Form->input('UploadMenu.0.choice',array('label'=>false))),
    array(array(__("Choice must be in format n-m e.g. 0-3 or 1-5. Leave blank if not applicable",true),"colspan='2' class='formComment'")),
    array(__("Please select File that you want to upload",true), $this->Form->input('UploadMenu.0.file',array('label'=>false,'type'=>'file','size' => '50'))),
    array(__("Taking action when answer",true), $this->Form->input('UploadMenu.0.action_key',array('label'=>false))),
    array(array(__("Poll action would be taken if recipient response to this question by this answer. Leave blank if not applicable.",true),"colspan='2' class='formComment'")),
),array('class'=>'blue'),array('class'=>'blue'));


echo "</table>";

?>

<div id="doc">
<div id="content"></div>

</div>


<?php

/* - schedule is for later implementation
echo "<h2>".__("Start and end time",true)."</h2>";
echo "<div class='formComment'>".__("When would you like to open and close the poll?",true)."</div>";

echo "<table cellpadding=0 class='blue'>";
echo $this->Html->tableCells(array (
     array(__("Start time",true),       $this->Form->input('start_time',array('label'=>false))),
     array(__("End time",true),         $this->Form->input('end_time',array('label'=>false)))
      ),array('class'=>'blue'),array('class'=>'blue'));
echo "</table>";
*/


echo $this->Form->end(__('Save',true));
echo '<input id="add-element" type="button" value="'.__("Add",true).'"">';
echo "</div>";
?>



