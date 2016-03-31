<?php
/****************************************************************************
 * add.ctp	- Create new poll
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
echo $this->Html->addCrumb(__('VoicePolls',true), '/voicePolls');
echo $this->Html->addCrumb(__('Send Voice Poll',true), '/voicePolls/sendvoicepoll');

echo $this->Html->script('addRemoveElements');

echo "<h1>".__("Send Voice Poll",true)."</h1>";

echo "<div>";

$selection_form = '';
$selection_form.= $this->Form->create("Recipient", array('id' => 'RecipientSelection'));
$input1 = $this->Form->input('recipient_gender', array('class' => 'ServiceType','type' => 'select', 'options' => array('Male' => 'Male', 'Female' => 'Female'), 'label' => false, 'empty' => '-- '.__("Gender",true).' --'));

/*$input2 = $this->Form->input('recipient_age', array('class' => 'ServiceType','type' => 'select', 'options' => array(20=>20), 'label' => false, 'empty' => '-- '.__("Age",true).' --'));*/

$input3 = $this->Form->input('recipient_position', array('class' => 'ServiceType','type' => 'select', 'options' => array('Member' => 'Member', 'Representative' => 'Representative'), 'label' => false, 'empty' => '-- '.__("Position",true).' --'));

$input4 = $this->Form->input('recipient_description', array('class' => 'ServiceType','type' => 'select', 'options' => $recipient_description, 'label' => false, 'empty' => '-- '.__("Description",true).' --'));

$row1[] = array(array($this->Html->div('table_sub_header',__('Recipient Selection',true)), array('colspan'=> 4)));
$row1[] = array($input1, /*$input2,*/ $input3, $input4);
$selection_form.= "<table cellspacing=0 class='none'>";
$selection_form.= $this->Html->tableCells($row1, array('class' => 'none'), array('class' => 'none'));
$selection_form.= "</table>";


echo $selection_form;
 echo $this->Form->end();


echo $this->Form->create('VoicePolls',array('type' => 'post','action'=> 'sendvoicepollnow'));
echo "<table width='60%' class='collapsed' cellspacing=0>";

echo $this->Html->tableCells(array (
     array(__("Select Voice Poll",true), $this->Form->select('VoicePollMenu.voicepoll', $recordingopt)),
     ),array('class'=>'blue'),array('class'=>'blue'));

echo $this->Html->tableHeaders(array(__('Select Recipient',true)));

echo $this->Html->tableCells(
        array(

            array( 
                $this->Html->div('recipient_div', 
                                    $this->Form->select('VoicePollMenu.recipient', $recipientopt, array( 'multiple' => 'checkbox' )), 
                                    array('id'=>'recipient_div'))
            )
        ),
        array('class'=>'blue'),array('class'=>'blue'));

echo $this->Html->tableCells(array (

      $this->Form->end(__('Send Now',true)),
     ),array('class'=>'blue'),array('class'=>'blue'));

echo "</table>";
echo "</div>";



$this->Js->get('#RecipientSelection');
echo $this->Js->event('change',
    $this->Js->request(
      array('controller'=>'VoicePolls','action'=>'disp'),  
          array(
            'method'=>'post',
            'async'=>true,
            'update'=>'#recipient_div',
            'dataExpression'=>true,
            'data'=>$this->Js->serializeForm(array('isForm'=>false, 'inline'=>true))
          )
    )
);


?>
<input type="button" name="CheckAll" value="<? echo __('Check All',true);?>" onClick="checkAllVoicePoll(document.Message)">
<input type="button" name="UnCheckAll" value="<? echo __('Uncheck All',true);?>" onClick="uncheckAllVoicePoll(document.Message)">

<?php
/*
echo "<h2>".__("Alert Start time",true)."</h2>";
echo "<div class='formComment'>".__("When would you like to send the alert?",true)."</div>";

echo "<table cellpadding=0 class='blue'>";
echo $this->Html->tableCells(array (
     array(__("Start time",true),	$this->Form->input('start_time',array('label'=>false)))
      ),array('class'=>'blue'),array('class'=>'blue'));
echo "</table>";
echo $this->Form->end(__('Send Now',true));
echo "</div>";*/
?>

