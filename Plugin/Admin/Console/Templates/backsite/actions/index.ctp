    public function index() {
	    $conditions = array();
        $<?php echo $pluralName ?>TableURL = array('controller' => '<?php echo $backendPluginNameUnderscored . '_' . $controllerPath; ?>', 'action' => 'index');

        //join get query & named params
        $params = array_merge($this->request->params['named']);
        foreach($this->request->query as $key => $value) $params[$key] = $value;

        foreach($params as $key => $value) {
            $split = explode('-', $key);
            $modelName = (sizeof($split) > 1) ? $split[0] : '<?php echo $currentModelName ?>';
            $property = (sizeof($split) > 1) ? $split[1] : $key;
            if($modelName == '<?php echo $currentModelName ?>' || !empty($this-><?php echo $currentModelName ?>->belongsTo[$modelName])) {
                $this->loadModel($modelName);
                $modelObj = new $modelName();
                if(!empty($modelObj)) {
                    $columnType = $modelObj->getColumnType($property);
                    if(!empty($columnType)){
                        //add it to url
                        $<?php echo $pluralName ?>TableURL[$key] = $value;
                        //add it to conditions
                        switch($columnType)
                        {
                            case 'string':
                                $conditions[$modelName . '.' . $property . ' LIKE'] = '%'.$value.'%';
                                break;
                            default:
                                $conditions[$modelName . '.' . $property] = $value;
                                break;
                        }
                    }
                }
            }

        }

		$this-><?php echo $currentModelName ?>->recursive = 0;
		$this->set('<?php echo $pluralName ?>', $this->Paginator->paginate('<?php echo $currentModelName ?>', $conditions, array()));
		$this->set('<?php echo $pluralName ?>TableURL', $<?php echo $pluralName ?>TableURL);
		//render as local table if it is an ajax request
        if($this->request->is('ajax'))
        {
            $this->render('table');
        }
	}
    