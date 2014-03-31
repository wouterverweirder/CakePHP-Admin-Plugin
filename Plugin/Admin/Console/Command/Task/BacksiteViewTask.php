<?php
App::uses('BakeTask', 'Console/Command/Task');
class BacksiteViewTask extends BakeTask {

	public $tasks = array('Controller', 'DbConfig', 'Template');

	protected $_backendPluginName = null;

	public $path = null;

	public $noViewTemplateActions = array('delete');
	public $template = null;

	public function initialize() {
		$this->_backendPluginName = basename(dirname(dirname(dirname(dirname(__FILE__)))));
		$this->path = App::pluginPath($this->_backendPluginName) . 'View' . DS;
		parent::initialize();
	}

	public function execute() {

		$this->DbConfig->interactive = false;
		$this->Controller->interactive = false;
		$this->Template->params['theme'] = 'backsite';

		$this->bakeViews();
	}

    public function bakeViews() {
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
		        	$vars = $this->_loadController($controller);
					$actions = $this->_methodsToBake($controller);
					$this->bakeViewActions($controller, $actions, $vars);
		        }
			}
		}
    }

    public function bakeViewActions($controllerName, $actions, $vars) {
		foreach ($actions as $action) {
			$content = $this->getViewContent($controllerName, $action, $vars);
			$this->bakeView($controllerName, $action, $content);
		}
	}

	public function getViewContent($controllerName, $action, $vars = null) {
		if (!$vars) {
			$vars = $this->_loadController($controllerName);
		}
		$this->Template->set('action', $action);
		$this->Template->set($vars);
		$template = $this->getViewTemplate($controllerName, $action);
		if ($template) {
			if(is_array($template)) {
				return $this->Template->generate($template['folder'], $template['action']);
			} else {
				return $this->Template->generate('views', $template);
			}
		}
		return false;
	}

	public function bakeView($controllerName, $action, $content = '') {
        if ($content === true) {
            $content = $this->getViewContent($controllerName, $action);
        }
        if (empty($content)) {
            return false;
        }
        $this->out("\n" . __d('cake_console', 'Baking `%s` view file...', $action), 1, Shell::QUIET);
        $path = $this->getPath();
        $filename = $path . $this->_backendPluginName . $controllerName . DS . Inflector::underscore($action) . '.ctp';
        return $this->createFile($filename, $content);
	}

	public function getViewTemplate($controllerName, $action) {
		if ($action != $this->template && in_array($action, $this->noViewTemplateActions)) {
			return false;
		}
		if (!empty($this->template) && $action != $this->template) {
			return $this->template;
		}
		$themePath = $this->Template->getThemePath();
		if(in_array($action, array('add', 'edit')) && file_exists($themePath . 'views' . DS . $controllerName . DS . 'form.ctp')) {
			return array('folder' => 'views' . DS . $controllerName, 'action' => 'form');
		}
		if (file_exists($themePath . 'views' . DS . $controllerName . DS . $action . '.ctp')) {
			return array('folder' => 'views' . DS . $controllerName, 'action' => $action);
		}
		if (file_exists($themePath . 'views' . DS . $action . '.ctp')) {
			return $action;
		}
		$template = $action;
		$prefixes = Configure::read('Routing.prefixes');
		foreach ((array)$prefixes as $prefix) {
			if (strpos($template, $prefix) !== false) {
				$template = str_replace($prefix . '_', '', $template);
			}
		}
		if (in_array($template, array('add', 'edit'))) {
			$template = 'form';
		} elseif (preg_match('@(_add|_edit)$@', $template)) {
			$template = str_replace(array('_add', '_edit'), '_form', $template);
		}
		return $template;
	}

	protected function _loadController($controllerName) {
		$controllerClassName = $this->_backendPluginName . $controllerName . 'Controller';
		App::uses($controllerClassName, $this->_backendPluginName . '.Controller');
		if (!class_exists($controllerClassName)) {
			$file = $controllerClassName . '.php';
			$this->err(__d('cake_console', "The file '%s' could not be found.\nIn order to bake a view, you'll need to first create the controller.", $file));
			$this->_stop();
		}
		$controllerObj = new $controllerClassName();
		$controllerObj->constructClasses();
		$modelClass = $controllerObj->modelClass;
		$modelObj = $controllerObj->{$controllerObj->modelClass};

		if ($modelObj) {
			$primaryKey = $modelObj->primaryKey;
			$displayField = $modelObj->displayField;
			$singularVar = Inflector::variable($modelClass);
			$singularHumanName = $this->_singularHumanName($controllerName);
			$schema = $modelObj->schema(true);
			$fields = array_keys($schema);
			$associations = $this->_associations($modelObj);
		} else {
			$primaryKey = $displayField = null;
			$singularVar = Inflector::variable(Inflector::singularize($controllerName));
			$singularHumanName = $this->_singularHumanName($controllerName);
			$fields = $schema = $associations = array();
		}
		$pluralVar = Inflector::variable($controllerName);
		$pluralHumanName = $this->_pluralHumanName($controllerName);

		$backendPluginName = $this->_backendPluginName;
		$backendPluginNameUnderscored = Inflector::underscore($backendPluginName);

		$controllerPath = Inflector::underscore($controllerName);

		return compact('backendPluginName', 'backendPluginNameUnderscored', 'modelClass', 'schema', 'primaryKey', 'displayField', 'singularVar', 'pluralVar',
				'singularHumanName', 'pluralHumanName', 'fields', 'associations',
                'controllerName', 'controllerPath', 'modelObj');
	}

	protected function _associations(Model $model) {
		$keys = array('belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany');
		$associations = array();

		foreach ($keys as $key => $type) {
			foreach ($model->{$type} as $assocKey => $assocData) {
				list($plugin, $modelClass) = pluginSplit($assocData['className']);
				$associations[$type][$assocKey]['primaryKey'] = $model->{$assocKey}->primaryKey;
				$associations[$type][$assocKey]['displayField'] = $model->{$assocKey}->displayField;
				$associations[$type][$assocKey]['foreignKey'] = $assocData['foreignKey'];
				$associations[$type][$assocKey]['className'] = $assocData['className'];
				$associations[$type][$assocKey]['controller'] = Inflector::pluralize(Inflector::underscore($modelClass));
				$associations[$type][$assocKey]['fields'] = array_keys($model->{$assocKey}->schema(true));
			}
		}
		return $associations;
	}

	protected function _methodsToBake($controllerName) {
		$methods = array_diff(
			array_map('strtolower', get_class_methods($this->_backendPluginName . $controllerName . 'Controller')),
			array_map('strtolower', get_class_methods('AppController'))
		);

		$scaffoldActions = false;
		if (empty($methods)) {
			$scaffoldActions = true;
			$methods = $this->scaffoldActions;
		} else {
            $methods[] = "table";
        }

		foreach ($methods as $i => $method) {
			if (
				$method[0] === '_'
				|| $method == strtolower($this->_backendPluginName . $controllerName . 'Controller')
				|| $method == 'isauthorized'
				|| $method == 'logout') {
				unset($methods[$i]);
			}
		}

		return $methods;
	}

	protected function _listTables() {
		if (empty($this->connection)) {
			$this->connection = $this->DbConfig->getConfig();
		}
		return $this->Controller->listAll($this->connection, false);
	}
}