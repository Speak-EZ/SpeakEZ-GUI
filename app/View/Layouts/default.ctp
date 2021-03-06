<?php
/****************************************************************************
 * home.ctp	- Front page
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
 * Inspired by:
 * A Free Template by joomlajunkie.com
 * @version 1.0
 * @copyright (C) 2005-2006 by - Joomla Junkie
 * @web http://www.joomlajunkie.com
 * 
 ***************************************************************************/

$rand = rand();
//$player = 'AudioPlayer.setup("'.MY_DOMAIN.'/app/webroot/swf/player.swf?randomcount='.$rand.'", { 
$player = 'AudioPlayer.setup("karencis-dev.syr.edu/swf/player.swf?randomcount='.$rand.'", { 
	         width: "200",
		 transparentpagebg: "yes",
		 leftbg: "CCCCCC",  
		 lefticon: "fe911c",
		 righticon: "fe911c",
		 righticonhover: "fe911c"
            });';


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title_for_layout?></title>

    <?php echo $this->Html->charset('UTF-8'); ?>

    <?=$this->Html->css('customise');?>    		<!-- Freedom Fone -->
    <?=$this->Html->css('style');?>	     		<!-- Freedom Fone -->
    <?=$this->Html->css('flash_messages');?>	     	<!-- Flash messages -->
    <?=$this->Html->css('vimeo');?>	     		<!-- Main menu -->
    <?=$this->Html->css('modalbox');?>	        	<!-- Modalbox cluetip -->
    <?=$this->Html->meta('icon');?>





    <?=$this->Html->meta('keywords','SMS, mobile, GMS, callback, development, IVR, poll, FreeSWITCH, GSMOpen, CakePHP, ICT4D, M4D, activism, NGO, civil society, citizen journalism, reporting, journalism, Freedom Fone, Kubatana, voice, voip');?>





    <?php echo $this->Html->script('confirmSubmit'); ?>  <!-- Confirmation of form submit -->


    <?php echo $this->Html->script('modalbox');?>
    <?php echo $this->Html->script('cakemodalbox');?>
    <?php echo $this->Html->script('prototype');?>
    <?php echo $this->Html->script('scriptaculous.js?load=builder,effects');?>



    <?=$this->Html->script('audio-player');?>			  <!-- Audioplayer -->
    <?=$this->Html->scriptBlock($player,array('safe'=>false));?>    <!-- Audioplayer -->


 
</head>

<body>
<div id="wrapper">
	 <div class="header">

    <div style="float:left;height:10px"><img src="https://www.syr.edu/images/logo-with-seal.png"></div>
  
  
  <div style="float: left;">
  <?
  //<img class="logospeak" src="http://faculty.maxwell.syr.edu/ylee41/Syracuse_University.png">
  ?>
  <div style="font-size: 37px; float: right; padding: 25px 221px;color:#fff;">SPEAK EZ</div>
  </div>

         <? if (isset ($authUser)){ echo $this->Html->div('login',__("Logged in as",true).": ".$authUser); } ?> 
        <?php 
          if(isset($authGroup)){
             if ($authGroup) { 
               echo $this->Html->image("icons/logout.png", array("class" => "logout", "alt" => __("Logout",true), "title" => __("Logout",true), "url" => array("controller" => "users", "action" => "logout")));
              }
           } else {

   echo $this->Html->image("icons/login.png", array("class" => "logout", "alt" => __("Login",true), "title" => __("Login",true), "url" => array("controller" => "users", "action" => "login")));
           } ?>
       </div>

	 <div id="top_nav"><?php 

           if(isset($authGroup)){
              if($authGroup == 1){
                            echo $this->element('menuAdmin'); 
              } else {
                             echo $this->element('menuUser');
              } 
           } 


              ?>		<!-- horizonal menu -->

        

           </div>
	 <div id="content_wrap">
        
        <?php
        /*
        <?php echo $this->Html->div('breadcrumb', $this->Html->getCrumbs(' > ', array('text' => __('Home',true), 'url' => array('controller' => 'cdr', 'action' => 'overview'), 'escape' => false))); ?>
        */
        ?>

    <?php echo $this->Html->div('breadcrumb', $this->Html->getCrumbs(' > ', array('text' => __('Home',true)))); ?>


		<div id="main_content">
                <?php echo $this->Session->flash();?>
                <?php echo $this->Session->flash('auth');?>

    		<?php 
                echo $content_for_layout; ?>  
		</div>								<!--main_content end-->
	 </div>									<!--content_wrap end-->
	 
	 <div class="footer">
    <?php //echo VERSION_NAME." ".VERSION; ?>
         <?php 
         //echo "( Memory: ".round(memory_get_peak_usage()/1000000).' MB)'; 
         echo "SPEAK EZ";
         ?>         
         </div>
</div>										<!--wrapper end-->

    <?php echo $this->Js->writeBuffer(); ?> 


</body>
</html>