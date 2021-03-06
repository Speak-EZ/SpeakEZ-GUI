<?php 
/****************************************************************************
 * edit.ctp	- Edit existing tag (used in Leave-a-message)
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
      echo $this->Html->addCrumb(__('Tags',true), '/tags');
      $ivr_settings = Configure::read('IVR_SETTINGS');

      if($this->data){
        if(isset($messages)){
          foreach ($messages as $key => $entry){
             $messages[$key] = $this->Text->truncate($entry,$ivr_settings['showLengthMax'],array('ending' => '...', 'exact' => true, 'html' => false));
          }
       }

        echo $this->Html->addCrumb(__('Edit',true), '/tags/edit/'.$this->data['Tag']['id']);
        echo "<h1>".__("Edit Tag",true)."</h1>";
        $this->Session->flash();

        $options_name     = array('label' =>  false);
        $options_longname = array('label' =>  false, 'type'=>'text','size'=>'50');
        $options_message  = array('label' =>  false);

        echo $this->Form->create('Tag', array('type' => 'post','action'=> 'edit'));   				       			 
	echo $this->Form->hidden('Tag.id', array('value' => $this->data['Tag']['id']));
        echo "<table cellspacing = 0 class = 'stand-alone'>";
        echo $this->Html->tableCells(array (
     	    array(__("Tag",true), $this->Form->input('name',$options_name)),
     	    array(__("Description",true), $this->Form->input('longname',$options_longname)),
     	    array(array(__("Use in message",true),array('valign' =>'top')), $this->Form->input('Message',array('type'=>'select','multiple'=>'true','options' => $messages, 'label'=>false,'empty'=>'-- '.__('Use in none',true).' --'))),
            array('',   $this->Form->end(__('Save',true)))
                                ),
            array('class' => 'stand-alone'),array('class' => 'stand-alone'));
        echo "</table>";

      } else {

         echo $this->Html->div("invalid_entry", __("This page does not exist.",true));

      }




?>