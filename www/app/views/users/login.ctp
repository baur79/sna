<div class="users form">
<?php $session->flash('auth')?>
<?=$form->create('User', array('action' => 'login'))?>
	<fieldset>
		<legend><?php __('Login');?></legend>
	<?php if (!empty($authedUser)):?>
	<p><?=__('You are currently logged in as')?> <strong><?=$authedUser['User']['username']?></strong>.</p>
	<?=BR?>
	<?php endif?>

<?=$form->input('username', array('label' => __('Login name', true))),
	$form->input('password', array('label' => __('Password', true)))
?>
	</fieldset>
<?=$form->end('Let me in')?>
</div>
<div class="actions">
	<ul>
		<li><?=$html->link(__('Forgot password?', true), array('controller' => 'users', 'action' => 'forgot_password'))?></li>
	</ul>
</div>