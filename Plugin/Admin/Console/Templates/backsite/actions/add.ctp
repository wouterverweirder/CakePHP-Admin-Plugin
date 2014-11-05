	public function add() {
		if ($this->request->is('post')) {
			$this-><?php echo $currentModelName; ?>->create();
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved'), 'default', array(), 'good');
                if(!empty($this->request->query['redirect'])) {
					$this->redirect($this->redirectUrl);
				} else {
					$this->redirect(array('action' => 'view', $this-><?php echo $currentModelName; ?>->id));
				}
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
                    //is this a reference to a related object?
                    foreach ($this-><?php echo $currentModelName; ?>->belongsTo as $relationName => $relationInfo) {
                    	if($relationInfo['foreignKey'] == $param) {
                    		$relatedRecord = $this-><?php echo $currentModelName; ?>->$relationInfo['className']->find('first', array('conditions' => array($relationInfo['className'] . '.id' => $value), 'recursive' => 0));
                    		$this->set(Inflector::variable($relationInfo['className']), $relatedRecord);
                    	}
                    }
                }
            }
        }
<?php
	if($modelObj->Behaviors->loaded('Tree')) {
		$otherPluralName = $this->_pluralName('Parent' . $currentModelName);
		echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->find('all', array('order' => '{$currentModelName}.lft'));\n";
		echo "\t\t\${$otherPluralName}Select = \$this->{$currentModelName}->addTreePrefixes(\${$otherPluralName});\n";
		echo "\t\t\${$otherPluralName}Select = Hash::combine(\${$otherPluralName}Select, '{n}.{$currentModelName}.id', '{n}.{$currentModelName}');\n";
		echo "\t\t\${$otherPluralName}Select = \$this->{$currentModelName}->convertToListItemsWithDataAttributes(\${$otherPluralName}Select);\n";
		$compact[] = "'{$otherPluralName}'";
		$compact[] = "'{$otherPluralName}Select'";
	}
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if (!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
                $otherModelObj = ClassRegistry::init($otherModelName);

                if($otherModelObj->Behaviors->loaded('Tree')) {
                		echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('all', array('order' => '{$otherModelName}.lft'));\n";
										echo "\t\t\${$otherPluralName}Select = \$this->{$currentModelName}->{$otherModelName}->addTreePrefixes(\${$otherPluralName});\n";
										echo "\t\t\${$otherPluralName}Select = Hash::combine(\${$otherPluralName}Select, '{n}.{$otherModelName}.id', '{n}.{$otherModelName}');\n";
										echo "\t\t\${$otherPluralName}Select = \$this->{$currentModelName}->{$otherModelName}->convertToListItemsWithDataAttributes(\${$otherPluralName}Select);\n";
                } else {
                    echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('all', array('order' => '{$otherModelName}.'.\$this->{$currentModelName}->{$otherModelName}->displayField));\n";
                    echo "\t\t\${$otherPluralName}Select = Hash::combine(\${$otherPluralName}, '{n}.{$otherModelName}.id', '{n}.{$otherModelName}');\n";
                    echo "\t\t\${$otherPluralName}Select = \$this->{$currentModelName}->convertToListItemsWithDataAttributes(\${$otherPluralName}Select);\n";
                    echo "\t\t\${$otherPluralName}Select = Hash::sort(\${$otherPluralName}Select, '{n}.name', 'asc');\n";
                }

				$compact[] = "'{$otherPluralName}'";
				$compact[] = "'{$otherPluralName}Select'";
			endif;
		endforeach;
	endforeach;

	if (!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
	}

