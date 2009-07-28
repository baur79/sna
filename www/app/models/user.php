<?php
class User extends AppModel {
	
	var $name = 'User';
	var $validate = array(
		'username' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This nickname is already in use.',
			),
			'minLength' => array(
				'rule' => array('minLength', '8'),
				'message' => 'Minimum length of 8 characters.',
			),
		),
		'password' => array(
			'minLength' => array(
				'rule' => array('minLength', '5'),
				'message' => 'Minimum length of 5 characters.',
			),
		),
		'nickname' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This nickname is already in use.',
			),
			'minLength' => array(
				'rule' => array('minLength', '8'),
				'message' => 'Minimum length of 8 characters.',
			),
			'alphaNumeric' => array(
				'rule' => 'alphaNumeric',
				'message' => 'Nickname must only contain letters and numbers.',
			),
		),
		'email' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This email address is already in use.',
			),
			'email' => array(
				'rule' => 'email',
				'message' => 'Must be a valid email address.',
			),
		),
	);
	
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
	
}
?>