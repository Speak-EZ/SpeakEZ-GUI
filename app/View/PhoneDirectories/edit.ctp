<?php
/****************************************************************************
 * edit.ctp	- 
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

      echo "<h1>".__("Edit Member",true)."</h1>";


      $this->Session->flash(); 

      echo $this->Form->create('PhoneDirectories', array('type' => 'post','action'=> 'edit/'.$phone_id));
      echo "<table cellspacing=0 class='stand-alone'>";

      if(count($phone_data) == 1) {
        $phone_data = $phone_data[0]['phone_directory'];
        $name = $phone_data['recipient_name'];
        $phone_no = $phone_data['phone_no'];
        $gender = $phone_data['recipient_gender'];
        $age = $phone_data['recipient_age'];
        $position = $phone_data['recipient_position'];
        $description = $phone_data['recipient_description'];
        $information = $phone_data['recipient_information_1'];
      } else {

        $name = '';
        $phone_no = '';
        $gender = 'Male';
        $age = '';
        $position = 'Representative';
        $description = '';
        $information = '';
      }

      echo $this->Html->tableCells(array (
           array(__("Name",true),           $this->Form->input('name',array('label' => false, 'value'=>$name))),
           array(__("Phone No",true),   $this->Form->input('phone_no',array('label' => false, 'value'=>$phone_no))),
           array(__("Gender",true),   $this->Form->input('gender', array(
                                                                  'options' => array(
                                                                              'Male' => 'Male', 
                                                                              'Female' => 'Female'
                                                                              ),'label' => false,
                                                                            'default'=>$gender
                                                                  ))),
           array(__("Age (optional",true),   $this->Form->input('age',array('label' => false, 'value'=>$age))),
           array(__("Position",true),   $this->Form->input('position', array(
                                                                  'options' => array(
                                                                              'Member' => 'Member', 
                                                                              'Representative' => 'Representative'
                                                                            ),'label' => false,
                                                                            'default'=>$position
                                                                  ))),
           array(__("Description (optional",true),   $this->Form->input('description',array('label' => false, 'value'=>$description))),
           array(__("Other Information (optional)",true),   $this->Form->input('information',array('label' => false, 'value'=>$information))),
           array('',			     $this->Form->end(__('Save',true)))), array('class' => 'stand-alone'),array('class' => 'stand-alone'));

     echo "</table>";

?>