<?php
    if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR . 'additional_functions.ctp' )) {
        require __DIR__ . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR . 'additional_functions.ctp';
        echo "\n";
    }

    $configReadonlyModels = Configure::read('admin.console.models.readonly');
    if(array_search($currentModelName, $configReadonlyModels) === false) {
        $actions = array('index', 'view', 'add', 'edit', 'delete');
    } else {
        $actions = array('index', 'view');
    }
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