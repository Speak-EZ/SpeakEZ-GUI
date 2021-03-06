<?php
/****************************************************************************
 * edit.ctp	- Edit node (aka Menu Option for Voice Menu)
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

echo $this->Html->addCrumb(__('IVR Centre',true), '');
echo $this->Html->addCrumb(__('Content',true), '/nodes');

$ivr = Configure::read('IVR_SETTINGS');

	if($this->data){

                echo $this->Html->addCrumb(__('Edit',true), '/nodes/edit/'.$this->data['Node']['id']);

		echo "<h1>".__("Edit Content",true)."</h1>";
		
                echo $this->Session->flash();




      		$path = $ivr['path'].$ivr['dir_node'];
		$listen =  $this->element('player',array('path'=>$path,'file'=>$this->data['Node']['file'],'title'=>$this->data['Node']['title'],'id'=>$this->data['Node']['id']));
	        $download = $this->Html->image("icons/music.png", array("alt" => __("Download",true), "title" => __("Download",true), "url" => array("controller" => "nodes", "action" => "download", $this->data['Node']['id'])));


		echo $this->Form->create('Node', array('type' => 'post', 'action' => 'edit','enctype' => 'multipart/form-data') );
		echo $this->Form->input('file_old',array('type'=>'hidden','value'=>$this->data['Node']['file']));
		echo $this->Form->input('id',array('type'=>'hidden','value'=>$this->data['Node']['id']));

                $row[] = array(__("Title",true),	array($this->Form->input('title',array('label'=>false,'size'=>'50')),"colspan=2"));
	        $row[] = array(__("Audio file",true),	array($this->Form->input('file',array('label'=>false,'type'=>'file')),"colspan=2"));
                $row[] = array(array(__("If you select a file, the previous one will be deleted. Valid formats: wav and mp3",true),"colspan='3' class='formComment'"));
                $row[] = array(__("Duration",true), array($this->Formatting->epochToWords($this->data['Node']['duration']),"colspan= 2"));
                $row[] = array(array(__('Listen',true),array('width'=>'75')), array($download,array('valign'=>'middle','width'=>'25')), $listen);

		echo "<table width='500px' class='stand-alone'>";
                echo $this->Html->tableCells($row,array('class'=>'stand-alone'),array('class'=>'stand-alone'));
		echo "</table>";

		echo $this->Form->end(__('Save',true));

		}

	else {
    		echo "<h1>".__("No Menu Option with that id exists",true)."</h1>";
	}


?>

