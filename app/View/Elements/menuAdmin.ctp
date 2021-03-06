<?php
/****************************************************************************
 * menu.ctp	- Main horizontal menu
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
 *
 ***************************************************************************/
?>

<div style="background: url('https://www.syr.edu/css/images/bg_topical-nav.png') repeat scroll 0 0; height:30px;">
<ul id='menu'>

<!--
<li class='logo'>
<?php echo $this->Html->image('menu/menu_left.png',array('style'=>'float:left')); ?>
</li>
-->

<li>

<?php echo $this->Html->link(__("Home",true),'/'); ?>
</li>


<!--
<li>| <?php echo __("Poll",true);?>
<ul>
<li>
<?php echo $this->Html->image('menu/corner_inset_left.png',array('class'=>'corner_inset_left')); ?>
<?php echo $this->Html->link(__("Manage polls",true),'/polls/'); ?>
<?php echo $this->Html->image('menu/corner_inset_right.png',array('class'=>'corner_inset_right')); ?>
</li>

<li>
<?php echo $this->Html->link(__("Create poll",true),'/polls/add/'); ?>
</li>



<li class='last'>
<?php echo $this->Html->image('menu/corner_left.png',array('class'=>'corner_left')); ?>
<?php echo $this->Html->image('menu/dot.png',array('class'=>'middle'));?>
<?php echo $this->Html->image('menu/corner_right.png',array('class'=>'corner_right'));?>
</li>
</ul>
</li>
-->

<li>| <?php echo __("Message Center",true);?>
<ul>
<li>
<?php //echo $this->Html->image('menu/corner_inset_left.png',array('class'=>'corner_inset_left')); ?>
<?php echo $this->Html->link(__("Inboxes",true),'/messages/'); ?>
<?php //echo $this->Html->image('menu/corner_inset_right.png',array('class'=>'corner_inset_right')); ?>
</li>

<li>
<?php echo $this->Html->link(__("Archives",true),'/messages/archive'); ?>
</li>


<li>
<?php echo $this->Html->link(__("Tags",true),'/tags/'); ?>
</li>

<li>
<?php echo $this->Html->link(__("Categories",true),'/categories/'); ?>
</li>

<li>
<?php echo $this->Html->link(__("Manage LAM",true),'/lm_menus/index'); ?>
</li>

<li class='last'>
<?php //echo $this->Html->image('menu/corner_left.png',array('class'=>'corner_left')); ?>
<?php //echo $this->Html->image('menu/dot.png',array('class'=>'middle'));?>
<?php //echo $this->Html->image('menu/corner_right.png',array('class'=>'corner_right'));?>
</li>
</ul>
</li>




<li><?php echo __("SMS Center",true);?>
<ul>
<li>
<?php //echo $this->Html->image('menu/corner_inset_left.png',array('class'=>'corner_inset_left')); ?>
<?php echo $this->Html->link(__("Inboxes",true),'/bin/'); ?>
<?php //echo $this->Html->image('menu/corner_inset_right.png',array('class'=>'corner_inset_right')); ?>
</li>

<li>
<?php echo $this->Html->link(__("SMS gateways",true),'/sms_gateways/index'); ?>
</li>


<li>
<?php echo $this->Html->link(__("SMS batches",true),'/batches/index'); ?>
</li>

<li>
<?php echo $this->Html->link(__("Create batch",true),'/batches/add'); ?>
</li>


<li class='last'>
<?php //echo $this->Html->image('menu/corner_left.png',array('class'=>'corner_left')); ?>
<?php //echo $this->Html->image('menu/dot.png',array('class'=>'middle'));?>
<?php //echo $this->Html->image('menu/corner_right.png',array('class'=>'corner_right'));?>
</li>
</ul>
</li>



<li><?php echo __("IVR Center",true);?>
<ul>
<li>
<?php //echo $this->Html->image('menu/corner_inset_left.png',array('class'=>'corner_inset_left')); ?>
<?php echo $this->Html->link(__("Language selectors",true),'/selectors'); ?>
<?php //echo $this->Html->image('menu/corner_inset_right.png',array('class'=>'corner_inset_right')); ?>
</li>


<li>
<?php echo $this->Html->link(__("Voice menus",true),'/ivr_menus'); ?>
</li>


<li>
<?php echo $this->Html->link(__("Content",true),'/nodes/index'); ?>
</li>


<li class='last'>
<?php //echo $this->Html->image('menu/corner_left.png',array('class'=>'corner_left')); ?>
<?php //echo $this->Html->image('menu/dot.png',array('class'=>'middle'));?>
<?php //echo $this->Html->image('menu/corner_right.png',array('class'=>'corner_right'));?>
</li>
</ul>
</li>

<li><?php echo __("Alert",true);?>
<ul>

<li>
<?php //echo $this->Html->image('menu/corner_inset_left.png',array('class'=>'corner_inset_left')); ?>
<?php echo $this->Html->link(__("Create Alert File",true),'/CreateAlerts/add/'); ?>
<?php //echo $this->Html->image('menu/corner_inset_right.png',array('class'=>'corner_inset_right')); ?>
</li>

<li>
<?php echo $this->Html->link(__("Send Immediate Alert",true),'/CreateAlerts/immediate/'); ?>
</li>

<!-- <li>
<?php echo $this->Html->link(__("Send Scheduled Alert",true),'/CreateAlerts/scheduled/'); ?>
</li> --> 

<li class='last'>
<?php //echo $this->Html->image('menu/corner_left.png',array('class'=>'corner_left')); ?>
<?php //echo $this->Html->image('menu/dot.png',array('class'=>'middle'));?>
<?php //echo $this->Html->image('menu/corner_right.png',array('class'=>'corner_right'));?>
</li>

</ul>
</li>

<li>
<?php echo $this->Html->link(__("Voice Poll",true),'/voicePolls'); ?>
</li>
<li>
<?php echo $this->Html->link(__("Community Members |",true),'/PhoneDirectories'); ?>
</li>




<li style="width:210px; text-align:center;"> <?php echo $this->Html->link(__("SPEAK EZ",true),'/'); ?></li>

<li><?php echo __("| User Management",true);?>
<ul>
<li>
<?php //echo $this->Html->image('menu/corner_inset_left.png',array('class'=>'corner_inset_left')); ?>
<?php echo $this->Html->link(__("Phone books",true),'/phone_books'); ?>
<?php //echo $this->Html->image('menu/corner_inset_right.png',array('class'=>'corner_inset_right')); ?>
</li>

<li>
<?php echo $this->Html->link(__("Callers",true),'/callers'); ?>
</li>

<li>
<?php echo $this->Html->link(__("System users",true),'/users'); ?>
</li>

<li>
<?php echo $this->Html->link(__("Frontend Sweeper",true),'/users/system_sweeper'); ?>
</li>


<li class='last'>
<?php //echo $this->Html->image('menu/corner_left.png',array('class'=>'corner_left')); ?>
<?php //echo $this->Html->image('menu/dot.png',array('class'=>'middle'));?>
<?php //echo $this->Html->image('menu/corner_right.png',array('class'=>'corner_right'));?>
</li>
</ul>
</li>


<li><?php echo __("System data",true);?>
<ul>

<li>
<?php //echo $this->Html->image('menu/corner_inset_left.png',array('class'=>'corner_inset_left')); ?>
<?php echo $this->Html->link(__("Call data records",true),'/cdr/'); ?>
<?php //echo $this->Html->image('menu/corner_inset_right.png',array('class'=>'corner_inset_right')); ?>
</li>



<li>
<?php echo $this->Html->link(__("Statistics",true),'/cdr/statistics'); ?>
</li>

<li>
<?php echo $this->Html->link(__("Monitoring",true),'/monitor_ivr'); ?>
</li>

<li>
<?php echo $this->Html->link(__("Call detail report",true),'/cdr/general'); ?>
</li>


<li>
<?php echo $this->Html->link(__("Call graphs",true),'/reporting'); ?>
</li>




<li class='last'>
<?php //echo $this->Html->image('menu/corner_left.png',array('class'=>'corner_left')); ?>
<?php //echo $this->Html->image('menu/dot.png',array('class'=>'middle'));?>
<?php //echo $this->Html->image('menu/corner_right.png',array('class'=>'corner_right'));?>
</li>
</ul>
</li>

<li><?php echo __("Dashboard",true);?>
<ul>

<li>
<?php //echo $this->Html->image('menu/corner_inset_left.png',array('class'=>'corner_inset_left')); ?>
<?php echo $this->Html->link(__("Health",true),'/processes'); ?>
<?php //echo $this->Html->image('menu/corner_inset_right.png',array('class'=>'corner_inset_right')); ?>
</li>


<li>
<?php echo $this->Html->link(__("Settings",true),'/settings'); ?>
</li>


<li>
<?php echo $this->Html->link(__("Active GSM channels",true),'/channels'); ?>
</li>

<li>
<?php echo $this->Html->link(__("Service mapping",true),'/channels/audio_services'); ?>
</li>



<li>
<?php echo $this->Html->link(__("Logs",true),'/logs'); ?>
</li>


<li>
<?php echo $this->Html->link(__("About",true),'/dashboard/about'); ?>
</li>


<li class='last'>
<?php //echo $this->Html->image('menu/corner_left.png',array('class'=>'corner_left')); ?>
<?php //echo $this->Html->image('menu/dot.png',array('class'=>'middle'));?>
<?php //echo $this->Html->image('menu/corner_right.png',array('class'=>'corner_right'));?>
</li>
</ul>
</li>


<li><?php echo __("Help",true);?>
<ul>

<li>
<?php //echo $this->Html->image('menu/corner_inset_left.png',array('class'=>'corner_inset_left')); ?>
<?php echo $this->Html->link(__("Downloads",true),'http://www.freedomfone.org/page/downloads',array('target'=>'blank')); ?>
<?php //echo $this->Html->image('menu/corner_inset_right.png',array('class'=>'corner_inset_right')); ?>
</li>


<li>
<?php echo $this->Html->link(__("Website",true),'http://www.freedomfone.org',array('target'=>'blank')); ?>
</li>

<li>
<?php echo $this->Html->link(__("Wiki/SVN",true),'http://dev.freedomfone.org/wiki',array('target'=>'blank')); ?>
</li>


<li>
<?php echo $this->Html->link(__("Feedback",true),'http://www.freedomfone.org/page/feedback',array('target'=>'blank')); ?>
</li>



<li class='last'>
<?php //echo $this->Html->image('menu/corner_left.png',array('class'=>'corner_left')); ?>
<?php //echo $this->Html->image('menu/dot.png',array('class'=>'middle'));?>
<?php //echo $this->Html->image('menu/corner_right.png',array('class'=>'corner_right'));?>
</li>
</ul>
</li>

<li>
<?php echo $this->Html->link(__("Overview |",true),'/overview'); ?>
</li>

<!--
<li class='logo'>
<?php echo $this->Html->image('menu/menu_right_long.png',array('style'=>'float:left'));?>
</li>
-->

</ul>
</div>
