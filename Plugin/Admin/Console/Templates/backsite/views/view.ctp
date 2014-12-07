<h2><?php echo "<?php  echo __d('{$backendPluginNameUnderscored}', '{$singularHumanName}') . ': ' . \${$singularVar}['{$modelClass}']['toString'];?>";?></h2>
<ul class="nav nav-tabs">
    <li class="active"><a href="#tabs-1" data-toggle="tab"><?php echo "<?php  echo __d('{$backendPluginNameUnderscored}', 'Details');?>";?></a></li>
<?php
    //create tabs for associated models
    $tabnr = 1;

    $configHiddenTabs = Configure::read('admin.console.views.view.hidden_tabs');
    if(empty($configHiddenTabs)) $configHiddenTabs = array('all' => array());
    $hiddenTabs = array();
    if(!empty($configHiddenTabs['all'])) $hiddenTabs = $configHiddenTabs['all'];
    if(!empty($configHiddenTabs[$modelClass])) $hiddenTabs = array_merge($hiddenTabs, $configHiddenTabs[$modelClass]);

    if($modelObj->Behaviors->loaded('Tree')) {
        ++$tabnr;
        echo "\t\t<li><a href=\"#tabs-{$tabnr}\" data-toggle=\"tab\"><?php echo __d('{$backendPluginNameUnderscored}', 'Child " . Inflector::humanize(Inflector::underscore(Inflector::pluralize($modelClass))) . "');?></a></li>\n";
    }

    if (!empty($associations['hasOne'])) :
        foreach ($associations['hasOne'] as $alias => $details):
            if(array_search($alias, $hiddenTabs) === false) {
                ++$tabnr;
                echo "\t\t<li><a href=\"#tabs-{$tabnr}\" data-toggle=\"tab\"><?php echo __d('{$backendPluginNameUnderscored}', '" . Inflector::humanize(Inflector::underscore(Inflector::pluralize($alias))) . "');?></a></li>\n";
            }
        endforeach;
    endif;
    if (!empty($associations['hasMany'])) :
        foreach ($associations['hasMany'] as $alias => $details):
            if(array_search($alias, $hiddenTabs) === false) {
                ++$tabnr;
                echo "\t\t<li><a href=\"#tabs-{$tabnr}\" data-toggle=\"tab\"><?php echo __d('{$backendPluginNameUnderscored}', '" . Inflector::humanize(Inflector::underscore(Inflector::pluralize($alias))) . "');?></a></li>\n";
            }
        endforeach;
    endif;
    if (!empty($associations['hasAndBelongsToMany'])) :
        foreach ($associations['hasAndBelongsToMany'] as $alias => $details):
            if(array_search($alias, $hiddenTabs) === false) {
                ++$tabnr;
                echo "\t\t<li><a href=\"#tabs-{$tabnr}\" data-toggle=\"tab\"><?php echo __d('{$backendPluginNameUnderscored}', '" . Inflector::humanize(Inflector::underscore(Inflector::pluralize($alias))) . "');?></a></li>\n";
            }
        endforeach;
    endif;
?>
</ul>
<div class="tab-content">
    <div id="tabs-1" class="tab-pane active">
        <div class="<?php echo $pluralVar;?> view container-fluid">
            <div class="row-fluid">
                <div class="span10">
                    <dl class="dl-horizontal">
                    <?php
                    $configHiddenFields = Configure::read('admin.console.views.view.hidden_fields');
                    if(empty($configHiddenFields)) $configHiddenFields = array('all' => array());
                    $hiddenFields = array();
                    if(!empty($configHiddenFields['all'])) $hiddenFields = $configHiddenFields['all'];
                    if(!empty($configHiddenFields[$modelClass])) $hiddenFields = array_merge($hiddenFields, $configHiddenFields[$modelClass]);
                    foreach ($schema as $field => $properties) {
                        $isKey = false;
                        if(array_search($field, $hiddenFields) === false) {
                            if (!empty($associations['belongsTo'])) {
                                foreach ($associations['belongsTo'] as $alias => $details) {
                                    if ($field === $details['foreignKey']) {
                                        $isKey = true;
                                        $associationControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
                                        $associationControllerPath = $details['controller'];
                                        echo "\t\t\t\t<dt><?php echo __d('{$backendPluginNameUnderscored}', '" . Inflector::humanize(Inflector::underscore($alias)) . "'); ?></dt>\n";

                                        $actions = array('index', 'view', 'add', 'edit', 'delete');
                                        $configDisabledActions = Configure::read('admin.console.models.disabledActions');
                                        $configDisabledActions = (!empty($configDisabledActions[$details['className']])) ? $configDisabledActions[$details['className']] : array();
                                        $actions = array_diff($actions, $configDisabledActions);

                                        if(array_search('view', $actions) !== false){
                                            echo "\t\t<dd>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['toString'], array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t\t&nbsp;\n\t\t</dd>\n";
                                        } else {
                                            echo "\t\t<dd>\n\t\t\t<?php echo \${$singularVar}['{$alias}']['toString']; ?>\n\t\t\t&nbsp;\n\t\t</dd>\n";
                                        }
                                        break;
                                    }
                                }
                            }
                            if ($isKey !== true) {
                                echo "\t\t\t\t<dt><?php echo __d('{$backendPluginNameUnderscored}', '" . Inflector::humanize($field) . "'); ?></dt>\n";
                                echo "\t\t\t\t<dd>\n\t\t\t";
                                $fieldType = $properties['type'];
                                switch($fieldType) {
                                    case 'boolean':
                                        echo "<i class=\"icon-<?php echo (empty(\${$singularVar}['{$modelClass}']['{$field}'])) ? 'remove' : 'ok';?>\"></i>";
                                        break;
                                    default:
                                        echo "<?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n";
                                        break;
                                }
                                echo "\t\t\t&nbsp;\n\t\t</dd>\n";
                            }
                        }
                    }

                    ?>
                    </dl>
                </div>
                <?php
                $actions = array('index', 'view', 'add', 'edit', 'delete');
                $configDisabledActions = Configure::read('admin.console.models.disabledActions');
                $configDisabledActions = (!empty($configDisabledActions[$modelClass])) ? $configDisabledActions[$modelClass] : array();
                $actions = array_diff($actions, $configDisabledActions);

                $minHeight = sizeof($actions) * 26 + 30;
                ?>
                <div class="actions span2" style="min-height: <?php echo $minHeight;?>px;">
                    <div class="btn-group pull-right">
                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                            <?php echo "<?php echo __d('{$backendPluginNameUnderscored}', 'Actions');?>";?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu pull-right">
                <?php
                    if(array_search('edit', $actions) !== false) echo "\t\t\t\t<li><?php echo \$this->Html->link(__d('{$backendPluginNameUnderscored}', 'Edit " . $singularHumanName ."'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'], '?' => array('redirect' => \$this->Html->url(array('action' => 'index'))))); ?> </li>\n";
                    if(array_search('delete', $actions) !== false) echo "\t\t\t\t<li><?php echo \$this->Form->postLink(__d('{$backendPluginNameUnderscored}', 'Delete " . $singularHumanName . "'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}'], '?' => array('redirect' => \$this->Html->url(array('action' => 'index')))), null, __d('{$backendPluginNameUnderscored}', 'Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> </li>\n";
                    if(array_search('add', $actions) !== false) echo "\t\t\t\t<li><?php echo \$this->Html->link(__d('{$backendPluginNameUnderscored}', 'New " . $singularHumanName . "'), array('action' => 'add')); ?> </li>\n";
                    if(array_search('index', $actions) !== false) echo "\t\t\t\t<li><?php echo \$this->Html->link(__d('{$backendPluginNameUnderscored}', 'List " . $pluralHumanName . "'), array('action' => 'index')); ?> </li>\n";
                ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    $tabnr = 1;
if($modelObj->Behaviors->loaded('Tree')) {
    $actions = array('index', 'view', 'add', 'edit', 'delete');
    $configDisabledActions = Configure::read('admin.console.models.disabledActions');
    $configDisabledActions = (!empty($configDisabledActions[$modelClass])) ? $configDisabledActions[$modelClass] : array();
    $actions = array_diff($actions, $configDisabledActions);
?>
    <div id="tabs-<?php echo ++$tabnr; ?>" class="tab-pane">
        <div class="related">
            <h3><?php echo "<?php echo __d('{$backendPluginNameUnderscored}', 'Child " . $pluralHumanName . "');?>";?></h3>
            <div class="<?php echo $pluralVar;?> table">
            <?php echo "<?php echo \$this->element('../{$backendPluginName}{$controllerName}/table', array('{$pluralVar}TableURL' => \${$pluralVar}TableURL, '{$pluralVar}' => \${$pluralVar}, '{$pluralVar}TableModelAlias' => '{$modelClass}', 'redirectUrl' => '/' . \$this->request->url . '#tabs-' . $tabnr));?>\n"; ?>
            </div>
            <div class="actions">
                <?php if(array_search('add', $actions) !== false) echo "<?php echo \$this->Html->link(__d('{$backendPluginNameUnderscored}', 'New " . $singularHumanName . "'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$controllerPath}', 'action' => 'add', 'parent_id' => \${$singularVar}['{$modelClass}']['{$primaryKey}'], '?' => array('redirect' => '/' . \$this->request->url . '#tabs-' . $tabnr)), array('class' => 'btn btn-primary'));?>";?> </li>
            </div>
        </div>
    </div>
<?php
}
if (!empty($associations['hasOne'])) :
    foreach ($associations['hasOne'] as $alias => $details):
        if(array_search($alias, $hiddenTabs) === false) {
            $associationControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
            $associationControllerPath = $details['controller'];
    ?>
    <div id="tabs-<?php echo ++$tabnr; ?>" class="tab-pane">
        <div class="related">
            <h3><?php echo "<?php echo __d('{$backendPluginNameUnderscored}', 'Related " . Inflector::humanize($details['controller']) . "');?>";?></h3>
        <?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])):?>\n";?>
            <dl>
        <?php
            foreach ($details['fields'] as $field) {
                echo "\t\t<dt><?php echo __d('{$backendPluginNameUnderscored}', '" . Inflector::humanize($field) . "');?></dt>\n";
                echo "\t\t<dd>\n\t<?php echo \${$singularVar}['{$alias}']['{$field}'];?>\n&nbsp;</dd>\n";
            }
        ?>
            </dl>
        <?php echo "<?php endif; ?>\n";?>
            <div class="actions">
                <?php echo "<?php echo \$this->Html->link(__d('{$backendPluginNameUnderscored}', 'Edit " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'edit', \${$singularVar}['{$alias}']['{$details['primaryKey']}']), array('class' => 'btn')); ?></li>\n";?>
            </div>
        </div>
    </div>
    <?php
        }
    endforeach;
endif;

if(empty($associations['hasMany'])) $associations['hasMany'] = array();

foreach ($associations['hasMany'] as $alias => $details):
    if(array_search($alias, $hiddenTabs) === false) {
        $otherSingularVar = Inflector::variable(Inflector::singularize($details['controller']));
        $otherPluralVar = Inflector::pluralize($otherSingularVar);
        $otherControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
        $otherControllerPath = $details['controller'];

        $otherSingularHumanName = Inflector::humanize(Inflector::underscore($alias));
        $otherPluralHumanName = Inflector::humanize(Inflector::underscore(Inflector::pluralize($alias)));
        if(!empty($configAliases[$alias])) {
            $otherSingularHumanName = Inflector::humanize(Inflector::underscore($configAliases[$alias]));
            $otherPluralHumanName = Inflector::humanize(Inflector::underscore(Inflector::pluralize($configAliases[$alias])));
        }

        $actions = array('index', 'view', 'add', 'edit', 'delete');
        $configDisabledActions = Configure::read('admin.console.models.disabledActions');
        $configDisabledActions = (!empty($configDisabledActions[$alias])) ? $configDisabledActions[$alias] : array();
        $actions = array_diff($actions, $configDisabledActions);
        ?>
    <div id="tabs-<?php echo ++$tabnr; ?>" class="tab-pane">
        <div class="related">
            <h3><?php echo "<?php echo __d('{$backendPluginNameUnderscored}', '" . $otherPluralHumanName . "');?>";?></h3>
            <div class="<?php echo $otherPluralVar;?> table">
            <?php echo "<?php echo \$this->element('../{$backendPluginName}{$otherControllerName}/table', array('{$otherPluralVar}TableURL' => \${$otherPluralVar}TableURL, '{$otherPluralVar}' => \${$otherPluralVar}, '{$otherPluralVar}TableModelAlias' => '{$alias}', 'redirectUrl' => '/' . \$this->request->url . '#tabs-' . $tabnr));?>\n"; ?>
            </div>
            <div class="actions">
                <?php if(array_search('add', $actions) !== false) echo "<?php echo \$this->Html->link(__d('{$backendPluginNameUnderscored}', 'New " . $otherSingularHumanName . "'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$otherControllerPath}', 'action' => 'add', '{$details['foreignKey']}' => \${$singularVar}['{$modelClass}']['{$primaryKey}'], '?' => array('redirect' => '/' . \$this->request->url . '#tabs-' . $tabnr)), array('class' => 'btn btn-primary'));?>";?> </li>
            </div>
        </div>
    </div>
<?php
    }
endforeach;

//HABTM
if(empty($associations['hasAndBelongsToMany'])) $associations['hasAndBelongsToMany'] = array();

$i = 0;
foreach ($associations['hasAndBelongsToMany'] as $alias => $details):
    if(array_search($alias, $hiddenTabs) === false) {
        $otherSingularVar = Inflector::variable($alias);
        $otherPluralHumanName = Inflector::humanize($details['controller']);
        $associationControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
        $associationControllerPath = $details['controller'];
    ?>
    <div id="tabs-<?php echo ++$tabnr; ?>" class="tab-pane">
        <div class="related">
            <h3><?php echo "<?php echo __d('{$backendPluginNameUnderscored}', 'Related " . $otherPluralHumanName . "');?>";?></h3>
            <?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])):?>\n";?>
            <table cellpadding = "0" cellspacing = "0">
            <tr>
        <?php
                    foreach ($details['fields'] as $field) {
                        echo "\t\t<th><?php echo __d('{$backendPluginNameUnderscored}', '" . Inflector::humanize($field) . "'); ?></th>\n";
                    }
        ?>
                <th class="actions"><?php echo "<?php echo __d('{$backendPluginNameUnderscored}', 'Actions');?>";?></th>
            </tr>
        <?php
        echo "\t<?php
                \$i = 0;
                foreach (\${$singularVar}['{$alias}'] as \${$otherSingularVar}): ?>\n";
                echo "\t\t<tr>\n";
                    foreach ($details['fields'] as $field) {
                        echo "\t\t\t<td><?php echo \${$otherSingularVar}['{$field}'];?></td>\n";
                    }

                    echo "\t\t\t<td class=\"actions\">\n";
                    echo "\t\t\t\t<?php echo \$this->Html->link(__d('{$backendPluginNameUnderscored}', 'View'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'view', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-info btn-mini')); ?>\n";
                    echo "\t\t\t\t<?php echo \$this->Html->link(__d('{$backendPluginNameUnderscored}', 'Edit'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'edit', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-mini')); ?>\n";
                    echo "\t\t\t\t<?php echo \$this->Form->postLink(__d('{$backendPluginNameUnderscored}', 'Delete'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'delete', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-danger btn-mini'), __d('{$backendPluginNameUnderscored}', 'Are you sure you want to delete # %s?', \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
                    echo "\t\t\t</td>\n";
                echo "\t\t</tr>\n";

        echo "\t<?php endforeach; ?>\n";
        ?>
            </table>
        <?php echo "<?php endif; ?>\n\n";?>
            <div class="actions">
                <?php echo "<?php echo \$this->Html->link(__d('{$backendPluginNameUnderscored}', 'New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'add'), array('class' => 'btn btn-primary'));?>";?> </li>
            </div>
        </div>
    </div>
<?php
    }
    endforeach;
?>
</div>
<script type="text/javascript">
(function(){
    var $tab = $('.nav.nav-tabs li a[href=' + window.location.hash + ']:first');
    if($tab.length == 0) {
        $tab = $('.nav.nav-tabs li a:first');
    }
    $tab.tab('show');
})();
</script>
