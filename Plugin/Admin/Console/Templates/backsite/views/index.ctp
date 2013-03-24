<h2><?php echo "<?php echo __('{$pluralHumanName}');?>";?></h2>

<div id="tabs">
     <ul>
         <li><a href="#tabs-1"><?php echo "<?php  echo __('{$pluralHumanName} List');?>";?></a></li>
     </ul>
    <div id="tabs-1">
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
        <div class="actions">
            <h3><?php echo "<?php echo __('Actions'); ?>"; ?></h3>
            <ul>
                <li><?php echo "<?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('action' => 'add')); ?>";?></li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $( "#tabs" ).tabs();
    });
</script>