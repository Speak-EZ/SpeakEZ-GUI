<?php
/****************************************************************************
 * messages_controller.php	- Controller for Leave-a-message messages. Manages CRUD operations on messages.
 * version 		 	- 2.5.1350
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

class MessagesController extends AppController{

      var $name = 'Messages';
      var $helpers = array('Flash','Formatting','Session');      
      var $paginate = array('page' => 1, 'limit' => 10, 'order' => array( 'Message.created' => 'desc')); 
      var $layout = 'jquery';


      function refresh($method = null){

          $this->autoRender = false;
          $this->logRefresh('message',$method); 
          $this->Message->refresh();

          $this->loadModel('Cdr');
          $this->Cdr->refresh();
          $this->logRefresh('cdr',$method); 
      
      }


      function index(){

         $this->pageTitle = __('Leave-a-Message Inbox',true);

      	 $this->Session->write('Message.source', 'index');

         $this->Session->write('messages_tag',false);
         $this->Session->write('messages_category',false);
         $this->Session->write('messages_rate',false);
         $this->Session->write('messages_service',false);
         $this->Session->write('messages_order',false);
         $this->Session->write('messages_dir',false);
         $this->Session->write('messages_limit',false);
         $this->Session->write('messages_page',false);

        $this->loadModel('LmMenu');
        $this->LmMenu->recursive = -1;
 
	 $this->Message->recursive = 0; 
   	 $messages = $this->paginate('Message', array('Message.status' => '1'));
     
     // lookup caller id (phone_directory) and title (hardcode to specific LAM ID) - Ping January 2016
     $this->loadModel('PhoneDirectory');
     foreach ($messages as $key => $message) {
      $sender_number = '+'.ltrim($message['Message']['sender'], '0');
      $registered_user_lookup = $this->PhoneDirectory->execute_query('select recipient_name from phone_directory where phone_no ="'.$sender_number.'"', 'phone_directory');
      
      if(count($registered_user_lookup) == 1) {
        $messages[$key]['Message']['sender_name'] = $registered_user_lookup[0]['phone_directory']['recipient_name'];
      } else {
        $messages[$key]['Message']['sender_name'] = '';
      }

      
      $lm_menu = $this->LmMenu->find('first', 
                                            array('conditions' => 
                                                                array('instance_id' => $message['Message']['instance_id'])
                                      ));
      
      $lm_title = $lm_menu['LmMenu']['title'];

      //rename title to lm category title
      if($message['Message']['title'] == 'No title') {
        $messages[$key]['Message']['title'] = $lm_title;
        
      } 

      

    }

    	 $tags 	    = $this->Message->Tag->find('list');
     	 $categories = $this->Message->Category->find('list');
         $instances = $this->Message->find('list', array('fields' => array('Message.instance_id')));

         //get lm menu titles
         $lm_menu_all = $this->LmMenu->find('all', array('limit' => 100));
        $lm_titles = array();

        foreach($lm_menu_all as $key => $lm_menu){  
          $lm_titles[$lm_menu['LmMenu']['instance_id']] = $lm_menu['LmMenu']['title'];
        }

 	 $this->set(compact('tags','categories','instances','messages', 'lm_titles'));


      }


      function disp($page = null){


      $this->Message->recursive = 1; 
      $tag = $category = $rate = $instance_id = $dir = $limit = $id = false;
      $param = $conditions = $order = array();
      if(isset($this->request->data['Message'])) {
        $data = $this->request->data['Message'];
      } else {
        // get these values from session (or default) instead
        $data = array();
        $data['source'] = 'index' ;
        $data['tag'] = '';
        $data['category'] = '';
        $data['rate'] = '';
        $data['service'] = '';
        $data['order'] = 'Message.created' ;
        $data['dir'] = '';
        $data['limit'] = '';
      }
      $no_match = false;

      $this->Session->write('Message.source', $data['source']);
      //$this->Session->write('Message.tag', $data['tag']);
      $this->Session->write('Message.category', $data['category']);
      //$this->Session->write('Message.rate', $data['rate']);
      $this->Session->write('Message.service', $data['service']);
      $this->Session->write('Message.order', $data['order']);
      $this->Session->write('Message.dir', $data['dir']);
      $this->Session->write('Message.limit', $data['limit']);


      /*
      if($data['tag']){
        echo 'tag exist';
        $tags = $this->Message->Tag->findById($data['tag']);

           foreach ($tags['Message'] as $key => $message){
                $message_id[] = $message['id'];
           }
           if(isset($message_id)){
           $conditions['Message.id'] = $message_id;
           } else {
           $no_match = true;
           }


      }
      */


      if($data['category']){
         $conditions['Category.id'] = $data['category'];
         $this->Session->write('messages_category',$data['category']);
      } else {
         unset($conditions['Category.id']);

      }

      /*
      if($data['rate']){

         $conditions['Message.rate'] = $data['rate'];
         $this->Session->write('messages_rate',$data['rate']);

         } else {

         unset($conditions['Message.rate']);

         }
        */

      if($data['service']){

         $conditions['Message.instance_id'] = $data['service'];
         $this->Session->write('messages_service',$data['service']);

      } else {

         unset($conditions['Message.instance_id']);

      }


      if($data['dir']){
         $dir = $data['dir'];
         $this->Session->write('messages_dir',$dir);
      } elseif (!$dir = $this->Session->read('messages_dir')){

        $dir = false;

      }
 
     if($data['order']){
          if($data['order'] == 'Message.new'){ $dir = 'DESC';}
         $order = $data['order'].' '.$dir;
         $this->Session->write('messages_order',$order);
      } elseif (!$order = $this->Session->read('messages_order')){

        $order = false;
      }


      if($data['limit']){
         $limit = $data['limit'];
        $this->Session->write('messages_limit',$limit);
     
      } elseif (!$limit = $this->Session->read('messages_limit')) {
        $limit = 10;
      }

      if(!$page){
        $page = 1;
      } 



         $this->refreshAll();
         $this->Session->write('Message.source', 'index');

         $conditions['Message.status'] = 1;
         if(!$no_match){      
             $this->paginate = array('conditions' => $conditions, 'order' => $order,'limit' => $limit, 'page' => $page);
   	     $data = $this->paginate('Message');

         // lookup caller id (phone_directory) and title (hardcode to specific LAM ID) - Ping January 2016
         $this->loadModel('PhoneDirectory');
         foreach ($data as $key => $message) {
          $sender_number = '+'.ltrim($message['Message']['sender'], '0');
          $registered_user_lookup = $this->PhoneDirectory->execute_query('select recipient_name from phone_directory where phone_no ="'.$sender_number.'"', 'phone_directory');
          
          if(count($registered_user_lookup) == 1) {
            $data[$key]['Message']['sender_name'] = $registered_user_lookup[0]['phone_directory']['recipient_name'];
          } else {
            $data[$key]['Message']['sender_name'] = '';
          }

          $this->loadModel('LmMenu');
          $this->LmMenu->recursive = -1;
          $lm_menu = $this->LmMenu->find('first', 
                                                array('conditions' => 
                                                                    array('instance_id' => $message['Message']['instance_id'])
                                          ));
          
          $lm_title = $lm_menu['LmMenu']['title']; 

          //rename title to lm category title
          if($message['Message']['title'] == 'No title') {
            $data[$key]['Message']['title'] = $lm_title;
            
          } 


         }
         //print_r($data);

	     $this->set('messages',$data);  

             foreach ($data as $key => $message){
                $id[] = $message['Message']['id'];
             }
         } else {

           $this->set('messages',false);  
         }
         $this->Session->write('messages_selected', $id);


      }



      function archive(){

         $this->refreshAll();
 
         $this->pageTitle = __('Leave-a-Message Archive',true);
         $this->Session->write('Message.source', 'archive');
     
         if(isset($this->params['named']['sort'])) { 
      		$this->Session->write('messages_sort',array($this->params['named']['sort']=>$this->params['named']['direction']));
	 } elseif($this->Session->check('messages_sort')) { 
		if(in_array($this->Session->read('messags_sort'),array('new','title','rate','category','created','modified','length'))){
		   $this->paginate['order'] = $this->Session->read('messages_sort');
		} 
	 } 

         if(isset($this->params['named']['limit'])) { 
	    $this->Session->write('messages_limit',$this->params['named']['limit']);
	 } elseif($this->Session->check('messages_limit')) { 
	   $this->paginate['limit'] = $this->Session->read('messages_limit');
	 }	

         $conditions['Message.status'] = 0;
         $this->paginate = array('conditions' => $conditions);
   	 $data = $this->paginate('Message');
	 $this->set('messages',$data);

        }


     function view($id){


      $this->Message->id = $id;
      $this->set('data',$this->Message->read());       

      }


    function edit($id = null)    {  

            $this->pageTitle = __('Edit Leave-a-Message',true);

	     if(!$id){
		     $this->redirect(array('action' =>'/'));

	     } elseif(empty($this->request->data['Message'])){

		$this->referer();

		$this->Message->id = $id;
		$this->request->data = $this->Message->read();
		$this->set('data',$this->Message->read());       


      		if($this->Session->check('messages_sort')){
			$data = $this->Session->read('messages_sort');
			$keys = array_keys($data);
			$field = $keys[0];
			$dir = $data[$field];
		} else {
			$field = 'id';
			$dir = 'asc';
		}


      		$this->Session->write('Message.messages_sort', $dir);
      	  	$neighbors = $this->Message->find('neighbors', array('field' => $field, 'dir' => 'desc','value' => $this->request->data['Message'][$field], 'conditions' => array('status' => $this->request->data['Message']['status'] )));	

		$tags 	    = $this->Message->Tag->find('list');
 		$categories = $this->Message->Category->find('list');
 		$this->set(compact('tags','categories','neighbors'));

		     

		}

	else {

	if($this->Message->saveAll($this->request->data)){

		$this->Message->save($this->request->data['Tag']);
		$this->Session->setFlash(__('The entry has been updated',true),'success');
    	     
		$submit   = $this->params['data']['Submit'];

		if ($submit == __('Save',true) ){		   
                    $redirect = 'edit/'.$id;
                   }
		}

		$this->redirect(array('action' => $redirect));
	}
    }


    function delete ($id){

    	     $source = $this->Session->read('Message.source');


     	     $title = $this->Message->getTitle($id);

    	     if($this->Message->delete($id))
	     {
	     $this->Session->setFlash('Message with title "'.$title.'" has been deleted.');
	     $this->log('Msg: MESSAGE  DELETED; Id:title: '.$id.":".$title, 'leave_message');
	    
	     }


 	     if (!$redirect = $source){
                        $redirect = 'index';
              }
             $this->redirect(array('action' => $redirect));


    }


    function process (){


	if (!$redirect = $this->request->data['Message']['source']){
	   	$redirect = 'index';
	}  


    	//Data to process
    	if(!empty($this->request->data['Message'])){

	    //One or more messages selected
	    if(array_key_exists('message',$this->request->data)){

		$entries = $this->request->data['message'];
    	    	$action = $this->request->data['Submit'];

		//Loop through messages
         $download_files = array();
    	     	foreach ($entries as $key => $id){
	     	     if ($id) {
			   $this->Message->id = $id;
			   if ($action == __('Delete',true)){
		     	       if ($this->Message->delete($id)){
				     $title = $this->Message->getTitle($id);
				     $this->log('Msg: MESSAGE  DELETED; Id:title: '.$id.":".$title, 'leave_message');
			        }
			    } elseif ($action == __('Move to Archive',true)){
				$this->Message->saveField('status',0);
 	      			$this->log('ARCHIVE Message '.$id, 'leave_message');		       
			    }  elseif ($action == __('Activate',true)){
        $this->Message->saveField('status',1);
              $this->log('Msg: MESSAGE ACTIVATED '.$id, 'leave_message');          
          } elseif ($action == __('Download',true)){

				     //prepare compressed file to bundle messages for download - aggregated download
             $data = $this->Message->read();
             //print_r($data);
             //die();
             $real_file        = $data['Message']['file'].'.mp3';
             $this->loadModel('PhoneDirectory');
             $sender_number = '+'.ltrim($data['Message']['sender'], '0');
             $registered_user_lookup = $this->PhoneDirectory->execute_query('select recipient_name from phone_directory where phone_no ="'.$sender_number.'"', 'phone_directory');
              
              if(count($registered_user_lookup) == 1) {
                $sender = $registered_user_lookup[0]['phone_directory']['recipient_name'];
              } else {
                $sender = $sender_number;
              }

             $file        = date("Ymd-H-i-s", $data['Message']['created']).' - from '.$sender.'.mp3';
         
             //$name        = $data['Message']['title'];
             $instance_id = $data['Message']['instance_id'];

             //keep file locations in array
             array_push($download_files, array('filename' => $file, 'filelocation' => '/opt/freedomfone/gui/app/webroot/freedomfone/leave_message/'.$instance_id.'/messages/'.$real_file));
             
			    }
		        }
	             } //foreach

          //create achieve file if action is download
          if ($action == __('Download', true)) {
             //$host = $_SERVER['HTTP_HOST'];
             $download_file = 'test-mp3.zip';
             $download_link = '/download/'.$download_file;

             $zip = new ZipArchive;
             $res = $zip->open('/opt/freedomfone/gui/app/webroot/download/'.$download_file, ZipArchive::OVERWRITE);
              
              if ( $res === TRUE) {
                  foreach($download_files as $file) {
                    $zip->addFile($file['filelocation'], $file['filename']);
                  }
                  $zip->close();
                   echo 'ok';
                   $this->redirect($download_link);

              } else {
                  switch($res){
                      case ZipArchive::ER_EXISTS: 
                          $ErrMsg = "File already exists.";
                          break;

                      case ZipArchive::ER_INCONS: 
                          $ErrMsg = "Zip archive inconsistent.";
                          break;
                          
                      case ZipArchive::ER_MEMORY: 
                          $ErrMsg = "Malloc failure.";
                          break;
                          
                      case ZipArchive::ER_NOENT: 
                          $ErrMsg = "No such file.";
                          break;
                          
                      case ZipArchive::ER_NOZIP: 
                          $ErrMsg = "Not a zip archive.";
                          break;
                          
                      case ZipArchive::ER_OPEN: 
                          $ErrMsg = "Can't open file.";
                          break;
                          
                      case ZipArchive::ER_READ: 
                          $ErrMsg = "Read error.";
                          break;
                          
                      case ZipArchive::ER_SEEK: 
                          $ErrMsg = "Seek error.";
                          break;
                      
                      default: 
                          $ErrMsg = "Unknow (Code $rOpen)";
                          break;
                  }
              }
          }

		     //$this->redirect(array('action' => $redirect));
		 } //array_key_exists 
		 else {
		     
		     $this->redirect(array('action' => $redirect));
		 }

    	     } // data to process

	     else {
		 $this->redirect(array('action' => $redirect));
             }
    }



  function download ($id) {


    	Configure::write('debug', 0);

	$this->Message->id = $id;
	$data = $this->Message->read();
	
	$file        = $data['Message']['file'].'.mp3';
	$name        = $data['Message']['title'];
        $instance_id = $data['Message']['instance_id'];

	$path  = 'webroot/freedomfone/leave_message/'.$instance_id.'/messages';

	$this->response->file($path . DS . $file, array(
		'download' => true, 
		'name' => $name,
		));

	return $this->response;


    }


}
?>
