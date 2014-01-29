<?php
App::uses('BakeTask', 'Console/Command/Task');
class BacksiteControllerTask extends BakeTask {

	public $tasks = array('Controller', 'DbConfig', 'Template');

	protected $_backendPluginName = null;

	public $path = null;

	public function initialize() {
		$this->_backendPluginName = basename(dirname(dirname(dirname(dirname(__FILE__)))));
		$this->path = App::pluginPath($this->_backendPluginName) . 'Controller' . DS;
		parent::initialize();
	}

	public function execute() {
		$this->DbConfig->interactive = false;
		$this->Controller->interactive = false;
		$this->Template->params['theme'] = 'backsite';

		$this->bakeControllers();
	}

	public function bakeControllers() {
		$tables = $this->_listTables();
		foreach ($tables as $table) {
			$model = $this->_modelName($table);
			$controller = $this->_controllerName($model);
			App::uses($model, 'Model');
			if (class_exists($model)) {
				$modelObj = ClassRegistry::init($model);
		        if($modelObj->useDbConfig != 'default') {
		        	$this->out("\n" . 'skipping ' . $model);
		        } else {
					$actions = $this->bakeControllerActions($controller);
					if(!empty($actions)) {
						$this->bakeController($controller, $actions);
					}
		        }
			}
		}
	}

	public function bakeControllerActions($controllerName) {
		$currentModelName = $modelImport = $this->_modelName($controllerName);
		App::uses($modelImport, 'Model');
		if (!class_exists($modelImport)) {
            $this->err(__d('cake_console', 'You must have a model for this class to build basic methods. Please try again.'));
            $this->_stop();
        }
        $modelObj = ClassRegistry::init($currentModelName);

        $controllerPath = $this->_controllerPath($controllerName);
        $pluralName = $this->_pluralName($currentModelName);
        $singularName = Inflector::variable($currentModelName);
        $singularHumanName = $this->_singularHumanName($controllerName);
        $pluralHumanName = $this->_pluralName($controllerName);
        $displayField = $modelObj->displayField;
        $primaryKey = $modelObj->primaryKey;

        $backendPluginName = $this->_backendPluginName;
        $backendPluginNameUnderscored = Inflector::underscore($backendPluginName);

        $this->Template->set(compact(
            'backendPluginName', 'backendPluginNameUnderscored', 'controllerPath', 'pluralName', 'singularName',
            'singularHumanName', 'pluralHumanName', 'modelObj', 'currentModelName',
            'displayField', 'primaryKey',
            'controllerName'
        ));

        $actions = $this->Template->generate('actions', 'backsite_controller_actions');
        return $actions;
	}

	public function bakeController($controllerName, $actions = '') {
        $this->out("\n" . __d('cake_console', 'Baking '. $this->_backendPluginName . ' controller class for %s...', $controllerName), 1, Shell::QUIET);

        $backendPluginName = $this->_backendPluginName;

        $this->Template->set(compact('backendPluginName', 'controllerName', 'actions'));
        $contents = $this->Template->generate('classes', 'backsite_controller');

        $path = $this->getPath();
        $filename = $path . $this->_backendPluginName . $controllerName . 'Controller.php';
        if ($this->createFile($filename, $contents)) {
            return $contents;
        }
        return false;
    }

	protected function _listTables() {
		if (empty($this->connection)) {
			$this->connection = $this->DbConfig->getConfig();
		}
		return $this->Controller->listAll($this->connection, false);
	}
}