<?php
/****************************************************************************
 * add_selector.ctp	- Create new language selector
 * version 	        - 3.0.1500
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

echo $this->Html->addCrumb(__('IVR Center',true), '');
echo $this->Html->addCrumb(__('Language selectors',true), '/selectors');
echo $this->Html->addCrumb(__('Create',true), '/selectors/add');


echo "<h1>".__("Create Language Selector",true)."</h1>";

$ivr_default  = Configure::read('IVR_DEFAULT');
$ivr = Configure::read('IVR_SETTINGS');


echo $this->Session->flash();
	
$commentTitle   = "<span class='formHelp'>".__("Name of IVR",true)."</span>";
$commentLong    = "<span class='formHelp'>".__("Long greeting message:include a brief description of the services offered and the menu alternatives.",true)."</span>";
$commentShort   = "<span class='formHelp'>".__("Brief instuctions: Repeat the menu alternatives. For example: For news, press 1. For health, press 2.",true)."</span>";


$FallbackIndex   = "<div class='formComment'>".__("Default",true).": ".$ivr_default['ivrIndexMessage']."</div>";
$FallbackExit    = "<div class='formComment'>".__("Default",true).": ".$ivr_default['ivrExitMessage']."</div>";
$FallbackInvalid = "<div class='formComment'>".__("Default",true).": ".$ivr_default['ivrInvalidMessage']."</div>";
$FallbackLong    = "<div class='formComment'>".__("Default",true).": ".$ivr_default['ivrLongMessage']."</div>";
$FallbackShort   = "<div class='formComment'>".__("Default",true).": ".$ivr_default['ivrShortMessage']."</div>";


echo $this->Form->create('IvrMenu', array('type' => 'post', 'action' => 'add_selector','enctype' => 'multipart/form-data') );
echo $this->Form->input('ivr_type',array('type'=>'hidden','value'=>'switcher'));

?>

<fieldset>
<legend><?php echo  __('Title',true);?> </legend>
<?php echo $this->Form->input('title',array('type'=>'text','size' => '93', 'between'=>'<br />','label'=>$commentTitle)); ?>
</fieldset>

<fieldset>
<h3>1. <?php echo __('Instructions',true);?> </h3>
<?php echo $this->Form->input('message_long',array('type'=>'textarea','cols' => '80', 'rows' => '3', 'label'=>$commentLong, 'after' => $FallbackLong, 'between'=>'<br />' )); ?>
<?php echo $this->Form->input('IvrMenu.file_long', array('between'=>'<br />','type'=>'file','size'=>'50','label'=>__('Audio file',true)));?>
</fieldset>


<fieldset>
<h3>2. <?php echo  __('Invalid',true);?> </h3>
<?php echo $this->Form->input('message_invalid',array('type'=>'text','size' => '93','label'=>false,'after' => $FallbackInvalid, 'between'=>'<br />' )); ?>
<?php echo $this->Form->input('IvrMenu.file_invalid', array('between'=>'<br />','type'=>'file','size'=>'50','label'=>__('Audio file',true)));?>
</fieldset>

<fieldset>

<?

echo "<legend>".__('Menu Options',true)."</legend>";



        $opt = array('ivr'=>__('Voice Menus',true),'lam'=>__('Leave-a-message',true),'node' => __('Content',true));
	echo $this->Form->input("type",array("id"=>"IvrMenu","type"=>"select","options"=>$opt,"label"=> false,"empty" => '-- '.__("Select service",true).' --'));
	$this->Js->get('#IvrMenu');
	$this->Js->event('change', $this->Js->request(array('controller'=>'ivr_menus','action' => 'disp'),array('async' => true,'update' => '#service_div','method' => 'post','dataExpression'=>true,'data'=> $this->Js->serializeForm(array('isForm' => true,'inline' => true)))));
	echo $this->Form->end();

                                                                                       
       echo "<div id='service_div' style=''></div>";
       echo "</fieldset>";



?>



