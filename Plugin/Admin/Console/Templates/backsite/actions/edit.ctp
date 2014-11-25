	public function edit($id = null) {
		$this-><?php echo $currentModelName; ?>->id = $id;
		if (!$this-><?php echo $currentModelName; ?>->exists()) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved'), 'default', array(), 'good');
				$this->redirect($this->redirectUrl);
			} else {
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'), 'default', array(), 'bad');
			}
		} else {
			$<?php echo $singularName; ?> = $this-><?php echo $currentModelName; ?>->read(null, $id);
			$this->request->data = $<?php echo $singularName; ?>;
			$this->set('<?php echo $singularName; ?>', $<?php echo $singularName; ?>);
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

	                echo "\t\tasort(\${$otherPluralName}Select);\n";

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

