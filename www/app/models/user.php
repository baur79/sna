<?php

class User extends AppModel {
	
	var $name = 'User';
	
	var $validate = array(); // See __construct(); It is there to enable use of__()
	
	var $displayField = 'nickname';
	
	var $hasMany = array(
		'UserOption' => array(
			'className' => 'UserOption',
			'foreignKey' => 'user_id',
			'dependent' => true,
		),
		'Message' => array(
			'className' => 'Message',
			'foreignKey' => 'user_id',
			'dependent' => true,
		),
		'Shout' => array(
			'className' => 'Shout',
			'foreignKey' => 'user_id',
			'dependent' => true,
		),
	);
	
	function __construct() {
		$this->validate = array( 
			'username' => array(
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __('This username is already in use.', true),
				),
				'alphaNumeric' => array(
					'rule' => 'alphaNumeric',
					'message' => __('Use letters from A to Z or numbers from 0 to 9 only.', true),
				),
				'minLength' => array(
					'rule' => array('minLength', '3'),
					'message' => __('Minimum length of 3 characters.', true),
				),
			),
			'password' => array(
				'alphaNumeric' => array(
					'rule' => 'alphaNumeric',
					'message' => __('Use letters from A to Z or numbers from 0 to 9 only.', true),
				),
				'minLength' => array(
					'rule' => array('minLength', '3'),
					'message' => __('Minimum length of 3 characters.', true),
				),
				'validateEqualData' => array(
					'rule' => array(
						'validateEqualData',
						__('You may have misstyped your Password or your Password Confirmation is wrong.', true),
						'password_confirmation',
					),
					'message' => __('Your Password does not match with your Password Confirmation.', true),
				),
			),
			'nickname' => array(
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __('This nickname is already in use.', true),
				),
				'alphaNumeric' => array(
					'rule' => 'alphaNumeric',
					'message' => __('Nickname must only contain letters and numbers.', true),
				),
				'minLength' => array(
					'rule' => array('minLength', '3'),
					'message' => __('Minimum length of 3 characters.', true),
				),
			),
			'email' => array(
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __('This email address is already in use.', true),
				),
				'email' => array(
					'rule' => 'email',
					'message' => __('Must be a valid email address.', true),
				),
				'validateEqualData' => array(
					'rule' => array(
						'validateEqualData',
						__('You may have misstyped your Email or your Email Confirmation is wrong.', true),
						'email_confirmation',
					),
					'message' => __('Your Email does not match with your Email Confirmation.', true),
				),
			),
			'has_accepted_tos' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __('You may only accept or deny the Terms of Service.', true),
				),
				'validateBooleanValue' => array(
					'rule' => array('validateBooleanValue', 'has_accepted_tos', 1),
					'on' => 'create',
					'message' => __('You must accept the Terms of Service on User Account registration.', true),
				),
			),
			'last_login' => array(
				'validateDatetime' => array(
					'rule' => array('validateDatetime', 'last_login'),
					'message' => __('Last Login must be a valid datetime (yyyy-mm-dd hh:mm:ss).', true),
				),
			),
		);
		$this->passwordInClearText = null;
		parent::__construct();
	}
	
	function afterFind(&$results) {
		foreach ($results as $index => $data) {
			if (!empty($results[$index][$this->alias]['nickname'])) {
				$results[$index][$this->alias]['nicename'] = $results[$index][$this->alias]['nickname'];
			}
			if (!empty($results[$index][$this->alias]['nickname'])
			&& !empty($results[$index][$this->alias]['username'])) {
				if ($results[$index][$this->alias]['nickname'] != $results[$index][$this->alias]['username']) {
					$results[$index][$this->alias]['nicename'] .= ':' . $results[$index][$this->alias]['username'];
				}
			}
		}
		return $results;
	}
	
	function beforeValidate() {
		// be nice to the user, starting and trailing whitespaces are ignored and removed before validation
		if (!empty($this->data['User']['email'])) {
			$this->data['User']['email'] = trim($this->data['User']['email']);
		}
		if (!empty($this->data['User']['email_confirmation'])) {
			$this->data['User']['email_confirmation'] = trim($this->data['User']['email_confirmation']);
		}
		if (!empty($this->data['User']['username'])) {
			$this->data['User']['username'] = trim($this->data['User']['username']);
		}
		if (!empty($this->data['User']['nickname'])) {
			$this->data['User']['nickname'] = trim($this->data['User']['nickname']);
		}
	}
	
	function beforeSave() {
		if ($this->id === false) { // on new records, clear out some fields, predefine some values
			$this->data[$this->alias]['is_hidden'] = 0;
			$this->data[$this->alias]['is_disabled'] = 0;
			$this->data[$this->alias]['is_deleted'] = 0;
			unset($this->data[$this->alias]['email_confirmation']);
			unset($this->data[$this->alias]['password_confirmation']);
			if (isset($this->data['User']['send_copy_via_email']) && $this->data['User']['send_copy_via_email'] == 1) {
				$this->passwordInClearText = $this->data[$this->alias]['password'];
			}
		}
		$this->hashPasswords(null, true);
		return true;
	}
	
	function afterSave($isCreated) {
		if ($isCreated === true) {
			$this->deactivate($this->passwordInClearText);
			$this->passwordInClearText = null;
			$this->updateLastLogin($this->read());
		}
	}
	
	function generateActivationKey($i = 0) {
		// Defensive loop stopper.
		$i++;
		if ($i > 10) {
			$this->log('Issue with User::generateActivationKey(). Failed at generating a valid key.', 'error');
			return false;
		} else {
			// Key looks like D7E9-F3E4-479A-838C
			$activationKey = substr(strtoupper(String::uuid()), 4, -13);
			if ($this->find('first', array('conditions' => array('activation_key' => $activationKey))) !== false) {
				$activationKey = $this->generateActivationKey($i);
			} else {
				return $activationKey;
			}
		}
	}
	
	function hashPasswords($data, $enforce = false) {
		// $this->log('User::hashPasswords()', 'debug');
		if ($enforce && isset($this->data[$this->alias]['password'])) {
			if (!empty($this->data[$this->alias]['password'])) {
				$this->data[$this->alias]['password'] =
					Security::hash($this->data[$this->alias]['password'], null, true);
			}
		}
		return $data;
	}
	
	function deactivate($passwordInClearText, $isNewUser = true) {
		$activationKey = $this->generateActivationKey();
		$data = $this->read();
		if ($activationKey === false) {
			$this->log('No valid Activation Key. Disabling User.', 'error');
			$this->setDisabled($data, 1);
		} else {
			$this->id = $data[$this->alias]['id'];
			$this->saveField('activation_key', $activationKey , true);
			$this->sendActivationEmail($data, $activationKey, $isNewUser, $passwordInClearText);
		}
	}
	
	function sendActivationEmail($data, $activationKey, $isNewUser, $passwordInClearText) {
		// ENH: SMS-Gateway
		App::import('Core', 'Controller');
		App::import('Component', 'Email');
		// We need this fake controller
		$Controller = new Controller();
		$Email = new EmailComponent(null);
		$Email->initialize($Controller);
		$domainName = env('SERVER_NAME');
		if (strpos($domainName, 'www.') === 0) {
			$domainName = substr($domainName, 4);
		}
		$Email->to = $data[$this->alias]['email'];
		$Email->subject = $domainName . ': ' . $data[$this->alias]['username'] . '/'
			. $data[$this->alias]['nickname'];
		$Email->from = 'noreply@' . $domainName;
		$Controller->set(array(
				'domainName' => $domainName,
				'serverName' => env('SERVER_NAME'),
				'title' => $Email->subject,
				'activationKey' => $activationKey
		));
		if ($isNewUser) {
			$Email->template = 'registration';
			$Email->subject .= ' - ' . __('User Account Activation', true);
				
		} else {
			$Email->template = 'activation';
			$Email->subject .= ' - ' . __('User Account Reactivation', true);
		}
		if ($passwordInClearText) {
			$Email->template .= '_details';
			$Email->subject .= ' ' . __('including User Account Details', true);
			$Controller->set(array(
					'nickname' => $data[$this->alias]['nickname'],
					'username' => $data[$this->alias]['username'],
					'password' => $passwordInClearText,
					'email' => $data[$this->alias]['email'],
				)
			);
		}
		$Email->send();
		unset($Email);
		unset($Controller);
	}
	
	function activate($data, $doSendEmail = true) {
		// TODO: treat $doSendEmail
		if (empty($data['User']['activation_key'])) {
			$this->invalidate('activation_key', __('Enter your Activation Key.', true));
			return false;
		}
		$data['User']['activation_key'] = strtoupper($data['User']['activation_key']);
		$data = $this->find('first', array(
				'fields' => array('User.id'),
				'conditions' => array('activation_key' => $data['User']['activation_key']),
				'recursive' => 0,
			)
		);
		if (!empty($data['User']['id'])) {
			$this->id = $data['User']['id'];
			if ($this->saveField('activation_key', null)) {
				return true;
			}
		} else {
			$this->invalidate('activation_key', __('The Activation Key you have entered is invalid.', true));
			return false;
		}
	}
	
	function setTos($data, $switch = 0) {
		$this->id = $data['User']['id'];
		if ($this->saveField('has_accepted_tos', $switch, true)) {
			return true;
		} else {
			return false;
		}
	}
	
	function setDisabled($data, $switch) {
		$this->id = $data['User']['id'];
		if ($this->saveField('is_disabled', $switch, true)) {
			return true;
		} else {
			return false;
		}
	}
	
	function updateLastLogin($data) {
		$this->id = $data['User']['id'];
		if ($this->saveField('last_login', date('Y-m-d H:i:s'), true)) {
			return true;
		} else {
			$this->log('Could not update last_login on user ' . $this->id . true);
			return false;
		}
	}
}
?>