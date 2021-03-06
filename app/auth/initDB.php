   function initDB() {
    $group =& $this->User->Group;

    //Allow admins to everything
    $group->id = 1;     
    $this->Acl->allow($group, 'controllers');


 
    //allow users to read but not write
    $group->id = 2;
    $this->Acl->deny($group, 'controllers');

    //Polls
    $this->Acl->deny($group, 'controllers/Polls');
    $this->Acl->allow($group, 'controllers/Polls/index');
    $this->Acl->allow($group, 'controllers/Polls/view');
    $this->Acl->allow($group, 'controllers/Polls/refresh');


    //Leave a message
    $this->Acl->deny($group, 'controllers/Messages');
    $this->Acl->allow($group, 'controllers/Messages/index');
    $this->Acl->allow($group, 'controllers/Messages/disp');
    $this->Acl->allow($group, 'controllers/Messages/archive');
    $this->Acl->allow($group, 'controllers/Messages/edit');
    $this->Acl->allow($group, 'controllers/Messages/view');
    $this->Acl->allow($group, 'controllers/Messages/refresh');


    //Categories
    $this->Acl->deny($group, 'controllers/Categories');
    $this->Acl->allow($group, 'controllers/Categories/index');

    //Tags
    $this->Acl->deny($group, 'controllers/Tags');
    $this->Acl->allow($group, 'controllers/Tags/index');

    //CreateAlert
    $this->Acl->deny($group, 'controllers/CreateAlert');
    $this->Acl->allow($group, 'controllers/CreateAlert/index');

    //VoicePoll
    $this->Acl->deny($group, 'controllers/VoicePoll');
    $this->Acl->allow($group, 'controllers/VoicePoll/index');
    $this->Acl->allow($group, 'controllers/VoicePoll/add');
    $this->Acl->allow($group, 'controllers/VoicePoll/view');

    //Leave-a-message
    $this->Acl->deny($group, 'controllers/LmMenus');
    $this->Acl->allow($group, 'controllers/LmMenus/index');

    //Incoming SMS
    $this->Acl->deny($group, 'controllers/Bin');
    $this->Acl->allow($group, 'controllers/Bin/index');
    $this->Acl->allow($group, 'controllers/Bin/refresh');
    $this->Acl->allow($group, 'controllers/Bin/delete');
    $this->Acl->allow($group, 'controllers/Bin/disp');

    //Outgoing SMS
    $this->Acl->deny($group, 'controllers/Batches');
    $this->Acl->allow($group, 'controllers/Batches/index');
    $this->Acl->allow($group, 'controllers/Batches/disp');
    $this->Acl->allow($group, 'controllers/Batches/view');
    $this->Acl->allow($group, 'controllers/Batches/update');


    //SMS gateways
    $this->Acl->deny($group, 'controllers/SmsGateways');
    $this->Acl->allow($group, 'controllers/SmsGateways/index');



    //Language Selectors and Voice menus
    $this->Acl->deny($group, 'controllers/IvrMenus');
    $this->Acl->allow($group, 'controllers/IvrMenus/index');
    $this->Acl->allow($group, 'controllers/IvrMenus/selectors');

    //Content
    $this->Acl->deny($group, 'controllers/Nodes');
    $this->Acl->allow($group, 'controllers/Nodes/index');

    //Users
    $this->Acl->deny($group, 'controllers/Users');
    $this->Acl->allow($group, 'controllers/Users/index');
    $this->Acl->allow($group, 'controllers/Users/view');

    //Phone books
    $this->Acl->deny($group, 'controllers/PhoneBooks');
    $this->Acl->allow($group, 'controllers/PhoneBooks/index');

    //System data (CDR)
    $this->Acl->deny($group, 'controllers/Cdr');
    $this->Acl->allow($group, 'controllers/Cdr/index');
    $this->Acl->allow($group, 'controllers/Cdr/statistics');
    $this->Acl->allow($group, 'controllers/Cdr/general');
    $this->Acl->allow($group, 'controllers/Cdr/overview');
    $this->Acl->allow($group, 'controllers/Cdr/refresh');


    //Monitor IVR
    $this->Acl->deny($group, 'controllers/MonitorIvr');
    $this->Acl->allow($group, 'controllers/MonitorIvr/index');
    $this->Acl->allow($group, 'controllers/MonitorIvr/refresh');

    
    //Dashboard
    $this->Acl->deny($group, 'controllers/Processes');
    $this->Acl->allow($group, 'controllers/Processes/index');
    $this->Acl->allow($group, 'controllers/Processes/refresh');
    $this->Acl->allow($group, 'controllers/Processes/system');
    $this->Acl->allow($group, 'controllers/Processes/refresh');
    
    //Settings
    $this->Acl->deny($group, 'controllers/Settings');
    $this->Acl->allow($group, 'controllers/Settings/index');

    //GSM channels
    $this->Acl->deny($group, 'controllers/Channels');
    $this->Acl->allow($group, 'controllers/Channels/index');
    $this->Acl->allow($group, 'controllers/Channels/refresh');
    $this->Acl->allow($group, 'controllers/Channels/audio_services');

    $this->Acl->deny($group, 'controllers/OfficeRoute');
    $this->Acl->allow($group, 'controllers/OfficeRoute/refresh');

    //Logs
    $this->Acl->deny($group, 'controllers/Logs');


    //Authentication
    $this->Acl->deny($group, 'controllers/Users');
    $this->Acl->allow($group, 'controllers/Users/logout');
    $this->Acl->deny($group, 'controllers/Groups');


    echo "all done";
    exit;
  }
