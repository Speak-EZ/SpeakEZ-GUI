<?php
/****************************************************************************
 * add.ctp	- Create new poll
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
echo $this->Html->addCrumb(__('Send Immediate Alert',true), '/CreateAlerts/scheduled/');

echo $this->Html->script('addRemoveElements');

echo "<h1>".__("Send Scheduled Alert",true)."</h1>";

echo "<div>";

$recipientopt = array(
    'Value 1' => 'Recipient 1',
    'Value 2' => 'Recipient 2',
    'Value 3' => 'Recipient 3',
    'Value 4' => 'Recipient 4',
    'Value 5' => 'Recipient 5',
    'Value 6' => 'Recipient 6',
    'Value 7' => 'Recipient 7',
    'Value 8' => 'Recipient 8',
    'Value 9' => 'Recipient 9',
    'Value 10' => 'Recipient 10'
);

$recordingopt = array(
    'Value 1' => 'Recording 1',
    'Value 2' => 'Recording 2',
    'Value 3' => 'Recording 3',
    'Value 4' => 'Recording 4',
    'Value 5' => 'Recording 5',
    'Value 6' => 'Recording 6',
    'Value 7' => 'Recording 7',
    'Value 8' => 'Recording 8',
    'Value 9' => 'Recording 9',
    'Value 10' => 'Recording 10'
);

echo $this->Form->create('ScheduledAlerts',array('type' => 'post','action'=> 'send'));

echo "<table width='600px' class='collapsed' cellspacing=0>";
echo $this->Html->tableCells(array (
     array(__("Set Date and Time",true), 

        $this->Form->input('', array(
            'type' => 'datetime',
            'dateFormat' => 'DMY',
            'minYear' => date('Y'),
            'maxYear' => date('Y'),

            'timeFormat' => '24',
            'selected' => date('Y-m-d 0:00:s'),
            'attributes' => array(),
            'empty' => FALSE
                )
            )),

        //$this->Form->select('Model.field', $recordingopt)),     
     array(__("Select Recording",true), $this->Form->select('AlertMenu.recording', $recordingopt)),
     ),array('class'=>'blue'),array('class'=>'blue'));

echo $this->Html->tableHeaders(array(__('Select Recipient',true)));

echo $this->Html->tableCells(array (
    array( 
    $this->Form->select('AlertMenu.recipient', $recipientopt, array(
    'multiple' => 'checkbox'
     ))
    //,$this->Form->select('Model.field', $recordingopt)
    )),array('class'=>'blue'),array('class'=>'blue'));

echo $this->Html->tableCells(array (

      $this->Form->end(__('Send Now',true)),
     ),array('class'=>'blue'),array('class'=>'blue'));

echo "</table>";
echo "</div>";

?>
<input type="button" name="CheckAll" value="<? echo __('Check All',true);?>" onClick="checkAllImmediate(document.Message)">
<input type="button" name="UnCheckAll" value="<? echo __('Uncheck All',true);?>" onClick="uncheckAllImmediate(document.Message)">
<?




/*
echo $this->Form->create('CreateAlerts',array('type' => 'post','action'=> 'send'));
echo $this->Form->create('CreateAlerts',array('type' => 'post','action'=> 'upload'));

echo $this->Html->div('frameLeft');
echo "<table cellpadding=0 class='blue'>";
echo $this->Html->tableCells(array (
     array(__("File Name",true), $this->Form->input('UploadMenu.title',array('label'=>false,'size' => '40'))),
     array(array(__("Enter the name of the Alert file",true),"colspan='2' class='formComment'")),
     array(__("Description",true),    $this->Form->input('UploadMenu.descr',array('label'=>false,'size' => '40'))),
    array(array(__("Enter description of the file",true),"colspan='2' class='formComment'")),
    $lines1[0] = array(__("Please select File that you want to upload",true), $this->Form->input('UploadMenu.file',array('label'=>false,'type'=>'file','size' => '50'))),
     ),array('class'=>'blue'),array('class'=>'blue'));
echo "</table>";


/*<form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
echo $this->Form->end(__('Upload Now',true));*/
?>




<?php
/*
echo "<h2>".__("Alert Start time",true)."</h2>";
echo "<div class='formComment'>".__("When would you like to send the alert?",true)."</div>";

echo "<table cellpadding=0 class='blue'>";
echo $this->Html->tableCells(array (
     array(__("Start time",true),   $this->Form->input('start_time',array('label'=>false)))
      ),array('class'=>'blue'),array('class'=>'blue'));
echo "</table>";
echo $this->Form->end(__('Send Now',true));
echo "</div>";*/
?>
