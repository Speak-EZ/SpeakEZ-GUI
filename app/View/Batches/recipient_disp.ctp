<?php
/****************************************************************************
 * disp.ctp	- Deliver SMS
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


$ext = Configure::read('EXTENSIONS');

echo $this->Html->tableCells(array (
    array( 
    $this->Form->select('Batch.Recipient', $recipientopt, array(
    'multiple' => 'checkbox'
     ))
    //,$this->Form->select('Model.field', $recordingopt)
    )),array('class'=>'blue'),array('class'=>'blue'));

?>