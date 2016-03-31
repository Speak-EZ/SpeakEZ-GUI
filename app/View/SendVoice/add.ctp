<?php
/****************************************************************************
 * add.ctp	- Create new Voice Campaign
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
 *the first three lines have to be reviewed
***************************************************************************/
echo $this->Html->addCrumb(__('SMS Centre',true), '');
echo $this->Html->addCrumb(__('SMS Batches',true), '/batches');
echo $this->Html->addCrumb(__('Create',true), '/batches/add');




echo "<h1>".__("Create Voice Campaign",true)."</h1>";

echo $this->Session->flash('filename');
	
echo $this->Form->create('Batch',array('type' => 'post','action'=> 'add', 'enctype' => 'multipart/form-data'));



echo $this->Html->div('frameLeft');

echo "<table width='600px'  cellpadding=0 class='blue'>";
echo $this->Html->tableCells(array (
     array(__("Name",true),	$this->Form->input('name',array('label'=>false,'size' => '39'))),
     array(array(__("Name of Campaign.",true),"colspan='2' class='formComment'")),

bd-
     array(__("Audio content",true),	$this->Form->input('file',array('label'=>false, 'type' => 'file'))),
     array(array(__("Audio file to be sent",true),"colspan='2' class='formComment'")),

     array(__("Receivers",true),	$this->Form->input('file',array('label'=>false, 'type' => 'file'))),
     array(array(__("File containing receivers phone numbers. One number per line. Examples of valid formats:13153950817.",true),"colspan='2' 		class='formComment'")),

     ), array('class'=>'blue'),array('class'=>'blue'));


echo "</table>";

      $this->Js->get('#ServiceType1');
      $this->Js->event('change', $this->Js->request(array('controller'=>'batches','action' => 'method'),array('async' => true,'update' => '#service_div','method' => 'post','dataExpression'=>true,'data'=> $this->Js->serializeForm(array('isForm' => true,'inline' => true)))));

      echo $this->Form->end();	

     echo "<div id='service_div'>";
     echo "</div>";


     echo $this->Form->end(__('Save',true));
     echo "</div>";
?>

