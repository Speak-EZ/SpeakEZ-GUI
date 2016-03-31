<?php
/****************************************************************************
 * index.ctp	- List processes
 * version 	- 3.0.1500
 * 
 * Version: MPL 1.1
 *
 * The contents of this file are subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this file except in compliance withs
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

echo $this->Html->addCrumb(__('IVR Center',true), '');
echo $this->Html->addCrumb(__('Voice menus',true), '/ivr_menus/');
echo $this->Html->script('bootstrap.min');
echo $this->Html->script('RecorderDemo');
echo $this->Html->script('WebAudioRecorder');

$this->Session->flash();


$ivr_default  = Configure::read('IVR_DEFAULT');
$ivr_settings = Configure::read('IVR_SETTINGS');


$commentTitle   = "<span class='formHelp'>".__("Name of IVR",true)."</span>";
$commentLong   = "<span class='formHelp'>".__("Long greeting message:include a brief description of the services offered and the menu alternatives.",true)."</span>";
$commentShort  = "<span class='formHelp'>".__("Brief instuctions: Repeat the menu alternatives. For example: For news, press 1. For health, press 2.",true)."</span>";

$commentExit   = "<span class='formHelp'>".__("Message played before Freedom Fone finishes (hangs) the call. ",true)."</span>";
$commentInvalid   = "<span class='formHelp'>".__("Message played after incorrect input (digit pressed) from user. ",true)."</span>";

$commentOption1  = "<span class='formHelp'>".__("Select option for alternative 1.",true)."</span>";
$commentOption2  = "<span class='formHelp'>".__("Select option for alternative 2.",true)."</span>";
$FallbackIndex   = "<div class='formComment'>".__("Default: ",true).$ivr_default['ivrIndexMessage']."</div>";
$FallbackExit    = "<div class='formComment'>".__("Default: ",true).$ivr_default['ivrExitMessage']."</div>";
$FallbackInvalid    = "<div class='formComment'>".__("Default: ",true).$ivr_default['ivrInvalidMessage']."</div>";
$FallbackLong    = "<div class='formComment'>".__("Default",true).": ".$ivr_default['ivrLongMessage']."</div>";
$FallbackShort   = "<div class='formComment'>".__("Default",true).": ".$ivr_default['ivrShortMessage']."</div>";


	if($this->data && $this->data['IvrMenu']['ivr_type']=='ivr'){

             echo $this->Html->addCrumb(__('Edit',true), '/ivr_menus/edit/'.$this->data['IvrMenu']['id']);

             $ivrMenu = $this->data['IvrMenu'];
             echo "<h1>".__("In-browser recording",true)."</h1>";

             echo $this->Session->flash();


echo $this->Form->create('IvrMenu', array('type' => 'post', 'action' => 'edit','enctype' => 'multipart/form-data') );
echo $this->Form->input('id',array('type'=>'hidden'));
echo $this->Form->input('instance_id',array('type'=>'hidden','value'=>$ivrMenu['instance_id']));
echo $this->Form->input('ivr_type',array('type'=>'hidden','value'=>$ivrMenu['ivr_type']));



$path = $ivr_settings['path'].$this->data['IvrMenu']['instance_id']."/".$ivr_settings['dir_menu'];

echo "<fieldset><legend>".__('Name',true)."</legend>";
echo $this->Form->input('title',array('type'=>'text','size' => '95', 'maxLength' => '100','between'=>'<br />','label'=>$commentTitle));
echo "</fieldset>";
	
echo "<fieldset>";
echo "<legend>".__('Audio Recording',true)."</legend>";
?>

1-

<iframe src="https://s3-us-west-2.amazonaws.com/assets.cdn.univerch.com/speakez-demo/index.html" frameborder="0" width="100%" height="500px"></iframe>    
<?
}
else {

   echo "<h3>".__("This IVR does not exist.",true)."</h3>";
}

?>
