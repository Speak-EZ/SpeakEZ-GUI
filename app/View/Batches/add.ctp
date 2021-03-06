<?php
/****************************************************************************
 * add.ctp	- Create new SMS batch
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
echo $this->Html->addCrumb(__('SMS Center',true), '');
echo $this->Html->addCrumb(__('SMS Batches',true), '/batches');
echo $this->Html->addCrumb(__('Create',true), '/batches/add');




echo "<h1>".__("Create SMS batch",true)."</h1>";

echo $this->Session->flash('filename');
echo $this->Session->flash('sms_gateway_id');
	

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
$selection_form.=  $this->Form->end();

echo $selection_form;

echo $this->Form->create('Batch',array('type' => 'post','action'=> 'add', 'enctype' => 'multipart/form-data'));



echo $this->Html->div('frameLeft');

echo "<table width='600px'  cellpadding=0 class='blue'>";
echo $this->Html->tableCells(array (
     array(__("Name",true),	$this->Form->input('name',array('label'=>false,'size' => '39'))),
     array(array(__("Name of SMS batch.",true),"colspan='2' class='formComment'")),

     array(__("SMS body",true),	$this->Form->input('body',array('label'=>false,'cols' => 55,'rows ' => 3,'maxLength' => 160))),
     array(array(__("Alpha-numeric characters only (maximum 160)",true),"colspan='2' class='formComment'")),

     array(__("Sender number (fixed)",true),	$this->Form->input('sender_number',array('label'=>false,'size' => '39', 'value' => '13474747568', 'readonly' => true))),
    // array(array(__("For Clickatell only: Number to appear as sender of the SMS. Include country prefix but without plus sign (+) and double zeros (00).",true),"colspan='2' class='formComment'")),

     //array(__("Receivers",true),	$this->Form->input('file',array('label'=>false, 'type' => 'file'))),
     //array(array(__("File containing receivers phone numbers. One number per line. Maximum 100 entries for Clickatell batches. Examples of valid formats: +263772123456, 00263772123456, 0772123456.",true),"colspan='2' class='formComment'")),


     ), array('class'=>'blue'),array('class'=>'blue'));

echo "</table>";

echo "<table cellspacing=0 class='blue'>";

echo $this->Html->tableCells(
        array(
            array(__("Receivers",true)),
            array( 
                $this->Html->div('recipient_div', 
                                    $this->Form->select('Recipient', $recipientopt, array( 'multiple' => 'checkbox' )), 
                                    array('id'=>'recipient_div'))
            )
        ),
        array('class'=>'blue'),array('class'=>'blue'));

echo "</table>";

echo $this->Form->create("Batch_method");
$input1 = $this->Form->input('gateway_type', array('id' => 'ServiceType1','type' => 'select', 'options' => array("SMS Gateway", "GSM Channel"), 'label' => false, 'empty' => '-- '.__("Select sending method",true).' --'));

echo "<table cellspacing=0 class='blue'>";
      echo $this->Html->tableCells(array($input1), array('class' => 'blue'), array('class' => 'blue'));
echo "</table>";

      $this->Js->get('#ServiceType1');
      $this->Js->event('change', $this->Js->request(array('controller'=>'batches','action' => 'method'),array('async' => true,'update' => '#service_div','method' => 'post','dataExpression'=>true,'data'=> $this->Js->serializeForm(array('isForm' => true,'inline' => true)))));

      echo $this->Form->end();	

     echo "<div id='service_div'>";
     echo "</div>";


     echo $this->Form->end(__('Save',true));
     echo '<br><input type="button" name="CheckAll" value="'.__('Check All',true).'" onClick="checkAllImmediate(document.Message)">';
    echo '<input type="button" name="UnCheckAll" value="'.__('Uncheck All',true).'" onClick="uncheckAllImmediate(document.Message)">';
     echo "</div>";

$this->Js->get('#RecipientSelection');
echo $this->Js->event('change',
    $this->Js->request(
      array('controller'=>'Batches','action'=>'recipient_disp'),  
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
