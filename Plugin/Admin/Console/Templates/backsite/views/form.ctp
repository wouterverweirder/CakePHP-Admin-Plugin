<div class="<?php echo $pluralVar;?> form">
<?php echo "<?php echo \$this->ExtendedForm->create('{$modelClass}', array('class' => 'form-horizontal'));?>\n";?>
	<fieldset>
		<legend><?php printf("<?php echo __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></legend>
<?php
		echo "\t<?php\n";

        $configHiddenFields = Configure::read('admin.console.views.form.hidden_fields');
        if(empty($configHiddenFields)) $configHiddenFields = array('all' => array());
        $hiddenFields = array();
        if(!empty($configHiddenFields['all'])) $hiddenFields = $configHiddenFields['all'];
        if(!empty($configHiddenFields[$modelClass])) $hiddenFields = array_merge($hiddenFields, $configHiddenFields[$modelClass]);

        $configFieldTypes = Configure::read('admin.console.views.form.field_types');
        if(empty($configFieldTypes)) $configFieldTypes = array('all' => array());
        $fieldTypes = array();
        if(!empty($configFieldTypes['all'])) $fieldTypes = $configFieldTypes['all'];
        if(!empty($configFieldTypes[$modelClass])) $fieldTypes = array_merge($fieldTypes, $configFieldTypes[$modelClass]);

		foreach ($fields as $field) {
			if (strpos($action, 'add') !== false && $field == $primaryKey) {
				continue;
            } elseif(in_array($field, $hiddenFields)) {
                continue;
			} else {
                $str = '';
                //check for special field types
                $explicitFieldType = '';
                if(!empty($fieldTypes[$field])) {
                    $explicitFieldType = $fieldTypes[$field];
                }
                switch($explicitFieldType) {
                    case 'parent_id':
                        $str = "\t\techo \$this->ExtendedForm->input('{$field}', array('type' => 'select', 'label' => __('Parent " . Inflector::humanize(Inflector::underscore($modelClass)) . "'), 'options' => \$parent" . Inflector::pluralize($modelClass) . "));\n";
                        break;
                    case 'password':
                        if(strpos($action, 'add') !== false) {
                            $str = "\t\techo \$this->ExtendedForm->input('{$field}', array('type' => 'password', 'label' => __('" . Inflector::humanize(Inflector::underscore($field)) . "')));\n";
                        } else {
                            $str = "\t\techo \$this->ExtendedForm->input('new_{$field}', array('type' => 'password', 'label' => __('New " . Inflector::humanize(Inflector::underscore($field)) . "')));\n";
                        }
                        $str .= "\t\techo \$this->ExtendedForm->input('confirm_{$field}', array('type' => 'password', 'label' => __('Confirm " . Inflector::humanize(Inflector::underscore($field)) . "')));\n";
                        break;
                    case '':
                        $str = "\t\techo \$this->ExtendedForm->input('{$field}', array('label' => __('" . Inflector::humanize(Inflector::underscore(preg_replace('/_id$/', '', $field))) . "')));\n";
                        break;
                    default:
                        $str = "\t\techo \$this->ExtendedForm->input('{$field}', array('type' => '{$explicitFieldType}', 'label' => __('" . Inflector::humanize(Inflector::underscore($field)) . "')));\n";
                        break;
                }
                echo $str;
			}
		}

		if (!empty($associations['hasAndBelongsToMany'])) {
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
				echo "\t\techo \$this->ExtendedForm->input('{$assocName}');\n";
			}
		}
		echo "\t?>\n";
?>
	</fieldset>
<?php
    echo "<?php echo \$this->ExtendedForm->end(array('label' => __('Save {$modelClass}'), 'class' => 'btn btn-primary', 'div' => false, 'before' => '<div class=\"control-group\"><div class=\"controls\">', 'after' => \"\\n\" . \$this->Html->link(__('Cancel'), \$cancelUrl, array('class' => 'btn')) . '</div></div>'));?>\n";
?>
</div>