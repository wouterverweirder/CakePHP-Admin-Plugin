<h2><?php echo "<?php echo __('{$pluralHumanName}');?>";?></h2>

<?php
    $configSearchableFields = Configure::read('admin.console.views.index.searchable_fields');
    if(!empty($configSearchableFields[$modelClass])):
?>
        <div class="<?php echo $pluralVar;?> index search">
            <?php echo "<?php echo \$this->ExtendedForm->create('$modelClass', array('type' => 'get'));?>\n";?>
                <fieldset>
                    <legend><?php echo "<?php echo __('Search $singularHumanName'); ?>";?></legend>

                    <?php foreach($configSearchableFields[$modelClass] as $searchableFieldName => $searchableFieldLabel): ?>
                    <?php echo "<?php echo \$this->ExtendedForm->input('{$modelClass}-{$searchableFieldName}', array('label' => '{$searchableFieldLabel}'));?>\n";?>
                    <?php endforeach; ?>

                </fieldset>
            <?php echo "<?php echo \$this->ExtendedForm->end(__('Search $pluralHumanName'));?>";?>
        </div>
<?php endif;?>

        <div class="<?php echo $pluralVar;?> index table">
            <?php echo "<?php echo \$this->element('../{$backendPluginName}{$controllerName}/table');?>\n"; ?>
        </div>

<?php
$actions = array('index', 'view', 'add', 'edit', 'delete');
$configDisabledActions = Configure::read('admin.console.models.disabledActions');
$configDisabledActions = (!empty($configDisabledActions[$modelClass])) ? $configDisabledActions[$modelClass] : array();
$actions = array_diff($actions, $configDisabledActions);
if(array_search('add', $actions) !== false):
?>
        <div class="actions">

            <h3><?php echo "<?php echo __('Actions'); ?>"; ?></h3>
            <?php echo "<?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('action' => 'add'), array('class' => 'btn btn-primary')); ?>";?>
        </div>
<?php endif;?>