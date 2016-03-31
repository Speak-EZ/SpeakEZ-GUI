<?php
/****************************************************************************
 * phonedirectoriescontroller.php	
 * version 		 	- 3.0.1500
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

class PhoneDirectoriesController extends AppController {

    var $name = 'PhoneDirectories';
	var $helpers = array('Session');

	function index() {

        $this->set('title_for_layout', __('Phone Directory',true));
        $this-> loadModel('PhoneDirectory');
		$recipientData = $this->PhoneDirectory->execute_query('select * from phone_directory', 'phone_directory');

		$this->set('recipientData', $recipientData);
	}


	function add() {

      	$this->set('title_for_layout', __('Add Contact Information',true));
		$data = $this->request->data;
		if (array_key_exists('PhoneDirectories', $data)) {
			$data = $data['PhoneDirectories'];
			if($data['name'] && $data['phone_no'] && $data['gender'] && $data['position']) {


		        $this-> loadModel('PhoneDirectory');
				$this->PhoneDirectory->execute_query('insert into phone_directory(recipient_name,phone_no, recipient_gender, recipient_age, recipient_position, recipient_description, recipient_information_1) values("'.$data['name'].'","'.$data['phone_no'].'","'.$data['gender'].'","'.$data['age'].'","'.$data['position'].'","'.$data['description'].'","'.$data['information'].'")', 'phone_directory');

				$this->Session->setFlash(__('New contact successfully saved.',true), 'success');  
  			} else {
				$this->Session->setFlash(__('Error saving data.',true), 'error');  
  			}
  			$this->redirect(array('action' => '/'));
		} 

		$this->render(); 
	}

	function edit($user_id) {

      	$this->set('title_for_layout', __('Add Contact Information',true));
		
		$data = $this->request->data;
		if (array_key_exists('PhoneDirectories', $data)) {
			//update data
			$data = $data['PhoneDirectories'];
			if($data['name'] && $data['phone_no'] && $data['gender'] && $data['position']) {


		        $this-> loadModel('PhoneDirectory');
				$this->PhoneDirectory->execute_query('update phone_directory set recipient_name = "'.$data['name'].'", phone_no="'.$data['phone_no'].'", recipient_gender="'.$data['gender'].'",recipient_age="'.$data['age'].'",recipient_position="'.$data['position'].'",recipient_description="'.$data['description'].'", recipient_information_1="'.$data['information'].'" where phone_id = '.$user_id, 'phone_directory');
				echo 'update phone_directory set recipient_name = "'.$data['name'].'", phone_no="'.$data['phone_no'].'", recipient_description="'.$data['description'].'", recipient_information_1="'.$data['information'].'"';
				$this->Session->setFlash(__('Data successfully updated.',true), 'success');  
  			} else {
				$this->Session->setFlash(__('Error saving data.',true), 'error');  
  			}
  			$this->redirect(array('action' => '/'));
		} else {
			// show data
			$this-> loadModel('PhoneDirectory');
			$phone_data = $this->PhoneDirectory->execute_query('select  * from phone_directory where phone_id='.$user_id, 'phone_directory');

			$this->set('phone_data', $phone_data);

		}
		$this->set('phone_id', $user_id);
		
		$this->render();
	}

	 function delete ($id = NULL){

    	if($id) {
    		$this-> loadModel('PhoneDirectory');
			$this->PhoneDirectory->execute_query('delete from phone_directory where phone_id = '.$id, 'phone_directory');

    	    $this->Session->SetFlash(__('The selected tag has been deleted.',true),'success');
	     	$this->redirect(array('action' => '/index'));

	     }
     }


}
?>
