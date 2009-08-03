<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Add User');?></legend>
	<?php
		echo $form->input('has_accepted_tos');
		echo $form->input('is_hidden');
		echo $form->input('is_disabled');
		echo $form->input('is_deleted');
		echo $form->input('username');
		echo $form->input('password');
		echo $form->input('nickname');
		echo $form->input('email');
		echo $form->input('activation_key');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Users', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List User Options', true), array('controller'=> 'user_options', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User Option', true), array('controller'=> 'user_options', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Messages', true), array('controller'=> 'messages', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Message', true), array('controller'=> 'messages', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Shouts', true), array('controller'=> 'shouts', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Shout', true), array('controller'=> 'shouts', 'action'=>'add')); ?> </li>
	</ul>
</div>