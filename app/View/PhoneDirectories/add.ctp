<?php
/****************************************************************************
 * add.ctp	- 
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

      echo $this->Html->addCrumb(__('Community Members',true), '/PhoneDirectories');
      echo $this->Html->addCrumb(__('Add',true), '/PhoneDirectories/add');

      echo "<h1>".__("Add Member",true)."</h1>";


      $this->Session->flash();
      $options	  = array('label' => false);


      echo $this->Form->create('PhoneDirectories', array('type' => 'post','action'=> 'add'));
      echo "<table cellspacing=0 class='stand-alone'>";

      echo $this->Html->tableCells(array (
           array(__("Name",true),           $this->Form->input('name',$options)),
           array(__("Phone No",true),   $this->Form->input('phone_no',$options)),
           array(__("Gender",true),   $this->Form->input('gender', array(
                                                                  'options' => array(
                                                                              'Male' => 'Male', 
                                                                              'Female' => 'Female'
                                                                              ),'label' => false
                                                                  ))),
           array(__("Age (optional)",true),   $this->Form->input('age',$options)),
           array(__("Position",true),   $this->Form->input('position', array(
                                                                  'options' => array(
                                                                              'Member' => 'Member', 
                                                                              'Representative' => 'Representative'
                                                                            ),'label' => false
                                                                  ))),
           array(__("Description (optional)",true),   $this->Form->input('description',$options)),
           array(__("Other Information (optional)",true),   $this->Form->input('information',$options)),
           array('',			     $this->Form->end(__('Save',true)))), array('class' => 'stand-alone'),array('class' => 'stand-alone'));

     echo "</table>";

?>