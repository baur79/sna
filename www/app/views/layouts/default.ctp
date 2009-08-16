<!DOCTYPE html PUBLIC
	"-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?=$html->charset()?>
	<title>Social Network Application &middot; <?=$title_for_layout?></title>
	<?php
		echo $html->meta('icon');
		echo $html->css('cake.generic');
		echo $html->css('sna.basic');
		echo $html->css('sna.app');
		echo $html->css('sna.modifications');
		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><?=$html->link(__('Social Network Application', true), '/')?></h1>
		</div>
		<div id="content">
			<?php $session->flash()?>
			<?=$content_for_layout?>
		</div>
		<div id="footer">
			<?=$html->link(
					$html->image('cake.power.gif', array(
						'alt'=> __("CakePHP: the rapid development php framework", true),
						'border'=>"0")),
					'http://www.cakephp.org',
					array('target'=>'_blank'), null, false
				)?>
		</div>
	</div>
	<?=$cakeDebug?>
	</body>
</html>