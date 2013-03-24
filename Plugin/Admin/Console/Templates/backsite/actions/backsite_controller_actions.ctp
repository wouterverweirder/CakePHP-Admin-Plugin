<?php if($controllerName == 'Users'):?>
    public function login() {
        if ($this->Auth->login()) {
            $this->redirect($this->Auth->redirect());
        } else {
            if($this->request->isPost()) {
                $this->Auth->flash(__('Invalid username or password, try again'));
            }
        }
    }

    public function logout() {
        $this->redirect($this->Auth->logout());
    }
<?php endif;?>
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

	public function view($id = null) {
		$this-><?php echo $currentModelName; ?>->id = $id;
		if (!$this-><?php echo $currentModelName; ?>->exists()) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
        $<?php echo $singularName; ?> = $this-><?php echo $currentModelName; ?>->read(null, $id);
		$this->set('<?php echo $singularName; ?>', $<?php echo $singularName; ?>);
<?php
		    foreach (array('hasMany') as $assoc):
                foreach ($modelObj->{$assoc} as $associationName => $relation):
                    if (!empty($associationName)):

                        $otherModelName = $this->_modelName($associationName);
                        $otherPluralName = $this->_pluralName($associationName);

                        if($otherModelName == 'Child' . $currentModelName) {
                            //MPTT behaviour
                            echo "\t\t\$this->{$currentModelName}->recursive = 0;\n";
                            echo "\t\t\$this->paginate = array('conditions' => array('{$currentModelName}.parent_id' => \$id), 'limit' => 15);\n";
                            echo "\t\t\$this->set('{$pluralName}', \$this->Paginator->paginate('{$currentModelName}'));\n";
                            echo "\t\t\$this->set('{$pluralName}TableURL', array('controller' => '{$backendPluginNameUnderscored}_{$this->_controllerPath($pluralName)}', 'action' => 'index', '{$currentModelName}-parent_id' => \$id));\n";
                        } else {
                            echo "\t\t//related {$otherPluralName}\n";
                            echo "\t\t\$this->{$currentModelName}->{$otherModelName}->recursive = 0;\n";
                            switch($otherModelName)
                            {
                                default:
                                    echo "\t\t\$this->paginate = array('conditions' => array('{$currentModelName}.id' => \$id), 'limit' => 15);\n";
                                    break;
                            }
                            echo "\t\t\$this->set('{$otherPluralName}', \$this->Paginator->paginate('{$otherModelName}'));\n";
                            echo "\t\t\$this->set('{$otherPluralName}TableURL', array('controller' => '{$backendPluginNameUnderscored}_{$this->_controllerPath($otherPluralName)}', 'action' => 'index', '{$currentModelName}-id' => \$id));\n";
                        }
                    endif;
                endforeach;
		    endforeach;
?>
	}

<?php $compact = array(); ?>

	public function add() {
		if ($this->request->is('post')) {
			$this-><?php echo $currentModelName; ?>->create();
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved'), 'default', array(), 'good');
                $this->redirect(array('action' => 'view', $this-><?php echo $currentModelName; ?>->id));
			} else {
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'), 'default', array(), 'bad');
			}
		} else {
            //add the named params as data
            foreach($this->request->params['named'] as $param => $value) {
                $columnType = $this-><?php echo $currentModelName; ?>->getColumnType($param);
                if(!empty($columnType)) {
                    if(empty($this->request->data['<?php echo $currentModelName; ?>'])) $this->request->data['<?php echo $currentModelName; ?>'] = array();
                    $this->request->data['<?php echo $currentModelName; ?>'][$param] = $value;
                }
            }
        }
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if (!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
                $otherModelObj = ClassRegistry::init($otherModelName);

                if($otherModelName == 'Parent' . $currentModelName) {
                    echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->generateTreeList(null, '{n}.{$currentModelName}.id', '{n}.{$currentModelName}.' . \$this->{$currentModelName}->displayField, ' - ', 0);\n";
                } else {
                    if(!empty($otherModelObj->actsAs) && (array_search('Tree', $otherModelObj->actsAs) !== false)) {
                        echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->generateTreeList(null, '{n}.{$otherModelName}.id', '{n}.{$otherModelName}.' . \$this->{$currentModelName}->{$otherModelName}->displayField, ' - ', 0);\n";
                    } else {
                        echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list', array('order' => \$this->{$currentModelName}->{$otherModelName}->displayField));\n";
                    }
                }

				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;

	if (!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
	}

<?php $compact = array(); ?>

	public function edit($id = null) {
		$this-><?php echo $currentModelName; ?>->id = $id;
		if (!$this-><?php echo $currentModelName; ?>->exists()) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved'), 'default', array(), 'good');
                $this->redirect(array('action' => 'view', $this-><?php echo $currentModelName; ?>->id));
			} else {
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'), 'default', array(), 'bad');
			}
		} else {
            $<?php echo $singularName; ?> = $this-><?php echo $currentModelName; ?>->read(null, $id);
			$this->request->data = $<?php echo $singularName; ?>;
		}
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if (!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
                    $otherModelObj = ClassRegistry::init($otherModelName);

                    if($otherModelName == 'Parent' . $currentModelName) {
                        echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->generateTreeList(null, '{n}.{$currentModelName}.id', '{n}.{$currentModelName}.' . \$this->{$currentModelName}->displayField, ' - ', 0);\n";
                    } else {
                        if(!empty($otherModelObj->actsAs) && (array_search('Tree', $otherModelObj->actsAs) !== false)) {
                            echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->generateTreeList(null, '{n}.{$otherModelName}.id', '{n}.{$otherModelName}.' . \$this->{$currentModelName}->{$otherModelName}->displayField, ' - ', 0);\n";
                        } else {
                            echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list', array('order' => \$this->{$currentModelName}->{$otherModelName}->displayField));\n";
                        }
                    }
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
?>

<?php

		if (!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}

	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this-><?php echo $currentModelName; ?>->id = $id;
		if (!$this-><?php echo $currentModelName; ?>->exists()) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		if ($this-><?php echo $currentModelName; ?>->delete()) {
			$this->Session->setFlash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> deleted'), 'default', array(), 'good');
            $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> was not deleted'), 'default', array(), 'bad');
		$this->redirect(array('action' => 'index'));
	}