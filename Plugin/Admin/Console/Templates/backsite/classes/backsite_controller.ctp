<?php
echo "<?php\n";
echo "App::uses('{$backendPluginName}AppController', '{$backendPluginName}.Controller');\n";
?>
/**
 * <?php echo $backendPluginName; ?><?php echo $controllerName; ?>Controller
 *
<?php
	$defaultModel = Inflector::singularize($controllerName);
	echo " * @property {$defaultModel} \${$defaultModel}\n";
?>
 */
class <?php echo $backendPluginName; ?><?php echo $controllerName; ?>Controller extends <?php echo $backendPluginName; ?>AppController {

<?php echo "\tpublic \$uses = array('{$defaultModel}');\n" ?>

<?php echo $actions;?>

}
