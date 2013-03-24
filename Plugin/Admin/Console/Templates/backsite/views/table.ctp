<?php echo "<?php
\$this->Paginator->options(array(
    'url' => \${$pluralVar}TableURL,
    'update' => '.{$pluralVar}.table',
    'evalScripts' => true
));?>\n";
?>
<table cellpadding="0" cellspacing="0">
<tr>
<?php
    $i = 0;

    $configHiddenFields = Configure::read('admin.console.views.table.hidden_fields');
    if(empty($configHiddenFields)) $configHiddenFields = array('all' => array());
    $hiddenFields = array();
    if(!empty($configHiddenFields['all'])) $hiddenFields = $configHiddenFields['all'];
    if(!empty($configHiddenFields[$modelClass])) $hiddenFields = array_merge($hiddenFields, $configHiddenFields[$modelClass]);

    //displayfield comes first!
    if(!empty($displayField) && !is_array($displayField) && !empty($schema[$displayField])) {
        $hiddenFields[] = $displayField;
        echo "\t<th><?php echo \$this->Paginator->sort('{$displayField}', null, array('model' => '{$modelClass}'));?></th>\n";
    }

    foreach ($schema as $field => $properties) {
        $showField = true;

        if(array_search($field, $hiddenFields) === false)
        {
            $suffix = substr($field, -3);
            if(!($suffix === false))
            {
                $pos = strpos($suffix, '_');
                if(!($pos === false) && $pos == 0 && $suffix != '_id')
                {
                    $showField = false;
                }
            }
            switch($properties['type'])
            {
                case 'text':
                    $showField = false;
                    break;
            }
        }
        else
        {
            $showField = false;
        }

        if($showField)
        {
            $i++;
            echo "\t<th><?php echo \$this->Paginator->sort('{$field}', null, array('model' => '{$modelClass}'));?></th>\n";
        }

        if($i > 7) break;
    }
?>
    <th class="actions"><?php echo "<?php echo __('Actions');?>";?></th>
</tr>
<?php
echo "<?php
foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
echo "\t<tr>\n";
    $i = 0;

    //displayfield comes first!
    if(!empty($displayField) && !is_array($displayField) && !empty($schema[$displayField])) {
        $field = $displayField;
        $isKey = false;
        if (!empty($associations['belongsTo'])) {
            foreach ($associations['belongsTo'] as $alias => $details) {
                if ($field === $details['foreignKey']) {
                    $isKey = true;
                    $associationControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
                    $associationControllerPath = $details['controller'];
                    echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
                    break;
                }
            }
        }
        if ($isKey !== true) {
            switch($properties['type'])
            {
                case 'datetime':
                    echo "\t\t<td><?php echo (empty(\${$singularVar}['{$modelClass}']['{$field}']) || '0000-00-00 00:00:00' == \${$singularVar}['{$modelClass}']['{$field}'] || '1970-01-01 01:00:00' == \${$singularVar}['{$modelClass}']['{$field}']) ? '' : \$this->Time->format('d/m/Y', \${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
                    break;
                default:
                    echo "\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
                    break;
            }
        }
    }

    foreach ($schema as $field => $properties) {
        $showField = true;

        if(array_search($field, $hiddenFields) === false)
        {
            $suffix = substr($field, -3);
            if(!($suffix === false))
            {
                $pos = strpos($suffix, '_');
                if(!($pos === false) && $pos == 0 && $suffix != '_id')
                {
                    $showField = false;
                }
            }
            switch($properties['type'])
            {
                case 'text':
                    $showField = false;
                    break;
            }
        }
        else
        {
            $showField = false;
        }

        if($showField)
        {
            $i++;
            $isKey = false;
            if (!empty($associations['belongsTo'])) {
                foreach ($associations['belongsTo'] as $alias => $details) {
                    if ($field === $details['foreignKey']) {
                        $isKey = true;
                        $associationControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
                        $associationControllerPath = $details['controller'];
                        echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['toString'], array('controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
                        break;
                    }
                }
            }
            if ($isKey !== true) {
                switch($properties['type'])
                {
                    case 'datetime':
                        echo "\t\t<td><?php echo (empty(\${$singularVar}['{$modelClass}']['{$field}']) || '0000-00-00 00:00:00' == \${$singularVar}['{$modelClass}']['{$field}'] || '1970-01-01 01:00:00' == \${$singularVar}['{$modelClass}']['{$field}']) ? '' : \$this->Time->format('d/m/Y', \${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
                        break;
                    default:
                        echo "\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
                        break;
                }
            }
        }
        if($i > 7) break;
    }

    echo "\t\t<td class=\"actions\">\n";
    echo "\t\t\t<?php echo \$this->Html->link(__('View'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$controllerPath}', 'action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
    echo "\t\t\t<?php echo \$this->Html->link(__('Edit'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$controllerPath}', 'action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
    echo "\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$controllerPath}', 'action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), null, __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
    echo "\t\t</td>\n";
echo "\t</tr>\n";

echo "<?php endforeach; ?>\n";
?>
</table>
<p>
<?php echo "<?php
echo \$this->Paginator->counter(array(
'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'),
'model' => '{$modelClass}'
));
?>";?>
</p>

<div class="paging">
<?php
    echo "<?php\n";
    echo "\t\techo \$this->Paginator->prev('< ' . __('previous'), array('model' => '{$modelClass}'), null, array('class' => 'prev disabled'));\n";
    echo "\t\techo \$this->Paginator->numbers(array('separator' => '', 'model' => '{$modelClass}'));\n";
    echo "\t\techo \$this->Paginator->next(__('next') . ' >', array('model' => '{$modelClass}'), null, array('class' => 'next disabled'));\n";
    echo "\t?>\n";
?>
</div>

<?php echo "<?php
      echo \$this->Js->writeBuffer();";?>
