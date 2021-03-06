<?php
/****************************************************************************
 * add.ctp	- Create a new Leave-a-message IVR menu
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

echo $this->Html->addCrumb(__('Message Center',true), '');
echo $this->Html->addCrumb(__('Administration',true), '/lm_menus');
echo $this->Html->addCrumb(__('Create',true), '/lm_menus/add');



$lm_settings = Configure::read('LM_SETTINGS');
$lm_default  = Configure::read('LM_DEFAULT');

      echo "<h1>".__("Create Leave a Message IVR",true)."</h1>";
      echo $this->Session->flash();
 


  $info = $this->Html->link(
                        $this->Html->image("icons/bulb.png"),
                        "/pages/lm_menus/tip",
                        array("escape" => false, "title" => __("Tool tip", true), "onClick" => "Modalbox.show(this.href, {title: this.title, width: 500}); return false;"),
                        false);

   echo $this->Html->div('frameInfo', $info);

   echo $this->Html->div('instruction', __("Please upload either an .mp3 or a .wav audio file for each message. If no audio file is present, the fallback text will be used in the Leave-a-Message IVR Menu.",true));
   echo $this->Html->div('instruction', __("You can listen to your uploaded audio files by pressing the Play button or download a copy of the files by using the Download icon.",true));
   echo $this->Html->div('instruction', __("Audio files should be recorded in mono, 8KHz, and be maximum 10MB.",true));

$min = ' '.__('min',true);
$commentWelcome  = "<div class='formComment'>".__("Default",true).': '.$lm_default['lmWelcomeMessage']."</div>";
$commentInform   = "<div class='formComment'>".__("Default",true).': '.$lm_default['lmInformMessage']."</div>";
$commentInvalid  = "<div class='formComment'>".__("Default",true).': '.$lm_default['lmInvalidMessage']."</div>";
$commentLong     = "<div class='formComment'>".__("Default",true).': '.$lm_default['lmLongMessage']."</div>";
$commentSelect   = "<div class='formComment'>".__("Default",true).': '.$lm_default['lmSelectMessage']."</div>";
$commentDelete   = "<div class='formComment'>".__("Default",true).': '.$lm_default['lmDeleteMessage']."</div>";
$commentSave     = "<div class='formComment'>".__("Default",true).': '.$lm_default['lmSaveMessage']."</div>";
$commentGoodbye  = "<div class='formComment'>".__("Default",true).': '.$lm_default['lmGoodbyeMessage']."</div>";

$commentTitle     = $this->Html->div('formComment', __('Name of this Leave-a-message IVR menu.',true));
$commentMaxreclen = $this->Html->div('formComment', __('Maximum duration of voice message left by user.',true));
$commentPriority = $this->Html->div('formComment', __('Priority level of this message (in case of Priority LAM, please specify a designated recipient\'s number for the automated message).',true));
$commentDesignatedRecipient = $this->Html->div('formComment', __('An automated text message will be sent to this number for every incoming Message of this LAM.',true)); 
$commentForceTTS  = $this->Html->div('formComment', __('Check the box to force Text-to-speech (ignore uploaded files)',true));
$options          = array('60' => '1'.$min, '120' => '2'.$min, '180' => '3'.$min, '240' => '4'.$min , '300' => '5'.$min, '900' => __('Unlimited',true)); 
$priority_options          = array('General' => 'General', 'Priority' => 'Priority'); 


echo $this->Form->create('LmMenu', array('type' => 'post', 'action' => 'add','enctype' => 'multipart/form-data') );
echo $this->Form->hidden('lmOnHangup',array('value'=>'accept'));
echo $this->Form->hidden('lmForceTTS',array('value'=>0));
echo $this->Form->hidden('id',array('value'=>$this->data['LmMenu']['id']));
echo $this->Form->hidden('instance_id',array('value'=>$this->data['LmMenu']['instance_id']));

     // ** General settings **//
     echo "<fieldset><legend>".__('General settings',true)."</legend>";
      echo $this->Form->input('LmMenu.title', array('between'=>'<br />','type'=>'text','size'=>'45','maxLength' => '50', 'label'=>__('Title',true),'after' => $commentTitle));
     echo $this->Form->input('LmMenu.priority', array('between'=>'<br />','type'=>'select', 'options' => $priority_options, 'label'=>__('Message priority',true),'after' => $commentPriority));
     echo $this->Form->input('LmMenu.designated_recipient', array('between'=>'<br />','type'=>'text','size'=>'45','maxLength' => '50', 'label'=>__('Designated recipient\'s number',true),'after' => $commentDesignatedRecipient));
     echo $this->Form->input('LmMenu.lmMaxreclen', array('between'=>'<br />','type'=>'select', 'options' => $options, 'label'=>__('Message duration',true),'after' => $commentMaxreclen));
     echo $this->Form->hidden('lmForceTTS',array('value'=>0));
     echo $this->Form->input('lmForceTTS',array('type' =>'checkbox','label' => false, 'before' => __('Do not use uploaded files',true).' '));    	    
     echo $this->Form->hidden('lmOnHangup',array('value'=>'accept'));
     echo $commentForceTTS;
     echo "</fieldset>";



     // ** Welcome **//
     echo "<fieldset><legend>".__('Step 1: Welcome message',true)."</legend>";
     $lines1[0] = array($this->Form->input('LmMenuFile.lmWelcome', array('between'=>'<br />','type'=>'file','size'=>'50','label'=>__('Audio file',true))));
     $lines1[1] = array($this->Form->input('lmWelcomeMessage',array('type'=>'textarea','rows' => '2','cols'=>'100%','label'=>__('Fallback text',true),'after' => $commentWelcome,'between'=>'<br />' ))); 
     echo "<table cellspacing = 0 class='none'>";
     echo $this->Html->tableCells($lines1,array('class' => 'none'),array('class' => 'none'));
     echo "</table>";
     echo "</fieldset>";


     // ** Record **//
     echo "<fieldset><legend>".__('Step 2: Record message instructions',true)."</legend>";
     $lines2[0] = array($this->Form->input('LmMenuFile.lmInform', array('between'=>'<br />','type'=>'file','size'=>'50','label'=>__('Audio file',true))));
     $lines2[1] = array($this->Form->input('lmInformMessage',array('type'=>'textarea','rows' => '2','cols'=>'100%','label'=>__('Fallback text',true),'after' => $commentInform,'between'=>'<br />' ))); 
     echo "<table cellspacing = 0 class='none'>";
     echo $this->Html->tableCells($lines2,array('class' => 'none'),array('class' => 'none'));
     echo "</table>";
     echo "</fieldset>";

     // Show and collapse advanced menu.

     echo $this->Js->link(__("Advanced options",true),
     	  		array("controller" => "lm_menus", "action" => "advanced_add"),
			array("update"   => "#lm_advanced", "escape" => false, "class" => "lm_advanced"));


     echo "<div id ='lm_advanced'></div>";


     echo $this->Form->end(__('Save',true)); 



?>