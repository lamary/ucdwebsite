<?php
App::uses('AppModel', 'Model');
App::uses('Security', 'Utility');
/**
 * User Model
 *
 * @property TypeUsers $TypeUsers
 * @property Project $Project
*/
class User extends AppModel {

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $virtualFields = array("nameSurname"=>"CONCAT(User.name, ' ' ,User.surname)");
	public $displayField = 'name';

	public $validate = array(
			'name' => array(
							'rule' => 'notEmpty',
							'message'  => 'Field name be completed'
					),
			'surname'=>array(
							'rule' => 'notEmpty',
							'message'  => 'Field surname be completed'
					),
			'pass1'=>array(
							'rule' => 'notEmpty',
							'message'  => 'Field password be completed'
					),
			'pass2'=>array(
					'rule' => 'notEmpty',
					'message'  => 'Field password be completed'
			),
			'email'=> array(
					'valid' => array(
							'rule'     => 'email',
							'message'  => 'Your mail is not valid.'
					),
					'notempty' => array(
							'rule' => 'notEmpty',
							'message'  => 'Field mail be completed'
					),
					'isUnique' => array(
							'rule' => array('isUniqueMail'),
							'message' => "This mail already exists."
					)
			),
			'mail'=> array(
					'valid' => array(
							'rule'     => 'email',
							'message'  => 'Your mail is not valid.'
					),
					'notempty' => array(
							'rule' => 'notEmpty',
							// 							'required' => true,
							'message'  => 'Field mail be completed'
					),
					'isUnique' => array(
							'rule' => 'isUnique',
							'message' => "This mail already exists."
					)
			),
			'link'=>array(
					'allowEmpty'=>true,
					'rule' => array('url', true),
					'message'=>'URL incorrect, put first http://'
			)

	);
	public function isUniqueMail() {
		$conditions = array($this->alias . '.mail' => $this->data[$this->alias]['email']);
		if (isset($this->data[$this->alias][$this->primaryKey])) {
			$conditions[$this->alias . '.' . $this->primaryKey . ' <>'] = $this->data[$this->alias][$this->primaryKey];
		}
		return $this->find('count', compact('conditions')) == 0;
	}

	public function beforeSave($options = array()){
		if(!empty($this->data['User']['name']) && !empty($this->data['User']['surname'])){

			$this->data['User']['name'] 	= ucwords(strtolower(trim($this->data['User']['name'])));
			$this->data['User']['surname'] 	= ucwords(strtolower(trim($this->data['User']['surname'])));
			$nameSur = strtolower($this->data['User']['name']." ".$this->data['User']['surname']);
			//create slug
			$this->data['User']['slug'] =  Inflector::slug($nameSur,'-');
		}

		if(!empty($this->data['User']['password'])){
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		}
		return true;
	}


	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
			'TypeUser' => array(
					'className' => 'TypeUser',
					'foreignKey' => 'type_users_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);

	/**
	 * hasAndBelongsToMany associations
	 *
	 * @var array
	*/
	public $hasAndBelongsToMany = array(
			'Project' => array(
					'className' => 'Project',
					'joinTable' => 'users_projects',
					'foreignKey' => 'user_id',
					'associationForeignKey' => 'project_id',
					'unique' => 'keepExisting',
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'finderQuery' => '',
					'deleteQuery' => '',
					'insertQuery' => ''
			)
	);

}
