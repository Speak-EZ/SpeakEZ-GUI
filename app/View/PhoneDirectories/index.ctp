<?php
/****************************************************************************
 * index.ctp	- 
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


$this->Access->showButton($authGroup, 'PhoneDirectories', 'add', 'frameRightAlone', __('Add Members',true), 'submit', 'button');
echo "<h1>".__("Community Members",true)."</h1>";


   if ($recipientData){

      echo "<table width='400px' cellspacing = 0>";
      $headers = array(__('ID',true),__('Name',true),__('Phone No',true), __('Gender (Age)', true), __('Position', true), __('Edit', true), __('Delete', true));
      if($authGroup != 1 ){ unset($headers[2]); }

      echo $this->Html->tableHeaders($headers);

      	      foreach ($recipientData as $key => $contact){

               $id       = $contact['phone_directory']['phone_id'];
    	         $name       = $contact['phone_directory']['recipient_name'];
               $phone_no = $contact['phone_directory']['phone_no'];  
               $age = $contact['phone_directory']['recipient_age'];  
               $gender = $contact['phone_directory']['recipient_gender'];  
               $position = $contact['phone_directory']['recipient_position'];  
               $description = $contact['phone_directory']['recipient_description'];  
 			         $information = $contact['phone_directory']['recipient_information_1'];	


               $edit        = $this->Access->showBlock($authGroup, $this->Html->image("icons/edit.png", array("alt" => __("Edit",true), "title" => __("Edit",true), "url" => array("controller" => "phone_directories", "action" => "edit", $contact['phone_directory']['phone_id']))));
               
               $delete      = $this->Access->showBlock($authGroup, $this->Html->image("icons/delete.png", array("alt" => __("Delete",true), "title" => __("Delete",true), "url" => array("controller" => "PhoneDirectories", "action" => "delete", $id ), "onClick" => "return confirm('".__('Are you sure you want to delete this contact?',true)."');")));
                if ($age != '') {
                  $age = ' ('.$age.')';
                }
               $row[$key] = array($id, $name, $phone_no, $gender.$age, $position, $edit, $delete); 

              }

     echo $this->Html->tableCells($row,array('class'=>'darker'));
      echo "</table>";

      }
      else {

         echo $this->Html->div('feedback', __('No tags exist. Please create one by clicking the <i>Create new</i> button to the right.',true));

      }

?>