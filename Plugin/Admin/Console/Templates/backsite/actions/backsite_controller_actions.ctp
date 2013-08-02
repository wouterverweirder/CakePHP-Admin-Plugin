<?php
    if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR . 'additional_functions.ctp' )) {
        require __DIR__ . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR . 'additional_functions.ctp';
        echo "\n";
    }

    $actions = array('index', 'view', 'add', 'edit', 'delete');
    $configDisabledActions = Configure::read('admin.console.models.disabledActions');
    $configDisabledActions = (!empty($configDisabledActions[$currentModelName])) ? $configDisabledActions[$currentModelName] : array();
    $actions = array_diff($actions, $configDisabledActions);
    
    foreach ($actions as $action) {
        $compact = array();
        if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR . $action . '.ctp')) {
            require __DIR__ . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR . $action . '.ctp';
        } else {
            require __DIR__ . DIRECTORY_SEPARATOR . $action . '.ctp';
        }
        echo "\n";
    }
?>