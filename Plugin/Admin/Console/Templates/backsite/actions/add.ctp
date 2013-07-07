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
	