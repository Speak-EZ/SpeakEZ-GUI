<?php
/****************************************************************************
 * nodes_controller.php		- Controller for nodes (aka Menu options). Used in IVR's (voice menus).
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

class NodesController extends AppController{

      var $name = 'Nodes';
      var $helpers = array('Html', 'Session','Form','Formatting','Flash');
      var $paginate = array('page' => 1, 'limit' => 10, 'order' => array( 'Node.created' => 'desc')); 
    
     	

      function index(){


        $this->set('title_for_layout', __('Content',true)); 
	$this->Node->recursive = 0; 

        if(isset($this->params['named']['limit'])) { 
	     $this->Session->write('nodes_limit',$this->params['named']['limit']);
	}
	elseif($this->Session->check('nodes_limit')) { 
			$this->paginate['limit'] = $this->Session->read('nodes_limit');
	}	


        $this->set('nodes',$this->Node->find('all',array('order'=>'Node.created ASC')));
        $this->set('nodes',$this->paginate());
     }



   function add(){

        $this->set('title_for_layout', __('Upload content',true));

   	$ivr_settings = Configure::read('IVR_SETTINGS');
	$path = $ivr_settings['path'].$ivr_settings['dir_node'];


	// Form data exist, save and redirect to Index
	if (!empty($this->request->data['Node'])) {


	   //Fetch form data
	   $files = array();
	   $files[0] = $this->request->data['Node']['file'];
           $title = $this->request->data['Node']['title'];
	   
	   //If title exists, upload file (wav)

   	   $this->Node->set( $this->request->data );
 
  	   if ($this->Node->validates()){

              if ($files[0]['error']==1 && !$files[0]['size']) {
                       $this->Session->setFlash(__('File upload failure (filesize exceeds maximum)',true).' : '.$files[0]['name'], 'error');                           
	       }  elseif ($files[0]['size']){


               	    $fileOK = $this->uploadFiles($path, $files ,false,'audio',false,false);


	            //File upload OK
		    if(array_key_exists('urls', $fileOK)){

		      //Set db fields
		      $filename = $this->getFilename($fileOK['files'][0]);
	              $this->request->data['Node']['file']        = $filename;
		      
		      $duration = $this->wavDuration('node',$filename,'wav');
		      $this->request->data['Node']['duration'] = $this->wavDuration('node',$filename,'wav');

		      //Save node in db
		      $this->Node->save($this->request->data, array('validate' => false));
		
		      //Log new node
		      $this->log('Msg: NEW NODE AUDIO FILE; File: '.$fileOK['files'][0], 'ivr');
		      
		      //Flash message and redirect	
		      $this->Session->setFlash(__('The menu option has been created.', true),'success');
		      $this->redirect(array('action'=>'index'));

		      }

		      //File upload NOT OK
		      elseif(array_key_exists('errors', $fileOK)) {
		
			//Flash messsage, log error
		      	$this->Session->setFlash($fileOK['errors'][0], 'error');
		      } else {

		     $this->log("Msg: ERROR; Action: file upload; Type: no file; Code: ".$fileOK['errors'][0],"ivr");
   	         
	              }

		 }
	    } 


	}

$this->render();    
			
}


    function delete ($id){


    	     $ivr_settings = Configure::read('IVR_SETTINGS');
      	     $path = '/'.$ivr_settings['path'].$ivr_settings['dir_node'];

	     if(!$this->isActive($id,'node')){
	     
		$this->request->data = $this->Node->findById($id);
  	     	$this->Node->deleteAudio($this->request->data['Node']['file'],$path,array('mp3','wav'));

    	     	if($this->Node->delete($id,true)){

			$this->Session->setFlash(__('The selected voice menu node has been deleted.',true),'success');
	     		$this->log('Msg: Node deleted, ID: '.$id, 'node');	
	     		$this->redirect(array('action' => '/index'));
	     		}
	     }

	     else {
		$this->Session->setFlash(__('The selected voice menu node could not be deleted as it is present in one or more Voice menus.',true),'warning');
		$this->redirect(array('action' => '/index'));
	     }

    }



   function edit($id=null){

        $this->set('title_for_layout', __('Edit content',true));
   	$ivr_settings = Configure::read('IVR_SETTINGS');
	$path = $ivr_settings['path'].$ivr_settings['dir_node'];
        $_titleOK = $_fileOK = true;


	// Non-existing id, or empty form
	  if (!$id && empty($this->request->data)){
	     $this->Session->setFlash(__('Invalid audio file', true)); 
	     $this->redirect(array('action'=>'index')); 
	  }
          
          // Retrieve data from database and display 
    	  elseif(empty($this->request->data['Node'])){


		$this->Node->id = $id;
		$this->request->data = $this->Node->read(null,$id);

          }
          
          //Fetch form data 
	  else {



          $this->Node->set( $this->request->data );	       



          //If title is ok, save
          if ($this->Node->validates(array('fieldList' => array('title')))){

            $this->Node->save($this->request->data,array('fieldList'=>array('title','modified'),'validate'=>true));
            
          } else {

              $this->Session->setFlash('Title is not correct (unique and min 3 characters)','error');
              $_titleOK = false;
          }


          //Fetch file and attempt to upload

	       $files = array(); 
	       $files[0] = $this->request->data['Node']['file'];

              if ($files[0]['error']==1 && !$files[0]['size']) {
                       $this->Session->setFlash(__('File upload failure (filesize exceeds maximum)',true).' : '.$files[0]['name'], 'error');                           
	       }  elseif ($files[0]['size']){

               //Attempt to upload file
	       $fileOK = $this->uploadFiles($path, $files ,false,'audio', false, false);

                       //If file upload OK
		       	if(array_key_exists('urls', $fileOK)) {

                                //Set file info
				$filename = $this->getFilename($fileOK['files'][0]);
				$this->request->data['Node']['file'] = $filename;        

	  			$duration = $this->wavDuration('node',$filename,'wav');
	  			$this->request->data['Node']['duration'] = $this->wavDuration('node',$filename,'wav');


				$this->log('Msg: NEW NODE AUDIO FILE; File: '.$fileOK['files'][0], 'ivr');	

                                //Save file data to db
                                $this->Node->save($this->request->data,array('fieldList'=>array('file','modified','duration'),'validate'=>false));


                                //Delete old audio file
				$this->Node->deleteAudio($this->request->data['Node']['file_old'],$path,array('mp3','wav'));
				
     				$this->Session->setFlash(__('Success',true).' : '.$fileOK['original'][0], 'success');							
  			
			 }

			elseif(array_key_exists('errors', $fileOK)) {
                                 $_fileOK= false;
				$this->log('Msg: NODE UPLOAD ERROR; Type: '.$fileOK['errors'][0], 'ivr');
				$this->Session->setFlash($fileOK['errors'][0],'error');
			 }
                } // if file is selected

          if($_titleOK && $_fileOK) {  
	  	       $this->Session->setFlash(__('Your changes have been saved.',true), 'success');							
	  	       $this->redirect(array('action'=>'index')); 
		      }  else {
               	       $this->Node->id = $id;
		       $this->request->data = $this->Node->read(null,$id);

                }

          } //fetch form data

          
}


  function download ($id) {

    	Configure::write('debug', 0);

	$this->Node->id = $id;
	$data = $this->Node->read();
	

	$file = $data['Node']['file'].'.mp3';
	$name = $data['Node']['title'];
	$path  = 'webroot/freedomfone/ivr/nodes';



	$this->response->file($path . DS . $file, array(
		'download' => true, 
		'name' => $name,
		));

	return $this->response;



    }


}

?>
