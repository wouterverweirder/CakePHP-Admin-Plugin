<?php
error_reporting(E_ALL | E_STRICT);

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('ROOT')) {
	define('ROOT', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
}
if (!defined('APP_DIR')) {
	define('APP_DIR', basename(dirname(dirname(dirname(dirname(__FILE__))))));
}
if (!defined('WEBROOT_DIR')) {
	define('WEBROOT_DIR', basename(dirname(dirname(dirname(__FILE__)))));
}
if (!defined('WWW_ROOT')) {
	define('WWW_ROOT', dirname(dirname(dirname(__FILE__))) . DS);
}
if (!defined('BACKEND_PLUGIN_NAME_UNDERSCORED')) {
	define('BACKEND_PLUGIN_NAME_UNDERSCORED', basename(dirname(dirname(__FILE__))));
}

if (!defined('CAKE_CORE_INCLUDE_PATH')) {
	if (function_exists('ini_set')) {
		ini_set('include_path', ROOT . DS . 'lib' . PATH_SEPARATOR . ini_get('include_path'));
	}
	if (!include('Cake' . DS . 'bootstrap.php')) {
		$failed = true;
	}
} else {
	if (!include(CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php')) {
		$failed = true;
	}
}
if (!empty($failed)) {
	trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
}

if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] == '/favicon.ico') {
	return;
}

App::uses('CakeRequest', 'Network');
$request = new CakeRequest();
$httpRoot = '/' . str_replace(BACKEND_PLUGIN_NAME_UNDERSCORED . '/js/ckfinder/core/connector/php/connector.php', '', dirname(dirname(dirname($request->url)))) . '/';

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

$fileType = 'files';
if(!empty($_GET['type']) && $_GET['type'] == 'image') {
	$fileType = 'images';
}

$args = array(
	'upload_dir' => WWW_ROOT . 'files' . DS . $fileType . DS,
    'upload_url' => $httpRoot . 'files/' . $fileType . '/',
    'image_versions' => array()
);
if(!empty($_GET['required_width'])) {
	$args['min_width'] = $_GET['required_width'];
	$args['max_width'] = $_GET['required_width'];
}
if(!empty($_GET['required_height'])) {
	$args['min_height'] = $_GET['required_height'];
	$args['max_height'] = $_GET['required_height'];
}

//canvas save?
if(!empty($_POST['imgBase64'])) {
	$assetFile = new File($args['upload_dir'] . $_POST['assetPath']);
	$thumbName = $assetFile->name() . '_thumb';
	$thumbFilePath = $args['upload_dir'] . $thumbName . '.png';
	$i = 0;
	while(file_exists($thumbFilePath)) {
		$thumbFilePath = $args['upload_dir'] . $thumbName . '_' . (++$i) . '.png';
	}
	$imageData = substr($_POST['imgBase64'], strpos($_POST['imgBase64'], ",") + 1);
	file_put_contents($thumbFilePath, base64_decode($imageData));
	die(basename($thumbFilePath));
} else {
	require(__DIR__ . '/server/php/UploadHandler.php');
	$upload_handler = new UploadHandler($args);
}