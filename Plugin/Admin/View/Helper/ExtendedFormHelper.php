<?php
App::uses('FormHelper', 'View/Helper');

class ExtendedFormHelper extends FormHelper
{

    public $helpers = array('Html', 'Js');

    public function input($fieldName, $options = array()) {
        $options = $this->_bootstrapOptions($fieldName, $options);
        $magicOptions = $this->_magicOptions($options);
        if($magicOptions['type'] == 'number') {
            $value = $this->value($magicOptions);
            if(empty($value['value'])) {
                $options['value'] = 0;
            }
        }
        return parent::input($fieldName, $options);
    }

    public function text($fieldName, $options = array()) {
        $options = $this->_initInputField($fieldName, $options);
        $modelKey = $this->model();
        $fieldKey = $this->field();
        if(empty($options['value']))
        {
            if(!empty($this->request->query[$fieldKey])) $options['value'] = $this->request->query[$fieldKey];
        }
        return parent::text($fieldName, $options);
    }

    public function textarea($fieldName, $options = array()) {
        $options = $this->_initInputField($fieldName, $options);
        if(!isset($options['rte']) || !empty($options['rte'])) {
            $urlPrefix = Router::url('/admin-plugin/js/');
            $ckoptions = array(
                'toolbar' => array(
                    array('Source', '-', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-', 'Image', '-', 'Table')
                ),
                'extraAllowedContent' => array(
                    'article' => array(
                        'classes' => 'table-yellow'
                    )
                ),
                'forcePasteAsPlainText' => true,
                'filebrowserBrowseUrl' => $urlPrefix . 'ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => $urlPrefix . 'ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => $urlPrefix . 'ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl' => $urlPrefix . 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => $urlPrefix . 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => $urlPrefix . 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            );
            $options['data-ckeditor'] = 1;
            $options['data-ckeditor-config'] = json_encode($ckoptions);
        }
        $result = parent::textarea($fieldName, $options);
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
        $options['data-fileupload'] = 1;
        $fileuploadConfig = array(
            'uploadUrl' => Router::url('/admin-plugin/jquery-fileupload/upload.php') . '?type=' . $fileType,
            'httpRoot' => Router::url('/'),
            'relativeUploadFolder' => 'files/' . $fileType . '/',
            'fileType' => $fileType
        );
        $options['data-fileupload-config'] = json_encode($fileuploadConfig);

        if(!isset($options['browse_server']) || !empty($options['browse_server'])) {
            $options['data-ckfinder'] = 1;
            $options['data-ckfinder-config'] = json_encode(array(
                'httpRoot' => Router::url('/'),
                'relativeUploadFolder' => 'files/' . $fileType . '/',
                'resourceType' => $resourceType
            ));
        }
        $result = parent::text($fieldName, $options);
        return $result;
    }

    private function _bootstrapOptions($fieldName, $options) {
        $initializedOptions = $this->_initInputField($fieldName, $options);
        if(!empty($options['label']) && !is_array($options['label'])) {
            $options['label'] = array('text' => $options['label']);
        }
        if(isset($options['label']) && is_array($options['label'])) {
            $options['label']['class'] = 'control-label';
        }
        if(empty($options['div'])) {
            $options['div'] = array();
        }
        if(!isset($options['div']['class'])) {
            $options['div']['class'] = 'control-group ' . Inflector::underscore($initializedOptions['id']);
        }
        if(empty($options['error'])) {
            $options['error'] = array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'));
        }
        if(empty($options['between'])) {
            $options['between'] = '<div class="controls">';
        }
        if(empty($options['after'])) {
            $options['after'] = '</div>';
        }
        if(isset($options['type']) && $options['type'] == 'checkbox') {
            $options['div'] = array('class' => 'checkbox ' . Inflector::underscore($initializedOptions['id']));
            $options['label']['class'] = '';
            $options['format'] = array('before', 'between', 'input', 'label' ,'error', 'after');
        } else {
            $options['format'] = array('before', 'label', 'between', 'input' ,'error', 'after');
        }
        return $options;
    }
}
