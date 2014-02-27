<?php echo "<?php
\$this->Paginator->options(array(
    'url' => \${$pluralVar}TableURL,
    'update' => '.{$pluralVar}.table',
    'evalScripts' => true
));?>\n";
?>
<table class="table table-striped">
<tr>
<?php
    $i = 0;

    $configHiddenFields = Configure::read('admin.console.views.table.hidden_fields');
    if(empty($configHiddenFields)) $configHiddenFields = array('all' => array());
    $hiddenFields = array();
    if(!empty($configHiddenFields['all'])) $hiddenFields = $configHiddenFields['all'];
    if(!empty($configHiddenFields[$modelClass])) $hiddenFields = array_merge($hiddenFields, $configHiddenFields[$modelClass]);

    //displayField comes first!
    if(!empty($displayField) && !is_array($displayField) && !empty($schema[$displayField]) && array_search($displayField, $hiddenFields) === false) {
        $displayFieldFromSchema = $schema[$displayField];
        unset($schema[$displayField]);
        $schema = array_merge(array($displayField => $displayFieldFromSchema), $schema);
    }

    $schemaForTable = array();

    $configExplicitFields = Configure::read('admin.console.views.table.explicit_fields');
    if(!empty($configExplicitFields) && !empty($configExplicitFields[$modelClass])) {
        foreach ($configExplicitFields[$modelClass] as $field) {
            $schemaForTable[$field] = $schema[$field];
        }
    } else {
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
                        if($field != $displayField) {
                            $showField = false;
                        }
                        break;
                }
            }
            else
            {
                $showField = false;
            }

            if($showField)
            {
                $schemaForTable[$field] = $properties;
                $i++;
            }

            if($i > 7) break;
        }
    }

    foreach($schemaForTable as $field => $properties) {
        echo "\t<th><?php echo \$this->Paginator->sort('{$field}', null, array('model' => \${$pluralVar}TableModelAlias));?></th>\n";
    }
?>
    <th class="actions"><?php echo "<?php echo __('Actions');?>";?></th>
</tr>
<?php
echo "<?php
foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
echo "\t<tr>\n";
    $i = 0;

    foreach ($schemaForTable as $field => $properties) {
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
                    echo "\t\t<td><?php echo (empty(\${$singularVar}[\${$pluralVar}TableModelAlias]['{$field}']) || '0000-00-00 00:00:00' == \${$singularVar}[\${$pluralVar}TableModelAlias]['{$field}'] || '1970-01-01 01:00:00' == \${$singularVar}[\${$pluralVar}TableModelAlias]['{$field}']) ? '' : \$this->Time->format('d/m/Y', \${$singularVar}[\${$pluralVar}TableModelAlias]['{$field}']); ?>&nbsp;</td>\n";
                    break;
                default:
                    echo "\t\t<td><?php echo h(\${$singularVar}[\${$pluralVar}TableModelAlias]['{$field}']); ?>&nbsp;</td>\n";
                    break;
            }
        }
    }

    echo "\t\t<td class=\"actions\">\n";

    $actions = array('index', 'view', 'add', 'edit', 'delete');
    $configDisabledActions = Configure::read('admin.console.models.disabledActions');
    $configDisabledActions = (!empty($configDisabledActions[$modelClass])) ? $configDisabledActions[$modelClass] : array();
    $actions = array_diff($actions, $configDisabledActions);

    if(array_search('view', $actions) !== false) echo "\t\t\t<?php echo \$this->Html->link(__('View'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$controllerPath}', 'action' => 'view', \${$singularVar}[\${$pluralVar}TableModelAlias]['{$primaryKey}']), array('class' => 'btn btn-info btn-mini')); ?>\n";
    if(array_search('edit', $actions) !== false) echo "\t\t\t<?php echo \$this->Html->link(__('Edit'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$controllerPath}', 'action' => 'edit', \${$singularVar}[\${$pluralVar}TableModelAlias]['{$primaryKey}'], '?' => array('redirect' => \$redirectUrl)), array('class' => 'btn btn-mini')); ?>\n";
    if(array_search('delete', $actions) !== false) echo "\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$controllerPath}', 'action' => 'delete', \${$singularVar}[\${$pluralVar}TableModelAlias]['{$primaryKey}'], '?' => array('redirect' => \$redirectUrl)), array('class' => 'btn btn-danger btn-mini'), __('Are you sure you want to delete # %s?', \${$singularVar}[\${$pluralVar}TableModelAlias]['{$primaryKey}'])); ?>\n";
    
    echo "\t\t</td>\n";
echo "\t</tr>\n";

echo "<?php endforeach; ?>\n";
?>
</table>
<p>
<?php echo "<?php
echo \$this->Paginator->counter(array(
'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'),
'model' => \${$pluralVar}TableModelAlias
));
?>";?>
</p>

<div class="pagination">
    <ul>
<?php
    echo "<?php\n";
    echo "\t\techo '<li>' . \$this->Paginator->prev('Prev', array('model' => \${$pluralVar}TableModelAlias), null, array('class' => 'prev disabled')) . '</li>';\n";
    echo "\t\techo \$this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentTag' => 'span', 'model' => \${$pluralVar}TableModelAlias));\n";
    echo "\t\techo '<li>' . \$this->Paginator->next('Next', array('model' => \${$pluralVar}TableModelAlias), null, array('class' => 'next disabled')) . '</li>';\n";
    echo "\t?>\n";
?>
    </ul>
</div>

<?php echo "<?php
      echo \$this->Js->writeBuffer();";?>
