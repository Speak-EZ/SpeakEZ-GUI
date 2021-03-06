<?php
/****************************************
 * add_failure.ctp	- View for listing Poll options (after failure of  adding a Poll option)
 * version 	        - 3.0.1500
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
/***************************************************************************/


     echo $this->Html->div('error',__("Invalid format",true));

     foreach ($votes as $key => $vote){

             $delete = $this->Js->link($this->Html->image("icons/delete.png"),
                                   array('controller' => 'votes', 'action' => 'delete/'.$vote['Vote']['id'].'/'.$this->data['Poll']['id']),
                                   array('update' => '#votes', 'escape' => false,'class' => 'votes'));




             $row[] = array(__('Option',true).' '.($key+1), $vote['Vote']['chtext'], $delete);

     }

     echo "<table width='500px' cellspacing='0' class='blue'>";  
     echo $html->tableCells($row, array('class' => 'blue'),array('class' => 'blue'));
     echo "</table>";



?>