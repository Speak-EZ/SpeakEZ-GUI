<?php
/****************************************************************************
 * edit.ctp	- Edit office route channel
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

echo $this->Html->addCrumb(__('Dashboard',true), '');
echo $this->Html->addCrumb(__('GSM channels',true), '/channels');


	if($this->data){

                echo $this->Html->addCrumb(__('Edit OfficeRoute',true), '/office_route/edit/'.$this->data['OfficeRoute']['id']);

                $msg1 = __("Edit channel",true);
                $msg2 = __("slot",true);
		echo "<h1>".$msg1." (".$msg2.":".$this->data['OfficeRoute']['id'].")</h1>";
		
               echo $this->Session->flash();

                echo $this->Html->div('frameLeft');

		echo $this->Form->create('OfficeRoute', array('type' => 'post', 'action' => 'edit','enctype' => 'multipart/form-data') );

		$row = array (array(__("Title",true),		$this->Form->input('title',array('label'=>false,'size'=>'50'))), 
		              array(__("Phone number",true),	$this->Form->input('msisdn',array('label'=>false,'size'=>'50'))),
     		              array(__("Operator",true),	$this->data['OfficeRoute']['operator_name']),
			      array(false, 			$this->Form->hidden('id',array('value'=>$this->data['OfficeRoute']['id']))),
			      );

			   
                echo "<table width='400px' cellspacing='0' class='blue'>"; 
                echo $this->Html->tableCells($row,array('class' => 'blue'),array('class' => 'blue'));
                echo "</table>";

		echo $this->Form->end(array('name'=>__('Save',true),'label' =>__('Save',true), 'class'=>'save_button'));

                echo "</div>";

		}

	else {
    		echo "<h1>".__("This page does not exist.",true)."</h1>";
	}

        
?>