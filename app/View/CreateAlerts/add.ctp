<?php
/****************************************************************************
 * add.ctp      - Create new poll
 * version      - 3.0.1500
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
echo $this->Html->addCrumb(__('Create Alert File',true), '/CreateAlerts/add/');

echo $this->Html->script('addRemoveElements');

echo "<h1>".__("Create Alert File",true)."</h1>";
//echo $this->Form->create('CreateAlerts',array('type' => 'post','action'=> 'send'));
/*echo $this->Form->create('CreateAlerts',array('type' => 'post','action'=> 'upload'));*/

/*
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
*/

/*<form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>*/




//echo $this->Form->end(__('Upload Now',true));
?>

<?php
/*
echo "<h2>".__("Alert Start time",true)."</h2>";
echo "<div class='formComment'>".__("When would you like to send the alert?",true)."</div>";

echo "<table cellpadding=0 class='blue'>";
echo $this->Html->tableCells(array (
     array(__("Start time",true),       $this->Form->input('start_time',array('label'=>false)))
      ),array('class'=>'blue'),array('class'=>'blue'));
echo "</table>";
echo $this->Form->end(__('Send Now',true));*/
//echo "</div>";
?>

<?php
// ping edit
echo $this->Session->flash();
echo $this->Form->create('CreateAlerts', 
		array('type' => 'post', 'action' => 'send','enctype' => 'multipart/form-data') );

     $row[] = array(__("Title",true),   $this->Form->input('UploadMenu.title',array('label'=>false,'size' =>'50')));
     $row[] = array(__("Description",true),   $this->Form->input('UploadMenu.descr',array('label'=>false,'size' =>'100')));

     $row[] = array(__("Audio file",true), $this->Form->input('UploadMenu.file',array('label'=>false,'type'=>'file')));
     $row[] = array(array(__("Valid formats: wav and mp3",true),"colspan='2' class='formComment'"));

     echo "<table cellspacing = 0 class='stand-alone'>";
     echo $this->Html->tableCells($row, array('class'=>'stand-alone'),array('class'=>'stand-alone'));
     echo "</table>";

     echo $this->Form->end(__('Save',true));

?>


