<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 */
class User extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $toString = 'email';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'email' => array(
			'email-email' => array(
				'required' => true,
				'rule' => array('email'),
			),
			'email-unique' => array(
				'rule' => array('isUnique'),
				'message' => 'Email is already in use',
			),
		),
		'password' => array(
			'notempty' => array(
				'required' => true,
				'on' => 'create',
				'rule' => array('notempty'),
			),
		),
		'confirm_password' => array(
			'equaltofield-update' => array(
				'rule' => array('equaltofield', 'new_password'),
				'message' => 'Passwords do not match',
				'on' => 'update', // Limit validation to 'create' or 'update' operations
			),
			'equaltofield-create' => array(
				'rule' => array('equaltofield', 'password'),
				'message' => 'Passwords do not match',
				'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public function beforeSave($options = array()) {
		if(!empty($this->data['User']['id'])) {
			if(!empty($this->data['User']['new_password'])) {
				$this->data['User']['password'] = Security::hash($this->data['User']['new_password'], null, true);
			}
		} else {
			$this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
		}
		return true;
	}
}
