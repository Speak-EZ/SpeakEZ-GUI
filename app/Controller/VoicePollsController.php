<?php
/****************************************************************************
 * VoicePoll.php   - Controller for voice polls. Manages CRUD operations on voice polls
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


class VoicePollsController extends AppController{

      var $name = 'VoicePolls';

      var $helpers = array('Time','Html', 'Session','Form','Formatting','Number','Js');

      var $layout = 'jquery';
      function refresh($method = null){

               $this->autoRender = false;
               $this->logRefresh('poll',$method); 
               $this->Poll->refresh();

      }



    function index(){

        $this->set('title_for_layout', __('Voice Poll',true));

        $poll_group = $this->VoicePoll->execute_query('select * from poll_group', 'poll_group');
        $poll_response_unique_ptcp = $this->VoicePoll->execute_query('select count(distinct recipient_no) as count_unique, poll_id from poll_response group by poll_id', 'poll_response');
        
        $this->set('poll_group', $poll_group);
        $this->set('poll_response_unique_ptcp', $poll_response_unique_ptcp);
        $this->render(); 

    }


     function view($pollId = null){

        if($pollId) {
            $this->set('title_for_layout', __('View Poll',true).' : '.($pollId));    

            //get poll group
            $poll_group = $this->VoicePoll->execute_query('select * from poll_group where poll_id='.$pollId, 'poll_group');
            // print_r($poll_group);
            if(count($poll_group) == 1){
                $poll_name = $poll_group[0]['poll_group']['poll_name'];
                $number_of_question = $poll_group[0]['poll_group']['number_of_question'];

                //get poll questions
                $poll_questions = $this->VoicePoll->execute_query('select * from poll_question where poll_id='.$pollId, 'poll_question');
                //get poll response
                $poll_responses = $this->VoicePoll->execute_query('select * from poll_response where poll_id='.$pollId, 'poll_response');
                //get poll rule
                $poll_rule = $this->VoicePoll->execute_query('select * from poll_rule where poll_id='.$pollId, 'poll_rule');

                $poll_rule_batches = array();
                if(count($poll_rule)) {
                  //select messages from batches sent
                  $poll_rule_batches = $this->VoicePoll->execute_query('select * from batches where name like "%Polling Triage Rule-Based, #'.$pollId.' -%"', 'batches');
                }

                $poll_data = array(
                                    'poll_id' => $pollId,
                                    'poll_name' => $poll_name,
                                    'number_of_question' => $poll_group[0]['poll_group']['number_of_question'],
                                    'poll_questions' => $poll_questions,
                                    'poll_responses' => $poll_responses,
                                    'poll_rule' => $poll_rule,
                                    'poll_rule_batches' => $poll_rule_batches
                                );

                $this->set('data', $poll_data);
            }
        }
        

      }

    function send(){
        $this->set('title_for_layout', __('Creating Poll',true));
        print_r($this->request->data['UploadMenu']);
        //get poll name and at least one question
        if($this->request->data['UploadMenu']['name']&&
            $this->request->data['UploadMenu'][0]['question']&&
            $this->request->data['UploadMenu'][0]['choice']&&
            $this->request->data['UploadMenu'][0]['file']
            ){

            $poll_name = $this->request->data['UploadMenu']['name'];
            $recipient = $this->request->data['UploadMenu']['recipient'];
            $message = $this->request->data['UploadMenu']['message'];

            unset($this->request->data['UploadMenu']['name']);
            unset($this->request->data['UploadMenu']['recipient']);
            unset($this->request->data['UploadMenu']['message']);
            
            $question_counter = 1;
            $number_of_question = count($this->request->data['UploadMenu']);

            //create new poll group and get poll id
            $this->VoicePoll->execute_query('insert into poll_group(number_of_question, poll_name) values('.$number_of_question.',"'.$poll_name.'")', 'poll_group');
                    
            //Fetching Poll Id
            $queryResult = $this->VoicePoll->execute_query('select poll_id from poll_group where number_of_question='.$number_of_question.' and poll_name="'.$poll_name.'"', 'poll_group');
            $pollId = ($queryResult[0]['poll_group']['poll_id']);

            foreach ($this->request->data['UploadMenu'] as $poll_question) {
                $file = $poll_question['file'];
                print_r($poll_question);
                if ($file['size']){
                    $question = $poll_question['question'];
                    $choice = $poll_question['choice'] == ''? '-':$poll_question['choice'];
                    
                    // FILE UPLOAD
                    // Spliting file name and type
                    $fileSplit = explode(".", $file['name']);

                    //Renaming File name
                    $file['fileName'] = "question_".$question_counter;

                    //Insert poll_question entry
                    $this->VoicePoll->execute_query('insert into poll_question(poll_id, question_id, question_text, available_choice, question_file_location) values('.$pollId.','.$question_counter.',"'.$question.'","'.$choice.'","/opt/freedomfone/gui/app/webroot/upload/voice_polling/'.$pollId.'/question_'.$question_counter.'.'.$fileSplit[1].'")', 'poll_question');

                    //Uploading File to updated location
                    $fileOK = $this->uploadFiles('upload/voice_polling', array($file) , $pollId, 'audio', true, true);
                    //$folder, $data, $itemId = null, $filetype, $useKey, $overWrite
                
                    if(array_key_exists('urls', $fileOK)){
                      $this->Session->setFlash(__('The file was successfully uploaded.',true),'success');       
                    }
                    
                        
                   }else if ($file['error']==1 && !$file['size']) {
                        $this->Session->setFlash(__('The file %s could not be uploaded due to file size restrictions',$file['name']), 'error', array(), $key);
                        $this->Session->setFlash(__("Error uploading file for question number ".$question_counter,true),'success');
                   }
                    
                $question_counter++;
            }

            // record poll_rule only if message and recipient exist
            if($message && $recipient){
                //accumulate poll_rule
                $rule_trugger_array = array();
                $action_taken_array = array();

                $rule_trigger_exist = false;
                foreach ($this->request->data['UploadMenu'] as $poll_question){
                  if($poll_question['action_key'] != '') {
                    array_push($rule_trugger_array, $poll_question['action_key']);
                    $rule_trigger_exist = true;
                  } else {
                    array_push($rule_trugger_array, '-');
                  }
                  $question_counter++;
                }

                if($rule_trigger_exist) {
                  // insert poll_rule entry
                  $action_recipient = 
                  $rule_trigger = implode('|', $rule_trugger_array);
                  $action_taken = json_encode(array(
                                                    'sms' => array('recipient' => $recipient,   // send sms to that person with a message
                                                                  'message' => $message
                                                              )
                                            ));
                  print_r($rule_trigger);
                  print_r($action_taken);
                  $this->VoicePoll->execute_query('insert into poll_rule(poll_id, rule_trigger, action_taken) values('.$pollId.',\''.$rule_trigger.'\',\''.$action_taken.'\')', 'poll_question');
                }

            }

        } else {
            $this->Session->setFlash(__('Please fill the information.',true),'error');
        }

        $this->redirect(array('action' => '/add'));

    }

   function add(){

      $this->set('title_for_layout', __('Voice Poll',true));
      
      $this->render(); 

    }

    function sendvoicepoll(){
        $this->set('title_for_layout', __('Send Voice Poll',true));
        
        //Set Recipient Contact List
        $this->loadModel('PhoneDirectory');
        $recipientData = $this->PhoneDirectory->execute_query('select * from phone_directory', 'phone_directory');

        //get unique description
        $recipient_description = array();
        foreach($recipientData as $recipient){;
          $recipient_description[$recipient['phone_directory']['recipient_description']] = $recipient['phone_directory']['recipient_description'];
        }

        $recipient_description = array_unique($recipient_description);


        //Set Recipient Contact List
        foreach($recipientData as $key => $val){
          $recipientPhoneList[$val['phone_directory']['phone_no']] = $val['phone_directory']['recipient_name'].' '.$val['phone_directory']['recipient_description']. ' '.$val['phone_directory']['phone_no'];
        }
        $this->set('recipientopt',$recipientPhoneList);

        //Set Voice Poll List
        $voicepollData = $this->VoicePoll->execute_query('select * from poll_group', 'poll_group');
        foreach($voicepollData as $key => $val){
          $voicePollList[]= $val['poll_group']['poll_name'];
        }
        $this->set('recordingopt',$voicePollList);
        $this->set('recipient_description',$recipient_description);

        $this->render(); 
        //$this->add(); 
        //$this->redirect(array('action' => '/'));
    }

    function sendvoicepollnow(){

      $this->set('title_for_layout', __('Sending Voice Poll',true));

      if(isset($this->request->data['VoicePollMenu']['voicepoll']) AND $this->request->data['VoicePollMenu']['voicepoll'] != '' AND $this->request->data['VoicePollMenu']['recipient']){
       
        
        //print_r($this->request->data['VoicePollMenu']['recipient']);
        /*
        foreach($this->request->data['VoicePollMenu']['recipient'] as $key1 => $val1){
          foreach($recipientData as $key2 => $val2){        
            if($val1 == $key2){
              $selectedRecipient[] = $val2['phone_directory']['phone_no'];

            }
          }
        }*/

        $numberlist = implode($this->request->data['VoicePollMenu']['recipient'], '|');

        //Fetch Voice Poll
        $voicepollData = $this->VoicePoll->execute_query('select * from poll_group', 'poll_group');
        foreach($voicepollData as $key => $val){        
            if($key == $this->request->data['VoicePollMenu']['voicepoll']){
              $selectedVoicePoll= $val;
            }
          }
        
        //Fetch Voice Poll Questions
        $selectedVoicePollQuestion = array();
        $voicepollquestionData = $this->VoicePoll->execute_query('select * from poll_question where poll_id='.$selectedVoicePoll['poll_group']['poll_id'], 'poll_question');
        
        foreach ($voicepollquestionData as $key => $value) {
          array_push($selectedVoicePollQuestion, $value['poll_question']['available_choice']);
        }
        //print_r($voicepollquestionData);
        $questionlist = implode($selectedVoicePollQuestion, '|');
        
        
        //Sending Voice Poll  
        $output = shell_exec('fs_cli -x "jsrun /home/sharicus/Desktop/outbound_call/scripts/voice_polling/call_dispatcher.js 100|'.$selectedVoicePoll['poll_group']['poll_id'].'|'.$selectedVoicePoll['poll_group']['number_of_question'].' '.$numberlist.' '.$questionlist.'"');
        print_r('fs_cli -x "jsrun /home/sharicus/Desktop/outbound_call/scripts/voice_polling/call_dispatcher.js 100|'.$selectedVoicePoll['poll_group']['poll_id'].'|'.$selectedVoicePoll['poll_group']['number_of_question'].' '.$numberlist.' '.$questionlist.'"');
 
        $this->Session->setFlash(__('Alert sent successfully.',true),'success');
      }elseif($this->request->data['VoicePollMenu']['recipient']){
        $this->Session->setFlash(__('Please select a recording',true),'error');
      }else{
        $this->Session->setFlash(__('Please select a recipient',true),'error');
      }
      $this->Session->setFlash(__('Poll sent successfully.',true),'error');
      $this->redirect(array('action' => '/sendvoicepoll'));

    }

    function readvoicepoll(){
        $this->set('title_for_layout', __('Set Scheduled Alert',true));
        $this->render(); 
    }

    function editvoicepoll ($id){

        $this->set('title_for_layout', __('Delete Poll',true));

           $title = $this->Poll->getTitle($id);
           if($this->Poll->delete($id,true))
       {
           $this->Session->setFlash(__('The following poll has been deleted',true).': '.$title, 'success', array());
             $this->log('[INFO] POLL DELETED, Id: '.$id.', Title: '.$title, 'poll');           
       $this->redirect(array('action' => '/index'));


       }



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
