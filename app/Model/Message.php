<?php
/****************************************************************************
 * message.php		- Model for Leave-a-message entries.
 * version 		- 3.0.1500
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

class Message extends AppModel {

	//edited
	var $name = 'Message';
	
	var $belongsTo = array('Category','Caller'); 

	var $hasAndBelongsToMany = array('Tag');



/*
 * Return message title
 * 
 * @param int $id
 * @return string $title
 *
 */

    function getTitle($id){

       $data = $this->findById($id);
       return $data['Message']['title'];     
    }


/*
 * Fetch new data from spooler
 * 
 * 
 * 
 *
 */
      function refresh(){

      $this->autoRender = false;
      $array = Configure::read('lm_in');

	      
	    $obj = new ff_event($array);	       

	   if ($obj -> auth != true) {
    	          die(printf("Unable to authenticate\r\n"));
	   }

	   $message_entries = array();

 	   	while ($entry = $obj->getNext('delete')){

	       $created = intval(floor($entry['Event-Date-Timestamp']/1000000));
	       $length  = intval(floor(($entry['FF-FinishTimeEpoch']-$entry['FF-StartTimeEpoch'])/1000));   
	       $mode = $entry['FF-CallerID'];
	       $value = $entry['FF-CallerName'];

//$this->PhoneDirectory->execute_query("select recipient_name from phone_directory where phone_no = '+13154805045'", "phone_directory")

	       $data= array ( 'sender'          => $this->sanitizePhoneNumber($entry['FF-CallerID']),
	       	      	      'file'            => $entry['FF-FileID'],
	       	      	      'created'         => $created,
			      		'length'          => $length,
	       		      'url'             => $entry['FF-URI'],
	       		      'instance_id'     => $entry['FF-InstanceID'],
      			      'quick_hangup'    => $entry['FF-OnQuickHangup'],
                              );
	       $this->log('[INFO] NEW MESSAGE, Sender: '.$entry['FF-CallerID'], 'message');	
	       $this->create();
	       $this->save($data);

	       		//collecting message info - Ping 201603
	       		$message_entry = array();
	       		$message_entry['instance_id'] = $entry['FF-InstanceID'];
	       		$message_entry['sender'] = $this->sanitizePhoneNumber($entry['FF-CallerID']);
	       		$message_entry['created'] = $created;

	       		array_push($message_entries, $message_entry);

               //Check if CDR with the same call_id exists with length=false
                $this->query("UPDATE cdr set length = ".$length.", quick_hangup = '".$entry['FF-OnQuickHangup']."' where call_id = '".$entry['FF-FileID']."' and channel_state='CS_ROUTING'");

        } 

        // perform LmMenuRule lookup and triage rule - Ping 201603
        if(count($message_entries) > 0){
        	foreach($message_entries as $message_entry) {

		        $lm_menu_lookup = $this->query("SELECT id, title from lm_menus where instance_id = ".$message_entry['instance_id']);
		      	$lm_menu_id = $lm_menu_lookup[0]['lm_menus']['id'];
		      	$lm_menu_title = $lm_menu_lookup[0]['lm_menus']['title'];
			   	$lm_menu_rule_lookup = $this->query("SELECT lm_menu_type, designated_recipient from lm_menu_rule where lm_menu_id = ".$lm_menu_id);

			   	if($lm_menu_rule_lookup[0]['lm_menu_rule']['lm_menu_type'] == 'Priority') {
			   		// deliver sms to designaed recipient
			   		$designated_recipient = $lm_menu_rule_lookup[0]['lm_menu_rule']['designated_recipient'];
			   		
			   		$data = array(
			   						'title' => $lm_menu_title,
			   						'recipient' => $designated_recipient,
			   						'sender' => $message_entry['sender'],
			   						'created' => $message_entry['created'],
			   					);

			   		// bypassing Controller function - not recommended but  ¯\_(ツ)_/¯ 
			   		App::import('Controller', 'Batches');
		            $batches = new BatchesController;
		            $batches->addLAMBatch($data);  
			   	}

        	}
        } 

        /* 
        //test bed
      	$lm_menu_lookup = $this->query("SELECT id from lm_menus where instance_id = 112");
      	$lm_menu_id = $lm_menu_lookup[0]['lm_menus']['id'];
	   	$lm_menu_rule_lookup = $this->query("SELECT lm_menu_type, designated_recipient from lm_menu_rule where lm_menu_id = ".$lm_menu_id);

	   	if($lm_menu_rule_lookup[0]['lm_menu_rule']['lm_menu_type'] == 'Priority') {
	   		$designated_recipient = $lm_menu_rule_lookup[0]['lm_menu_rule']['designated_recipient'];
	   		echo $designated_recipient;
	   	}
		*/

     }
}
?>
