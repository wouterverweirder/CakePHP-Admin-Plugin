<h2><?php echo "<?php  echo __('{$singularHumanName}') . ': ' . \${$singularVar}['{$modelClass}']['toString'];?>";?></h2>
<ul class="nav nav-tabs">
    <li class="active"><a href="#tabs-1" data-toggle="tab"><?php echo "<?php  echo __('Details');?>";?></a></li>
<?php
    //create tabs for associated models
    $tabnr = 1;
    if (!empty($associations['hasOne'])) :
        foreach ($associations['hasOne'] as $alias => $details):
            ++$tabnr;
            echo "\t\t<li><a href=\"#tabs-{$tabnr}\" data-toggle=\"tab\"><?php echo __('" . Inflector::humanize(Inflector::underscore(Inflector::pluralize($alias))) . "');?></a></li>\n";
        endforeach;
    endif;
    if (!empty($associations['hasMany'])) :
        foreach ($associations['hasMany'] as $alias => $details):
            ++$tabnr;
            echo "\t\t<li><a href=\"#tabs-{$tabnr}\" data-toggle=\"tab\"><?php echo __('" . Inflector::humanize(Inflector::underscore(Inflector::pluralize($alias))) . "');?></a></li>\n";
        endforeach;
    endif;
    if (!empty($associations['hasAndBelongsToMany'])) :
        foreach ($associations['hasAndBelongsToMany'] as $alias => $details):
            ++$tabnr;
            echo "\t\t<li><a href=\"#tabs-{$tabnr}\" data-toggle=\"tab\"><?php echo __('" . Inflector::humanize(Inflector::underscore(Inflector::pluralize($alias))) . "');?></a></li>\n";
        endforeach;
    endif;
?>
</ul>
<div class="tab-content">
    <div id="tabs-1" class="tab-pane active">
        <div class="<?php echo $pluralVar;?> view container-fluid">
            <div class="row-fluid">
                <div class="span9">
                    <dl>
                    <?php
                    $configHiddenFields = Configure::read('admin.console.views.view.hidden_fields');
                    if(empty($configHiddenFields)) $configHiddenFields = array('all' => array());
                    $hiddenFields = array();
                    if(!empty($configHiddenFields['all'])) $hiddenFields = $configHiddenFields['all'];
                    if(!empty($configHiddenFields[$modelClass])) $hiddenFields = array_merge($hiddenFields, $configHiddenFields[$modelClass]);
                    foreach ($fields as $field) {
                        $isKey = false;
                        if(array_search($field, $hiddenFields) === false) {
                            if (!empty($associations['belongsTo'])) {
                                foreach ($associations['belongsTo'] as $alias => $details) {
                                    if ($field === $details['foreignKey']) {
                                        $isKey = true;
                                        $associationControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
                                        $associationControllerPath = $details['controller'];
                                        echo "\t\t\t\t<dt><?php echo __('" . Inflector::humanize(Inflector::underscore($alias)) . "'); ?></dt>\n";
                                        echo "\t\t<dd>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['toString'], array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t\t&nbsp;\n\t\t</dd>\n";
                                        break;
                                    }
                                }
                            }
                            if ($isKey !== true) {
                                echo "\t\t\t\t<dt><?php echo __('" . Inflector::humanize($field) . "'); ?></dt>\n";
                                echo "\t\t\t\t<dd>\n\t\t\t";
                                switch($field) {
                                    case 'image1':
                                    case 'image2':
                                        echo "<?php if(!empty(\${$singularVar}['{$modelClass}']['{$field}'])) echo \$this->Html->image('/files/images/' . \${$singularVar}['{$modelClass}']['{$field}']); ?>\n";
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
                <div class="actions span2">
                    <div class="btn-group">
                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                            Actions
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                <?php

                    $actions = array('index', 'view', 'add', 'edit', 'delete');
                    $configDisabledActions = Configure::read('admin.console.models.disabledActions');
                    $configDisabledActions = (!empty($configDisabledActions[$modelClass])) ? $configDisabledActions[$modelClass] : array();
                    $actions = array_diff($actions, $configDisabledActions);

                    if(array_search('edit', $actions) !== false) echo "\t\t\t\t<li><?php echo \$this->Html->link(__('Edit " . $singularHumanName ."'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> </li>\n";
                    if(array_search('delete', $actions) !== false) echo "\t\t\t\t<li><?php echo \$this->Form->postLink(__('Delete " . $singularHumanName . "'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}'], '?' => array('redirect' => \$this->Html->url(array('action' => 'index')))), null, __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> </li>\n";
                    if(array_search('add', $actions) !== false) echo "\t\t\t\t<li><?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('action' => 'add')); ?> </li>\n";
                    if(array_search('index', $actions) !== false) echo "\t\t\t\t<li><?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('action' => 'index')); ?> </li>\n";
                ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    $tabnr = 1;
if (!empty($associations['hasOne'])) :
    foreach ($associations['hasOne'] as $alias => $details):
        $associationControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
        $associationControllerPath = $details['controller'];
    ?>
    <div id="tabs-<?php echo ++$tabnr; ?>" class="tab-pane">
        <div class="related">
            <h3><?php echo "<?php echo __('Related " . Inflector::humanize($details['controller']) . "');?>";?></h3>
        <?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])):?>\n";?>
            <dl>
        <?php
            foreach ($details['fields'] as $field) {
                echo "\t\t<dt><?php echo __('" . Inflector::humanize($field) . "');?></dt>\n";
                echo "\t\t<dd>\n\t<?php echo \${$singularVar}['{$alias}']['{$field}'];?>\n&nbsp;</dd>\n";
            }
        ?>
            </dl>
        <?php echo "<?php endif; ?>\n";?>
            <div class="actions">
                <?php echo "<?php echo \$this->Html->link(__('Edit " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'edit', \${$singularVar}['{$alias}']['{$details['primaryKey']}']), array('class' => 'btn')); ?></li>\n";?>
            </div>
        </div>
    </div>
    <?php
    endforeach;
endif;

if(empty($associations['hasMany'])) $associations['hasMany'] = array();

foreach ($associations['hasMany'] as $alias => $details):

    $aliasSingularVar = Inflector::variable($alias);
    $aliasPluralVar = Inflector::pluralize($aliasSingularVar);
    $aliasPluralHumanName = Inflector::humanize(Inflector::pluralize(Inflector::underscore($aliasSingularVar)));

    $otherSingularVar = Inflector::variable(Inflector::singularize($details['controller']));
    $otherPluralVar = Inflector::pluralize($otherSingularVar);
    $otherControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
    $otherControllerPath = $details['controller'];

    $actions = array('index', 'view', 'add', 'edit', 'delete');
    $configDisabledActions = Configure::read('admin.console.models.disabledActions');
    $configDisabledActions = (!empty($configDisabledActions[$alias])) ? $configDisabledActions[$alias] : array();
    $actions = array_diff($actions, $configDisabledActions);
        ?>
    <div id="tabs-<?php echo ++$tabnr; ?>" class="tab-pane">
        <div class="related">
            <h3><?php echo "<?php echo __('Related " . $aliasPluralHumanName . "');?>";?></h3>
            <div class="<?php echo $otherPluralVar;?> table">
            <?php echo "<?php echo \$this->element('../{$backendPluginName}{$otherControllerName}/table', array('{$otherPluralVar}TableURL' => \${$otherPluralVar}TableURL, '{$otherPluralVar}' => \${$otherPluralVar}, '{$otherPluralVar}TableModelAlias' => '{$alias}', 'redirectUrl' => '/' . \$this->request->url . '#tabs-' . $tabnr));?>\n"; ?>
            </div>
            <div class="actions">
                <?php echo "<?php echo \$this->Html->link(__('New " . $otherSingularHumanName . "'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$otherControllerPath}', 'action' => 'add', '{$details['foreignKey']}' => \${$singularVar}['{$modelClass}']['{$primaryKey}'], '?' => array('redirect' => '/' . \$this->request->url . '#tabs-' . $tabnr)), array('class' => 'btn btn-primary'));?>";?> </li>
            </div>
        </div>
    </div>
<?php
endforeach;

//HABTM
if(empty($associations['hasAndBelongsToMany'])) $associations['hasAndBelongsToMany'] = array();

$i = 0;
foreach ($associations['hasAndBelongsToMany'] as $alias => $details):
    $otherSingularVar = Inflector::variable($alias);
    $otherPluralHumanName = Inflector::humanize($details['controller']);
    $associationControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
    $associationControllerPath = $details['controller'];
    ?>
    <div id="tabs-<?php echo ++$tabnr; ?>" class="tab-pane">
        <div class="related">
            <h3><?php echo "<?php echo __('Related " . $otherPluralHumanName . "');?>";?></h3>
            <?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])):?>\n";?>
            <table cellpadding = "0" cellspacing = "0">
            <tr>
        <?php
                    foreach ($details['fields'] as $field) {
                        echo "\t\t<th><?php echo __('" . Inflector::humanize($field) . "'); ?></th>\n";
                    }
        ?>
                <th class="actions"><?php echo "<?php echo __('Actions');?>";?></th>
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
                    echo "\t\t\t\t<?php echo \$this->Html->link(__('View'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'view', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-info btn-mini')); ?>\n";
                    echo "\t\t\t\t<?php echo \$this->Html->link(__('Edit'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'edit', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-mini')); ?>\n";
                    echo "\t\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'delete', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-danger btn-mini'), __('Are you sure you want to delete # %s?', \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
                    echo "\t\t\t</td>\n";
                echo "\t\t</tr>\n";

        echo "\t<?php endforeach; ?>\n";
        ?>
            </table>
        <?php echo "<?php endif; ?>\n\n";?>
            <div class="actions">
                <?php echo "<?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'add'), array('class' => 'btn btn-primary'));?>";?> </li>
            </div>
        </div>
    </div>
<?php endforeach;?>
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