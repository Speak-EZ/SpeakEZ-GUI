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

<li>

<?php echo $this->Html->link(__("Home",true),'/'); ?>
</li>


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

<li>
<?php echo '                '?>
</li>


</ul>
</div>
