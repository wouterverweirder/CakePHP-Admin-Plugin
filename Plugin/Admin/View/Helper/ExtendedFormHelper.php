<?php
App::uses('FormHelper', 'View/Helper');

class ExtendedFormHelper extends FormHelper
{

    public function text($fieldName, $options = array()) {
        $options = $this->_initInputField($fieldName, $options);
        $modelKey = $this->model();
        $fieldKey = $this->field();
        if(empty($options['value']))
        {
            //get value?
            if(!empty($this->request->query[$fieldKey])) $options['value'] = $this->request->query[$fieldKey];
        }

        return parent::text($fieldName, $options);
    }

    public function textarea($fieldName, $options = array()) {
        $result = parent::textarea($fieldName, $options);
        $options = $this->_initInputField($fieldName, $options);
        //add ckeditor
        $result .= '<script type="text/javascript">CKEDITOR.replace(\''.$options['id'].'\', {toolbar: \'Basic\', forcePasteAsPlainText: true});</script>';
        //die($options['id']);
        return $result;
    }

    public function dateTime($fieldName, $dateFormat = 'DMY', $timeFormat = '12', $attributes = array()) {
        $attributes['empty'] = '-';
        if (empty($attributes['value'])) {
            $attributes = $this->value($attributes, $fieldName);
        }
        if($attributes['value'] == '1970-01-01 01:00:00')
        {
            $attributes['value'] = '1970-00-00 00:00:00';
        }
        $result = parent::dateTime($fieldName, $dateFormat, $timeFormat, $attributes);
        return $result;
    }

    public function image($fieldName, $options = array()) {
        $options['file_type'] = 'image';
        $result = $this->file($fieldName, $options);
        return $result;
    }

    public function file($fieldName, $options = array()) {
        //remove file type param
        $fileType = 'file';
        if(!empty($options['file_type'])) {
            $fileType = $options['file_type'];
            unset($options['file_type']);
        }
        $resourceType = 'Files';
        switch($fileType) {
            case 'image':
                $resourceType = 'Images';
                break;
        }
        $result = parent::text($fieldName, $options);
        $options = $this->_initInputField($fieldName, $options);
        //add CKEditor file browser Support
        $result .= "<input type=\"button\" value=\"Browse Server\" id=\"{$options['id']}BrowseServerButton\" />
        <script type=\"text/javascript\">
        (function(){
            function setFileField(fileUrl, data) {
                console.log(fileUrl);
                document.getElementById('{$options['id']}').value = fileUrl.substr('".Router::url('/')."files/{$fileType}/'.length + 1);
            }
            function browseServer() {
                var finder = new CKFinder();
                finder.resourceType = '{$resourceType}';
                finder.selectActionFunction = setFileField;
                finder.popup();
            }
            $('#{$options['id']}BrowseServerButton').bind('click', browseServer);
        })();
        </script>";
        return $result;
    }
}
