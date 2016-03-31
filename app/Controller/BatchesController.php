<?php
/****************************************************************************
 * BatchesController.php	- Manage batches of outgoing SMS 
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

class BatchesController extends AppController{

      var $name = 'Batches';

      var $paginate = array('page' => 1, 'limit' => 25, 'order' => array( 'Batch.created' => 'desc')); 
      var $helpers = array('Time','Html', 'Session','Form','Flash');
      var $components = array('RequestHandler');
      var $layout ='jquery';


    function index(){

          $this->set('title_for_layout', __('SMS Batches',true));

     	  $this->Batch->recursive = 1; 
   	  $batch = $this->paginate();


      	  $sms_gateways = $this->Batch->SmsGateway->find('list', array('fields' => array('SmsGateway.name')));
      	  $gsm_gateways = $this->Batch->find('list', array('conditions' => array('sms_gateway_id' => 0), 'fields' => array('sender')));

 	  $this->set(compact('batch','sms_gateways','gsm_gateways'));

      }


    function recipient_disp() {
    $recipientPhoneList = array();
      if(isset($this->request->data['Recipient']) &&

        ($this->request->data['Recipient']['recipient_position'] != '' ||
        $this->request->data['Recipient']['recipient_gender'] != '' ||
        $this->request->data['Recipient']['recipient_description'] != '')

        ) {

        $lookup_array = array();
        if($this->request->data['Recipient']['recipient_position'] != '')
          $lookup_array[] = 'recipient_position="'.$this->request->data['Recipient']['recipient_position'].'"';

        if($this->request->data['Recipient']['recipient_gender'] != '')
          $lookup_array[] = 'recipient_gender="'.$this->request->data['Recipient']['recipient_gender'].'"';

        if($this->request->data['Recipient']['recipient_description'] != '')
          $lookup_array[] = 'recipient_description="'.$this->request->data['Recipient']['recipient_description'].'"';

        //Set Recipient Contact List
        $this->loadModel('PhoneDirectory');
        $recipientData = $this->PhoneDirectory->execute_query('select * from phone_directory where '.implode(' AND ', $lookup_array), 'phone_directory');
  
        foreach($recipientData as $key => $val){
          $recipientPhoneList[$val['phone_directory']['phone_no']] = $val['phone_directory']['recipient_name'].' '.$val['phone_directory']['recipient_description']. ' '.$val['phone_directory']['phone_no'];
        }

      } else {
        //Set Recipient Contact List
        $this->loadModel('PhoneDirectory');
        $recipientData = $this->PhoneDirectory->execute_query('select * from phone_directory', 'phone_directory');
  
        foreach($recipientData as $key => $val){
          $recipientPhoneList[$val['phone_directory']['phone_no']] = $val['phone_directory']['recipient_name'].' '.$val['phone_directory']['recipient_description']. ' '.$val['phone_directory']['phone_no'];
        }
      }

      $this->set('recipientopt',$recipientPhoneList);
    }



    function disp(){


    	     $sender = $this->request->data['Batch']['sender'];
    	     if(is_numeric($sender)){
		$data   = $this->paginate('Batch', array('Batch.sms_gateway_id' => $sender));
	     } elseif(!$sender)  {
	       $data   = $this->paginate('Batch');
	     } else {
	       $data   = $this->paginate('Batch', array('Batch.sender' => $sender));
	     }


    	     $this->set('batch',$data);  


    }

    function method(){

    if($this->request->data['Batch_method']['gateway_type']== 0){

	    $this->loadModel('SmsGateway');
      	    $this->set('options',$this->SmsGateway->find('list', array('fields' => array('id','name'))));
	    $this->set('gateway_type','IP_GW');

    } elseif ($this->request->data['Batch_method']['gateway_type']== 1){

	    $this->set('gateway_type','GSM_GW');
      	    $this->set('options',$this->Batch->getChannels());

    } 


    } 

    /* 
    //add method backup
    //Ping - 20160223
    function add(){

          $this->set('title_for_layout', __('Create SMS batch',true));

          //print_r($this->request->data);
          //die(); 
          
            //Process form data
	       if(array_key_exists('Batch', $this->request->data)){


	        //Validate data 
		$fileData = $this->request->data['Batch']['file'];
		unset($this->request->data['Batch']['file']);
		$this->request->data['Batch']['filename'] = $fileData['tmp_name'];



	        if ($this->Batch->saveAll($this->request->data['Batch'], array('validate' => 'only'))) {


		if($this->request->data['Batch']['gateway_type']== 'IP_GW'){
			$this->loadModel('SmsGateway');
			$tmp = $this->SmsGateway->findById($this->request->data['Batch']['sms_gateway_id']);
			$this->request->data['Batch']['gateway_code'] =  $tmp['SmsGateway']['gateway_code'];
		} else {
		        $this->request->data['Batch']['gateway_code'] =  substr($this->request->data['Batch']['sms_gateway_id'],0,2); 
		        $this->request->data['Batch']['sender'] =  $this->request->data['Batch']['sms_gateway_id'];
			$this->request->data['Batch']['sender_number'] =  $this->request->data['Batch']['sender'];
			unset($this->request->data['Batch']['sms_gateway_id']);
		}


		$receivers = file($fileData['tmp_name']);

		if($receivers){

		        $receivers = $this->validateReceivers($receivers, $this->request->data['Batch']['gateway_code'], $this->getPrefix());


			//Save batch data
	  		$this->Batch->save($this->request->data['Batch']);
	 		$batch_id = $this->Batch->getLastInsertId();


			  foreach($receivers as $key => $receiver){
		  	    $this->request->data['SmsReceiver'][$key]['batch_id'] = $batch_id;
		  	    $this->request->data['SmsReceiver'][$key]['receiver'] = trim($receiver);
			   

	 		  //Save sms receiver data
	 		  $this->Batch->SmsReceiver->create($this->request->data['SmsReceiver'][$key]);
	 		  $this->Batch->SmsReceiver->saveAll($this->request->data['SmsReceiver'][$key],array('validate' => false));
			  $sms_receiver_id[] = $this->Batch->SmsReceiver->getLastInsertId();
			 
			  }



		          $status = $this->Batch->processBatch($batch_id); 


			  //For Clickatell: update apimsgid for receivers
			  if($this->request->data['Batch']['gateway_code'] == 'CT'){

			  	foreach($receivers as $key => $receiver){
		  	  	  		   $data['SmsReceiver'][$key]['id'] = $sms_receiver_id[$key];
						   $data['SmsReceiver'][$key]['apimsgid'] = $status[0][$key];
				}

	 		        $this->Batch->SmsReceiver->saveAll($data['SmsReceiver'], array('validate' => false));
		
		
			  } //Clickatell
			  elseif($this->request->data['Batch']['gateway_code'] == 'GM'){


			  	foreach($receivers as $key => $receiver){
		  	  	  		   $data['SmsReceiver'][$key]['id'] = $sms_receiver_id[$key];
						   $data['SmsReceiver'][$key]['gateway_id'] = $status[0][$key];
				}


				$this->Batch->SmsReceiver->saveAll($data['SmsReceiver'], array('validate' => false));
			  }

		} //receivers

		 //Save batch status
		 if(!$status){
			$status=false;

		 } elseif(is_array($status[0])){
		        $status=true;
		 } else { 
		        $status=false;
			$this->Session->setFlash(__("The batch failed. Please review your gateway credentials and receivers numbers.",true), "error");
		 }

		 $this->Batch->id = intval($batch_id);
	       	 $this->Batch->saveField('status', $status);	

		 $this->redirect(array('action' => 'index'));		 

                } //validate
		else {

		 $errors = $this->Batch->validationErrors;
		 foreach($errors as $key => $error){
		 	$this->Session->setFlash($error[0], "error", array(), $key);
		 }

		}
	       }

	       


      } //add
      */

      // add method modified
      // Ping 20160223
      // Ping 20160304
      function add(){

          $this->set('title_for_layout', __('Create SMS batch',true));

        //Set Recipient Contact List
        $this->loadModel('PhoneDirectory');
        $recipientData = $this->PhoneDirectory->execute_query('select * from phone_directory', 'phone_directory');

        //get unique description
        $recipient_description = array();
        foreach($recipientData as $recipient){;
          $recipient_description[$recipient['phone_directory']['recipient_description']] = $recipient['phone_directory']['recipient_description'];
        }

        $recipient_description = array_unique($recipient_description);

        // get recipient phone directory
        $recipientData = $this->PhoneDirectory->execute_query('select * from phone_directory', 'phone_directory');
        
        foreach($recipientData as $key => $val){
          $recipientPhoneList[$val['phone_directory']['phone_no']] = $val['phone_directory']['recipient_name'].' '.$val['phone_directory']['recipient_description']. ' '.$val['phone_directory']['phone_no'];
        }
        $this->set('recipientopt',$recipientPhoneList);
        $this->set('recipient_description',$recipient_description);


            //Process form data
	       if(array_key_exists('Batch', $this->request->data)){


	        //Validate data 
		//$fileData = $this->request->data['Batch']['file'];
		//unset($this->request->data['Batch']['file']);
		//$this->request->data['Batch']['filename'] = $fileData['tmp_name'];



	        if ($this->Batch->saveAll($this->request->data['Batch'], array('validate' => 'only'))) {


		if($this->request->data['Batch']['gateway_type']== 'IP_GW'){
			$this->loadModel('SmsGateway');
			$tmp = $this->SmsGateway->findById($this->request->data['Batch']['sms_gateway_id']);
			$this->request->data['Batch']['gateway_code'] =  $tmp['SmsGateway']['gateway_code'];
		} else {
		        $this->request->data['Batch']['gateway_code'] =  substr($this->request->data['Batch']['sms_gateway_id'],0,2); 
		        $this->request->data['Batch']['sender'] =  $this->request->data['Batch']['sms_gateway_id'];
			$this->request->data['Batch']['sender_number'] =  $this->request->data['Batch']['sender'];
			unset($this->request->data['Batch']['sms_gateway_id']);
		}


		//$receivers = file($fileData['tmp_name']);
		print_r($this->request->data['Batch']);

		$receivers =  $this->request->data['Batch']['Recipient'];

		if($receivers){
			$receivers = str_replace('+', '', $receivers);

			//Save batch data
	  		$this->Batch->save($this->request->data['Batch']);
	 		$batch_id = $this->Batch->getLastInsertId();


			  foreach($receivers as $key => $receiver){
		  	    $this->request->data['SmsReceiver'][$key]['batch_id'] = $batch_id;
		  	    $this->request->data['SmsReceiver'][$key]['receiver'] = trim($receiver);
			   

		 		  //Save sms receiver data
		 		  $this->Batch->SmsReceiver->create($this->request->data['SmsReceiver'][$key]);
		 		  $this->Batch->SmsReceiver->saveAll($this->request->data['SmsReceiver'][$key],array('validate' => false));
				  $sms_receiver_id[] = $this->Batch->SmsReceiver->getLastInsertId();
				 
			  }



		          $status = $this->Batch->processBatch($batch_id); 


			  //For Clickatell: update apimsgid for receivers
			  if($this->request->data['Batch']['gateway_code'] == 'CT'){

			  	foreach($receivers as $key => $receiver){
		  	  	  		   $data['SmsReceiver'][$key]['id'] = $sms_receiver_id[$key];
						   $data['SmsReceiver'][$key]['apimsgid'] = $status[0][$key];
				}

	 		        $this->Batch->SmsReceiver->saveAll($data['SmsReceiver'], array('validate' => false));
		
		
			  } //Clickatell
			  elseif($this->request->data['Batch']['gateway_code'] == 'GM'){


			  	foreach($receivers as $key => $receiver){
		  	  	  		   $data['SmsReceiver'][$key]['id'] = $sms_receiver_id[$key];
						   $data['SmsReceiver'][$key]['gateway_id'] = $status[0][$key];
				}


				$this->Batch->SmsReceiver->saveAll($data['SmsReceiver'], array('validate' => false));
			  }

		} //receivers

		 //Save batch status
		 if(!$status){
			$status=false;

		 } elseif(is_array($status[0])){
		        $status=true;
		 } else { 
		        $status=false;
			$this->Session->setFlash(__("The batch failed. Please review your gateway credentials and receivers numbers.",true), "error");
		 }

		 $this->Batch->id = intval($batch_id);
	       	 $this->Batch->saveField('status', $status);	

		 $this->redirect(array('action' => 'index'));		 

                } //validate
		else {

		 $errors = $this->Batch->validationErrors;
		 foreach($errors as $key => $error){
		 	$this->Session->setFlash($error[0], "error", array(), $key);
		 }

		}
	       }

	       


      } //add

    // ad-hoc function for FS javascript
    // bypassed Auth check -> edited AppController/beforeFilter
    // Author - Ping
    function addRemoteBatch(){
    	$this->autoRender=false;

    	//proceed to send an sms, no receiver list required - POC
	    //Validate data - take message and reciever numbers    	
    	if(!isset($this->request->data['poll_id'])) {
    		return false;

    	} 
    	$poll_id = $this->request->data['poll_id'];
    	$poll_recipient = $this->request->data['recipient'];
    	
    	$this->loadModel('VoicePoll');
    	$poll_rule = $this->VoicePoll->execute_query('select * from poll_rule where poll_id='.$poll_id, 'poll_rule');
        
    	if(count($poll_rule) != 1) {
    		return false;
    	}

    	$action_data = json_decode($poll_rule[0]['poll_rule']['action_taken']);
    	//print_r($action_data->sms);

    	$receivers = explode(',',$action_data->sms->recipient);
    	$text_body = $action_data->sms->message;
    	$text_body = str_replace('[poll_recipient]', $poll_recipient, $text_body);

    	
    	//forming data format
		$batch= array(
						'name' => 'Polling Triage Rule-Based, #'.$poll_id.' - '.time(),
						'body' => $text_body,
						'filename' => 'file/not/exists',
						'created' => time(),
						'gateway_type' => 'IP_GW',
						'status' => '',
						'sms_gateway_id' => '5',	// current gateway = 5 (KarenCIS_1)
						'sender_number' => '13474747568',
					);

		print_r($receivers);
		print_r($batch);
		print_r($poll_recipient);
		
	    if ($this->Batch->saveAll($batch, array('validate' => 'only'))) {


			if($batch['gateway_type']== 'IP_GW'){
				$this->loadModel('SmsGateway');
				$tmp = $this->SmsGateway->findById($batch['sms_gateway_id']);
				$batch['gateway_code'] =  $tmp['SmsGateway']['gateway_code'];
			}

			


			//Save batch data
	  		$this->Batch->save($batch);
	 		$batch_id = $this->Batch->getLastInsertId();


			foreach($receivers as $key => $receiver){
		  	    $this->request->data['SmsReceiver'][$key]['batch_id'] = $batch_id;
		  	    $this->request->data['SmsReceiver'][$key]['receiver'] = trim($receiver);
			  

				//Save sms receiver data
				$this->Batch->SmsReceiver->create($this->request->data['SmsReceiver'][$key]);
				$this->Batch->SmsReceiver->saveAll($this->request->data['SmsReceiver'][$key],array('validate' => false));
				$sms_receiver_id[] = $this->Batch->SmsReceiver->getLastInsertId();
			 
			}


			// TO-DO - uncomment when parameters are ready
		    $status = $this->Batch->processBatch($batch_id); 

			
			//For Clickatell: update apimsgid for receivers
			foreach($receivers as $key => $receiver){
				$data['SmsReceiver'][$key]['id'] = $sms_receiver_id[$key];
				$data['SmsReceiver'][$key]['apimsgid'] = $status[0][$key];
			}
		    $this->Batch->SmsReceiver->saveAll($data['SmsReceiver'], array('validate' => false));


			 //Save batch status
			 if(!$status){
				$status = false;

			 } elseif(is_array($status[0])){
			        $status=true;
			 } else { 
			        $status=false;
				$this->Session->setFlash(__("The batch failed. Please review your gateway credentials and receivers numbers.",true), "error");
			 }

			 $this->Batch->id = intval($batch_id);
		     $this->Batch->saveField('status', $status);	

	    }
	       
    }

	// ad-hoc function for LAM Triage
	// for internal call - no verification - To be implemented
    // Author - Ping
    function addLAMBatch($data){
    	$this->autoRender=false;
    	
    	// lookup caller id (phone_directory)
		$this->loadModel('PhoneDirectory');
		$sender_number = '+'.ltrim($data['sender'], '0');
		$registered_user_lookup = $this->PhoneDirectory->execute_query('select recipient_name from phone_directory where phone_no ="'.$sender_number.'"', 'phone_directory');

		if(count($registered_user_lookup) == 1) {
			$data['sender'] = $registered_user_lookup[0]['phone_directory']['recipient_name'].'('.$sender_number.')';
		} else {
			$data['sender'] = $sender_number;
		}

    	$text_body = 'You\'ve received a new voice message in [lm_menu_title] from [sender] at [created]';
    	$text_body = str_replace('[lm_menu_title]', $data['title'], $text_body);
    	$text_body = str_replace('[sender]', $data['sender'], $text_body);
    	$text_body = str_replace('[created]', date("H:i:s", $data['created']), $text_body);

    	//forming data format
		$batch= array(
						'name' => 'LAM Triage Rule-Based, #'.$data['title'].' - '.microtime(),
						'body' => $text_body,
						'filename' => 'file/not/exists',
						'created' => time(),
						'gateway_type' => 'IP_GW',
						'status' => '',
						'sms_gateway_id' => '5',	// current gateway = 5 (KarenCIS_1)
						'sender_number' => '13474747568',
					);
		$receivers = explode(',', $data['recipient']);
		print_r($receivers);
		print_r($batch);
		
	    if ($this->Batch->saveAll($batch, array('validate' => 'only'))) {


			if($batch['gateway_type']== 'IP_GW'){
				$this->loadModel('SmsGateway');
				$tmp = $this->SmsGateway->findById($batch['sms_gateway_id']);
				$batch['gateway_code'] =  $tmp['SmsGateway']['gateway_code'];
			}

			


			//Save batch data
	  		$this->Batch->save($batch);
	 		$batch_id = $this->Batch->getLastInsertId();


			foreach($receivers as $key => $receiver){
		  	    $this->request->data['SmsReceiver'][$key]['batch_id'] = $batch_id;
		  	    $this->request->data['SmsReceiver'][$key]['receiver'] = trim($receiver);
			  

				//Save sms receiver data
				$this->Batch->SmsReceiver->create($this->request->data['SmsReceiver'][$key]);
				$this->Batch->SmsReceiver->saveAll($this->request->data['SmsReceiver'][$key],array('validate' => false));
				$sms_receiver_id[] = $this->Batch->SmsReceiver->getLastInsertId();
			 
			}


			// TO-DO - uncomment when parameters are ready
		    $status = $this->Batch->processBatch($batch_id); 

			
			//For Clickatell: update apimsgid for receivers
			foreach($receivers as $key => $receiver){
				$data['SmsReceiver'][$key]['id'] = $sms_receiver_id[$key];
				$data['SmsReceiver'][$key]['apimsgid'] = $status[0][$key];
			}
		    $this->Batch->SmsReceiver->saveAll($data['SmsReceiver'], array('validate' => false));


			 //Save batch status
			 if(!$status){
				$status = false;

			 } elseif(is_array($status[0])){
			        $status=true;
			 } else { 
			        $status=false;
				$this->Session->setFlash(__("The batch failed. Please review your gateway credentials and receivers numbers.",true), "error");
			 }

			 $this->Batch->id = intval($batch_id);
		     $this->Batch->saveField('status', $status);	

	    }
	       

    }


    function delete ($id){

    
    	     if($this->Batch->delete($id))
	     {
		$this->Session->setFlash(__('SMS batch has been deleted.',true),'success');
                $this->log('[INFO], SMS DELETED; Id: '.$id, 'batch');
	     	$this->redirect(array('action' => 'index'));
	     }

    }



    function view($id){

          $this->set('title_for_layout', __('SMS Receivers',true));

        if($id){
     	     $this->requestAction('/batches/update/'.$id);

     	     $this->Batch->recursive = 1; 
             $batches = $this->paginate('Batch', array('Batch.id' => $id));
	     $this->set(compact('batches'));

	     }
    }


    function update($batch_id){

      $data = $this->Batch->findById($batch_id);
      $code = $data['Batch']['gateway_code'];


      if($code != 'OR'){
      $sms = $this->Batch->authBatch($batch_id);

      foreach($data['SmsReceiver']  as $key => $entry){

         //Update status
	 if($code == 'CT') { $id = $entry['apimsgid'];} 
	 elseif ($code == 'GM') { $id = $entry['gateway_id'];} 

    	 $status = $this->Batch->getStatus($sms, $code, $id);

	 $this->Batch->SmsReceiver->id = $entry['id'];
	 $this->Batch->SmsReceiver->saveField('status', $status);


      }


      } 
    }


    function process($id){

    	     Configure::write('debug', 0);

    	     if(array_key_exists('Submit', $this->request->data)){

	        if($this->request->data['Submit'] == __('Delete')){

		  foreach($this->request->data['batch'] as $id){
		    $this->Batch->delete($id);
                  }

         	  $this->redirect(array('action' => 'index'));	     
		} elseif ($this->request->data['Submit'] == __('Export')){


             	$this->set('data', $this->Batch->findById($id));

    	     	$this->layout = null;
    	     	$this->autoLayout = false;

    	     	$this->render();    



	     } //elseif

             }//if


} 

}
?>
