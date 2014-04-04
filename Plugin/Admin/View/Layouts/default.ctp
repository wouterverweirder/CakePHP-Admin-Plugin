<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $backendPluginName; ?>
		<?php echo $title_for_layout; ?>
	</title>

	<?php
		echo $this->Html->meta('icon');

        echo $this->Html->css('/' . $backendPluginNameUnderscored . '-plugin/css/bootstrap.min.css');

        echo $this->Html->script('/' . $backendPluginNameUnderscored . '-plugin/js/vendors.min.js');
        echo $this->Html->script('/' . $backendPluginNameUnderscored . '-plugin/js/ckeditor/ckeditor.js');
        echo $this->Html->script('/' . $backendPluginNameUnderscored . '-plugin/js/ckfinder/ckfinder.js');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div class="container">
		<div class="navbar">
			<div class="navbar-inner">
				<a class="brand" href="#"><?php echo $backendPluginName; ?></a>
				<ul class="nav">
					<li><?php echo $this->Html->link('Overview', '/' . $backendPluginNameUnderscored);?></li>
					<li><?php echo $this->Html->link('Users', '/' . $backendPluginNameUnderscored . '/users');?></li>
				</ul>
				<ul class="nav pull-right">
					<li><?php echo $this->Html->link('Logout', '/' . $backendPluginNameUnderscored . '/users/logout');?></li>
				</ul>
			</div>
		</div>
		<div id="content">

			<?php echo $this->Session->flash('bad', array('params' => array('class' => 'alert alert-error'))); ?>
            <?php echo $this->Session->flash('good', array('params' => array('class' => 'alert alert-success'))); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		</div>
	</div>
	<?php echo $this->Html->script('/' . $backendPluginNameUnderscored . '-plugin/js/admin.min.js'); ?>
	<?php echo $this->Js->writeBuffer(); ?>
</body>
</html>
