<?php
/****************************************************************************
 * index.ctp	- List processes
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

echo $this->Html->addCrumb(__('IVR Center',true), '');
echo $this->Html->addCrumb(__('Voice menus',true), '/ivr_menus/');
echo $this->Html->addCrumb(__('Create',true), '/ivr_menus/add');


echo "<h1>".__("Create voice menu",true)."</h1>";

$ivr_default  = Configure::read('IVR_DEFAULT');
$ivr = Configure::read('IVR_SETTINGS');
echo  $this->Session->flash();

	
$commentTitle   = "<span class='formHelp'>".__("Name of IVR",true)."</span>";
$commentLong    = "<span class='formHelp'>".__("Long greeting message:include a brief description of the services offered and the menu alternatives.",true)."</span>";
$commentShort   = "<span class='formHelp'>".__("Brief instuctions: Repeat the menu alternatives. For example: For news, press 1. For health, press 2.",true)."</span>";

$commentOption1  = "<span class='formHelp'>".__("Select option for alternative 1.",true)."</span>";
$commentOption2  = "<span class='formHelp'>".__("Select option for alternative 2.",true)."</span>";


$FallbackIndex   = "<div class='formComment'>".__("Default",true).": ".$ivr_default['ivrIndexMessage']."</div>";
$FallbackExit    = "<div class='formComment'>".__("Default",true).": ".$ivr_default['ivrExitMessage']."</div>";
$FallbackInvalid = "<div class='formComment'>".__("Default",true).": ".$ivr_default['ivrInvalidMessage']."</div>";
$FallbackLong    = "<div class='formComment'>".__("Default",true).": ".$ivr_default['ivrLongMessage']."</div>";
$FallbackShort   = "<div class='formComment'>".__("Default",true).": ".$ivr_default['ivrShortMessage']."</div>";


echo $this->Form->create('IvrMenu', array('type' => 'post', 'action' => 'add','enctype' => 'multipart/form-data') );
echo $this->Form->input('ivr_type',array('type'=>'hidden','value'=>'ivr'));

?>

<fieldset>
<legend><?php __('Name');?> </legend>
<?php echo $this->Form->input('title',array('type'=>'text','size' => '93', 'between'=>'<br />','label'=>$commentTitle)); ?>
</fieldset>

<fieldset>
<h3>1. <?php echo __('Welcome');?> </h3>
<?php echo $this->Form->input('message_long',array('type'=>'textarea','cols' => '80', 'rows' => '3', 'label'=>$commentLong, 'after' => $FallbackLong, 'between'=>'<br />' )); ?>
<?php echo $this->Form->input('IvrMenu.file_long', array('between'=>'<br />','type'=>'file','size'=>'50','label'=>__('Audio file',true)));?>
</fieldset>

<fieldset>
<h3>2. <?php echo __('Instructions');?> </h3>
<?php echo $this->Form->input('message_short',array('type'=>'textarea','cols' => '80', 'rows' => '3','label'=>$commentShort,'after' => $FallbackShort,'between'=>'<br />' )); ?>
<?php echo $this->Form->input('IvrMenu.file_short', array('between'=>'<br />','type'=>'file','size'=>'50','label'=>__('Audio file',true)));?>
</fieldset>

<fieldset>
<h3>3. <?php echo __('Goodbye');?> </h3>
<?php echo $this->Form->input('message_exit',array('type'=>'text','size' => '93','label'=>false,'after' => $FallbackExit, 'between'=>'<br />' )); ?>
<?php echo $this->Form->input('IvrMenu.file_exit', array('between'=>'<br />','type'=>'file','size'=>'50','label'=>__('Audio file',true)));?>
</fieldset>

<fieldset>
<h3>4. <?php echo __('Invalid');?> </h3>
<?php echo $this->Form->input('message_invalid',array('type'=>'text','size' => '93','label'=>false,'after' => $FallbackInvalid, 'between'=>'<br />' )); ?>
<?php echo $this->Form->input('IvrMenu.file_invalid', array('between'=>'<br />','type'=>'file','size'=>'50','label'=>__('Audio file',true)));?>
</fieldset>

<fieldset>

<?

echo "<legend>".__('Menu Options',true)."</legend>";

$path = $ivr['path'].$ivr['dir_node'];

       foreach($lam as $key => $entry){
             $lam[$key] = $this->Text->truncate($entry,$ivr['showLengthMax'],   array('ending' => '...','exact' => true,'html' => false));
       }
       foreach($voicemenu as $key => $entry){
             $voicemenu[$key] = $this->Text->truncate($entry,$ivr['showLengthMax'],array('ending' => '...','exact' => true,'html' => false));
       }

       foreach($nodes['title'] as $key => $entry){
             $nodes['title'][$key] = $this->Text->truncate($entry,$ivr['showLengthMax'],array('ending' => '...','exact' => true,'html' => false));
       }


     for($i=0;$i<8;$i++){

        echo $this->Form->input('Mapping.'.$i.'.digit',array('type'=>'hidden','value' => $i+1));	


        $default = false;
     	$options1=array('lam' =>'');
     	$options2=array('ivr' =>'');
     	$options3=array('node' =>'');

        $attributes=array('legend'=>false,'default'=>$default);


        $radio1 = $this->Form->radio('Mapping.'.$i.'.type',$options1,array('legend' => false, 'default' => true));
	$radio2 = $this->Form->radio('Mapping.'.$i.'.type',$options2,$attributes);
       	$radio3 = $this->Form->radio('Mapping.'.$i.'.type',$options3,$attributes);



        $row[$i]=array(
	"<h3>".__('#',true)." ".($i+1)."</h3>",
	$radio1, 
        $this->Form->input('Mapping.'.$i.'.lam_id',array('type'=>'select','options' => $lam,'label'=>'','empty'=>'- '.__('Select entry',true).' -' )),
	$radio2, 
        $this->Form->input('Mapping.'.$i.'.ivr_id',array('type'=>'select','options' => $voicemenu,'label'=>'','empty'=>'- '.__('Select entry',true).' -' )),
	$radio3, 
        $this->Form->input('Mapping.'.$i.'.node_id',array('type'=>'select','options' => $nodes['title'],'label'=>'','empty'=>'- '.__('Select entry',true).' -' )),


	);


     }

     $headers = array('','',__('Leave-a-Message',true),'',__('Voice Menu',true),'',__('Content',true));
     echo "<table cellspacing = 0 >";
     echo $this->Html->tableHeaders($headers);
     echo $this->Html->tableCells($row,array('class' =>'none'),array('class' =>'none'));
     echo "</table>";

     echo "</fieldset>";
     echo $this->Form->end(__('Save',true)); 


?>



