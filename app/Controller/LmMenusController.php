<?php
/****************************************************************************
* lm_menus_controller.php  - Controller for Leave-a-message IVR menu.
* version  		   - 3.0.1500
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

class LmMenusController extends AppController{

      var $name = 'LmMenus';

      var $layout = 'jquery';    	  

   function index(){

    $this->set('title_for_layout', __('Leave-a-Message IVR Menus',true));


   //Avoid fetching associated data
   $this->LmMenu->recursive = -1;
   
   //Fetch all data from model LmMenu, apply pagination
   $this->set('lm_menus', $this->paginate());
   
   }


   function create() {


        $this->set('title_for_layout', __('Create Leave-a-Message',true));
                 $lm_settings = Configure::read('LM_SETTINGS');
                 $fileData = array();


                 //Show form
                 if  ($this->request->data['submit']) {

                     $entry = $this->LmMenu->nextInstance();

                     if(!$entry['id']){
                        $this->Session->setFlash(__('There is no idle instance for a new Leave-a-message IVR menu. Please delete an existing menu, and try again. Maximum menus: 20.',true), 'warning');
                        $this->redirect(array('action' =>'/'));
                     } else {
 
                        $this->redirect(array('action' =>'/add/'.$entry['id']));
                     
                     }
                  } 
         }





    function add($id) {


        $this->set('title_for_layout',  __('Create Leave-a-Message',true));
         $lm_settings = Configure::read('LM_SETTINGS');



         $fileData = array();

         //Incorrect id -> redirect to index
         if(!$id){
               $this->redirect(array('action' =>'/'));
 
        }  elseif  ($id && !array_key_exists('LmMenu', $this->request->data)) { 
           //Id OK, no form data -> display data
                   
           $this->set(compact($lm_settings));
           $this->LmMenu->unbindModel(array('hasMany' => array('Mapping')));   
           $this->request->data = $this->LmMenu->findById($id);

        }  else {
         //Id OK, form data OK -> Save and redirect to Index

            if($this->request->data['LmMenu']['title']){

              foreach($this->request->data['LmMenuFile'] as $key => $file){

                   if ($file['size']){
                      $file['fileName']=$key;
                      $fileData[] = $file;
                   } elseif ($file['error']==1 && !$file['size']) {

		      $this->Session->setFlash(__('The file %s could not be uploaded due to file size restrictions',$file['name']), 'error', array(), $key);

                   }
              }

              //Upload one ore more wav files
              $fileOK = $this->uploadFiles($lm_settings['path'].$this->request->data['LmMenu']['instance_id']."/".$lm_settings['dir_menu'], $fileData ,false,'audio',true,true);
              $this->LmMenu->id = $this->request->data['LmMenu']['id'];

              if ($this->LmMenu->save($this->request->data['LmMenu'])) {


                 if(array_key_exists('urls', $fileOK)) {

                      foreach ($fileOK['urls'] as $key =>  $url ){
                              $this->log("Msg: NEW MENU AUDIO FILE; File: ".$url, "leave_message");
                              $this->Session->setFlash(__('The file %s was successfully uploaded.', $fileOK['original'][$key]), 'success', array(), $key);


                      }
                 }

                 if(array_key_exists('errors', $fileOK)) {

                      foreach ($fileOK['errors'] as $key => $error ){
                              $this->log("Msg: UPLOAD  ERROR, Error: ".$error, 'leave_message');
                              $this->Session->setFlash($error, 'error', array(), $key);
                       }
                 }
              } else {
                     $this->Session->setFlash(__('Your data could not be saved. Please, try again.',true),'error');
              }

              $this->redirect(array('action' => '/'));
         


             } else {
                     $this->Session->setFlash(__('Please enter a title for the menu.',true),'error');

             } 
   }

 }

   function edit($id) {


                $this->set('title_for_layout', __('Edit Leave-a-Message',true));
                 $lm_settings = Configure::read('LM_SETTINGS');
                 $fileData = array();

                 //Incorrect id -> redirect to index
                 if(!$id){
                          
                        $this->redirect(array('action' =>'/'));
                 } 

                 //Id OK, no form data -> display data
                 elseif  ($id && !array_key_exists('LmMenu', $this->request->data)) {

                        $this->set(compact($lm_settings));       
                        $this->LmMenu->unbindModel(array('hasMany' => array('Mapping')));   
                        $this->request->data = $this->LmMenu->findById($id);

                 }  else {


                   //Verify that title is set
                   if($this->request->data['LmMenu']['title']){
		
                         //Id OK, form data OK -> Save and redirect to Index
                         foreach($this->request->data['LmMenuFile'] as $key => $file){


                                                    
                             if ($file['size']){
                                 $file['fileName']=$key;
                                 $fileData[] = $file;
                             } elseif ($file['error']==1 && !$file['size']) {
                                 $this->Session->setFlash(__('The file %s could not be uploaded due to file size restrictions',$file['name']), 'error', array(), $key);
                             }
                          }

                          //Upload one ore more wav files
                          $fileOK = $this->uploadFiles($lm_settings['path'].$this->request->data['LmMenu']['instance_id']."/".$lm_settings['dir_menu'], $fileData ,false,'audio',true,true);
                          $this->LmMenu->id = $id;

                          if ($this->LmMenu->save($this->request->data['LmMenu'])) {


                                 if(array_key_exists('urls', $fileOK)) {

                                        foreach ($fileOK['urls'] as $key =>  $url ){
                                            $this->log("Msg: NEW MENU AUDIO FILE; File: ".$url, "leave_message");
                              		    $this->Session->setFlash(__('The file %s was successfully uploaded.', $fileOK['original'][$key]), 'success', array(), $key);
                                        }
                                  }

                                 if(array_key_exists('errors', $fileOK)) {

                                        foreach ($fileOK['errors'] as $key => $error ){
                                            $this->log("Msg: UPLOAD  ERROR, Error: ".$error, 'leave_message');
                                            $this->Session->setFlash($error, 'error',array(), $key);
                                        }
                                 }
                                                            
                         }  else {
                                $this->Session->setFlash(__('Your data could not be saved. Please, try again.',true),'warning');
                         }
                        $this->redirect(array('action' => '/'));
                        } else {

                          $this->Session->setFlash(__('Please enter a title for the menu.',true),'error');


                        }

                 } //else

   }



  function download ($id,$message) {

  Configure::write('debug', 0);

  $this->LmMenu->id = $id;

  $this->LmMenu->unbindModel(array('hasMany' => array('Mapping')));   
  $data = $this->LmMenu->read();
  
  $file = 'lm'.$message.'.mp3';
  $name = 'lm'.$message;
  $path  = 'webroot/freedomfone/leave_message/'.$data['LmMenu']['instance_id'].'/audio_menu';


	$this->response->file($path . DS . $file, array(
		'download' => true, 
		'name' => $name,
		));

		return $this->response;

} 


    function delete ($id){

         
         if($id){

             $lm_settings = Configure::read('LM_SETTINGS');
             $instance_id = $this->LmMenu->getInstanceID($id);
             $dirMenu = WWW_ROOT.$lm_settings['path'].$instance_id.'/'.$lm_settings['dir_menu'];
             $dirMsg = WWW_ROOT.$lm_settings['path'].$instance_id.'/'.$lm_settings['dir_messages'];


             //LAM is not active -> delete
             if (!$this->isActive($id, 'lam')){


                //Delete action OK -> success flash
                if ($result = $this->LmMenu->delete($id)){

                   $this->Session->setFlash(__('The selected menu has been deleted.',true),'success');

                   //Empty dir OK 
                   if ($result = $this->LmMenu->emptyDirMulti(array($dirMenu,$dirMsg))){

                     //Restore of config file OK

                     if(! $this->LmMenu->restoreConf($instance_id)){
                          
                         $this->Session->setFlash(__('The default conf file could not be restored. Please check file permissions.',true),'warning');

                     }

                   }

                 } else {
                //Delete/unlink action failed -> error flash
                
                        $this->Session->setFlash(__('No entry with this id exist. Please try again.',true),'error');

                }

             //LAM is active -> warning flash
             } else {

                $this->Session->setFlash(__('The selected menu could not be deleted since it is a member of another IVR.',true),'warning');
            
             }

           }
   
           $this->redirect(array('action' => 'index'));
      }

      function export($id){


               Configure::write('debug', 0);
    	       $this->layout = null;
    	       $this->autoLayout = false;
    	       $this->render();   
	
      }

      function advanced_edit($id){

              $lm_settings = Configure::read('LM_SETTINGS');
              $this->set(compact($lm_settings));       
              $this->LmMenu->unbindModel(array('hasMany' => array('Mapping')));   
              $this->request->data = $this->LmMenu->findById($id);

              $this->layout = "ajax";
      }

      function advanced_add(){

              $this->layout = "ajax";
      
      }


}
?>