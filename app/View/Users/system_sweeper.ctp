<?php
/****************************************************************************
 * sweep.ctp	- Sweep Freedom Fone confidential data
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

      echo $this->Html->addCrumb(__('User management',true), '');
      echo $this->Html->addCrumb(__('Frontend Sweeper',true), '/users/system_sweeper');


      echo "<h1>".__("Frontend Sweeper",true)."</h1>";


     echo $this->Session->flash();



      echo $this->Form->create('User',array('type' => 'post','action'=> 'system_sweeper'));
      echo $this->Html->div('instruction', __('The Frontend Sweeper wipes out all confidential information (such as name, email and phone numbers) from Freedom Fone.',true));
      echo $this->Html->div('instruction', __('To wipe out Freedom Fone logs, please use the Backend Sweeper.',true));
      echo $this->Form->submit(__('Start Sweeper',true),  array('name' =>'submit', 'class' => 'save_button'));


?>
