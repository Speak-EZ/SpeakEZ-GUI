<?php
/****************************************************************************
 * polls_controller.php   - Controller for polls. Manages CRUD operations on polls and votes.
 * version      - 3.0.1700
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


class CreateAlertsController extends AppController{
//test4
      var $name = 'CreateAlerts';

      var $helpers = array('Time','Html', 'Session','Form','Formatting','Number','Js');

      var $layout = 'jquery';
      function refresh($method = null){

               $this->autoRender = false;
               $this->logRefresh('poll',$method); 
               $this->Poll->refresh();

      }

      function index(){

        $this->set('title_for_layout', __('Create Alert',true));
        //$this->Poll->unbindModel(array('hasMany' => array('User')));
        //$this->set('polls',$this->Poll->find('all',array('order'=>'Poll.created DESC')));

	//test calling database model
	//$this->loadModel('VoicePoll');
	//print_r($this->VoicePoll->execute_query("select * from poll_response", 'poll_response'));

        $this->render(); 

      }


     function view($id){

        $this->set('title_for_layout', __('View Poll',true).' : '.$this->Poll->getTitle($id));
        $this->Poll->id = $id;
        $data = $this->Poll->findById($id);
        $this->set(compact('data'));

      }

    function send(){
      $this->set('title_for_layout', __('Upload Alert File',true));
	           print_r($this->request->data);

            if($this->request->data['UploadMenu']['title']&&$this->request->data['UploadMenu']['descr']&&$this->request->data['UploadMenu']['file']){
                $file = $this->request->data['UploadMenu']['file'];

          		if ($file['size']){
          		  
                $fileTitle=$this->request->data['UploadMenu']['title'];
                $fileDescr=$this->request->data['UploadMenu']['descr'];

          		  // FILE UPLOAD
                // Insert file details into database
          		  $this->CreateAlert->execute_query('insert into voice_message(voice_name,voice_text,voice_file_location) values("'.$fileTitle.'","'.$fileDescr.'","/opt/freedomfone/gui/app/webroot/upload/voice_push/voiceId/voice_1.wav")', 'voice_message');
                
                //Fetching Voice Id
                $queryResult = $this->CreateAlert->execute_query('select voice_id from voice_message where voice_name="'.$fileTitle.'" and voice_text="'.$fileDescr.'"', 'voice_message');
                $voiceId = ($queryResult[0]['voice_message']['voice_id']);


        		    // Spliting file name and type
                $fileSplit = explode(".",$file['name']);

                //Renaming File name
                $file['fileName'] = "voice_".$voiceId;

                //Updating Voice File Location column in Database
                $this->CreateAlert->execute_query('update voice_message set voice_file_location="/opt/freedomfone/gui/app/webroot/upload/voice_push/'.$voiceId.'/voice_'.$voiceId.'.'.$fileSplit[1].'" where voice_id='.$voiceId.'', 'voice_message');
                
                //Uploading File to updated location
                $fileOK = $this->uploadFiles('upload/voice_push', array($file) , $voiceId, 'audio', true, true);
        		    //$folder, $data, $itemId = null, $filetype, $useKey, $overWrite

                if(array_key_exists('urls', $fileOK)){
                  $this->Session->setFlash(__('The file was successfully uploaded.',true),'success');        
            		}
                    
               }elseif ($file['error']==1 && !$file['size']) {
                  $this->Session->setFlash(__('The file %s could not be uploaded due to file size restrictions',$file['name']), 'error', array(), $key);
             	$this->Session->setFlash(__("Error uploading file.",true),'success');
               }
   
            //$this->params['form']['File']['data'] = $fileData;         
            //$this->File->save($this->params['form']['File']);
              //TODO: Upload RECORDING FILE
              /*$fileOK = $this->uploadFiles($lm_settings['path'].$this->request->data['UploadMenu']['instance_id']."/".$lm_settings['dir_menu'], $fileData ,false,'audio',true,true);
              $this->LmMenu->id = $this->request->data['UploadMenu']['id'];
*/
              /*$file = rand(1000,100000)."-".$_FILES['file']['name'];
              $file_loc = $_FILES['file']['tmp_name'];
              $file_size = $_FILES['file']['size'];
              $file_type = $_FILES['file']['type'];
              $folder="uploads/";             
              move_uploaded_file($file_loc,$folder.$file);
              $sql="INSERT INTO voice_delivery(voice_id,voice_name,voice_text,voice_file_location) VALUES('$file','$fileTitle','$fileDescr')";
              mysql_query($sql); */

              //if ($this->LmMenu->save($this->request->data['UploadMenu'])) {
                  //$this->Session->setFlash(__('The file was successfully uploaded.',true),'success');
              //}
            }else if($this->request->data['UploadMenu']['descr']&&$this->request->data['UploadMenu']['file']){
                  $this->Session->setFlash(__('Please enter File Name.',true),'error');
                }
            else if($this->request->data['UploadMenu']['title']&&$this->request->data['UploadMenu']['file']){
              $this->Session->setFlash(__('Please enter File Description.',true),'error');
            }else{
              $this->Session->setFlash(__('Please upload a File.',true),'error');
            }

        $this->redirect(array('action' => '/add'));
    }

   function add(){

       $this->set('title_for_layout', __('Create Alert',true));
        $this->render(); 
    }

    function immediate(){
        $this->set('title_for_layout', __('Send Immediate Alert',true));

        //Set Recipient Contact List
        $this->loadModel('PhoneDirectory');
        $recipientData = $this->PhoneDirectory->execute_query('select * from phone_directory', 'phone_directory');

        //get unique description
        $recipient_description = array();
        foreach($recipientData as $recipient){;
          $recipient_description[$recipient['phone_directory']['recipient_description']] = $recipient['phone_directory']['recipient_description'];
        }

        $recipient_description = array_unique($recipient_description);

        foreach($recipientData as $key => $val){
          $recipientPhoneList[$val['phone_directory']['phone_no']] = $val['phone_directory']['recipient_name'].' '.$val['phone_directory']['recipient_description']. ' '.$val['phone_directory']['phone_no'];
        }
        $this->set('recipientopt',$recipientPhoneList);

        //Set Recording List
        $recordingData = $this->CreateAlert->execute_query('select * from voice_message', 'voice_message');
        foreach($recordingData as $key => $val){
          $recordingPhoneList[]= $val['voice_message']['voice_name'];
        }
        $this->set('recordingopt',$recordingPhoneList);
        $this->set('recipient_description',$recipient_description);

        $this->render();
    }

    function disp() {
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

    function sendimmediate(){
      $this->set('title_for_layout', __('Sending Immediate Alert',true));
      
      if(isset($this->request->data['AlertMenu']['recording']) AND $this->request->data['AlertMenu']['recording'] != '' AND $this->request->data['AlertMenu']['recipient']){
              
        /*
        $selectedRecipient=array();
        foreach($this->request->data['AlertMenu']['recipient'] as $key1 => $val1){
          foreach($recipientData as $key2 => $val2){        
            if($val1 == $key2){
              $selectedRecipient[] = $val2['phone_directory']['phone_no'];
            }
          }
        }
        */
        $numberlist = implode($this->request->data['AlertMenu']['recipient'], '|');

        //Fetch Recording
        $recordingData = $this->CreateAlert->execute_query('select * from voice_message', 'voice_message');
        foreach($recordingData as $key => $val){        
            if($key == $this->request->data['AlertMenu']['recording']){
              $selectedRecording = $val['voice_message']['voice_id'];
            }
          }
        
        //Sending Alert
        $output = shell_exec('fs_cli -x "jsrun /home/sharicus/Desktop/outbound_call/scripts/voice_push/call_dispatcher.js '.$selectedRecording.' '.$numberlist.'"'); 
          
        //print_r('fs_cli -x "jsrun /home/sharicus/Desktop/outbound_call/scripts/voice_push/call_dispatcher.js '.$selectedRecording.' '.$numberlist.'"');
        //print_r('fs_cli -x "jsrun /home/sharicus/Desktop/outbound_call/scripts/voice_push/call_dispatcher.js '.$selectedRecording.' '.$numberlist.'"');
        
        $this->Session->setFlash(__('Alert sent successfully.',true),'success');
      }elseif($this->request->data['AlertMenu']['recipient']){
        $this->Session->setFlash(__('Please select a recording',true),'error');
      }else{
        $this->Session->setFlash(__('Please select a recipient',true),'error');
      }
     $this->redirect(array('action' => '/immediate'));
    }

    function scheduled(){
        $this->set('title_for_layout', __('Set Scheduled Alert',true));
        //$this->redirect(array('action' => '/add'));
        $this->render(); 
    }

    function delete ($id){

        $this->set('title_for_layout', __('Delete Poll',true));

           $title = $this->Poll->getTitle($id);
           if($this->Poll->delete($id,true))
       {
           $this->Session->setFlash(__('The following poll has been deleted',true).': '.$title, 'success', array());
             $this->log('[INFO] POLL DELETED, Id: '.$id.', Title: '.$title, 'poll');           
       $this->redirect(array('action' => '/index'));


       }



    }


    function unlink ($id,$poll_id){

    $result =  $this->Poll->Vote->find('count',array('conditions' => array('Vote.poll_id' =>$poll_id)));
    
       if($result > 2){
           if($this->Poll->Vote->delete($id,true))
       {
             $this->log('[INFO] POLL OPTION DELETED, Id: '.$id, 'poll');           
       $this->redirect(array('action' => '/edit/'.$poll_id));
       }
       } else {

       $this->Session->setFlash(__('Poll option could not be deleted. A poll needs at least two options.',true),'warning');
       $this->redirect(array('action' => '/edit/'.$poll_id));
       }



    }


   function edit($id = null){

        $this->set('title_for_layout', __('Edit Poll',true).' : '.$this->Poll->getTitle($id));   
 
     if (!$id && empty($this->request->data)){ 
      $this->Session->setFlash(__('Invalid Poll', true)); 
      $this->redirect(array('action'=>'index')); 
      } elseif (empty($this->request->data['Poll'])){ 


    $this->request->data = $this->Poll->read(null,$id);
                $votes = $this->Poll->Vote->find('all',array('conditions' =>array('Poll.id' => $id)));
    $this->set(compact('votes'));

     } else{
   
                //Fetch form data
          $this->Poll->set( $this->request->data );

          //Validate data (poll and vote)
          if ($this->Poll->saveAll($this->request->data, array('validate' => 'only'))) {

              //Save poll data

              $this->Poll->save($this->request->data['Poll']);
                    $this->log('[INFO] POLL EDITED, Id: '.$id, 'poll');          

                         if ( $this->Poll->find('first', array('conditions' => array('id' => $id, 'status' =>2)))){                                                                                                                                                                            
                            $this->Session->setFlash(__("The poll has been updated. Please note that the poll's closing time has already passed.",true),'warning');
                            $this->log('[WARNING] Poll closing time has passed, Id: '.$id, 'poll');
                                   
                         } else {
                      
                            $this->Session->setFlash(__("The poll has been updated.",true),'success');
                         }    
  
                        $this->redirect(array('action' => 'index'));

                 } else {

                  $votes = $this->Poll->Vote->find('all',array('conditions' =>array('Poll.id' => $id)));
      $this->set(compact('votes'));

                 } 
            }
    }


}
?>
