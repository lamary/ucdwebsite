<?php
App::uses('AppModel', 'Model');
/**
 * TypeUser Model
 *
*/
class TypeUser extends AppModel {

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'name';
	public $validate = array(
			'name' => array(
					'notempty' => array(
							'rule' => 'notEmpty',
							'message'  => 'Field name be completed'
					),
					'isUnique' => array(
							'rule' => 'isUnique',
							'message' => "This name already exists."
					)
			)
	);


	public function beforeSave($options = array()){
		if(!empty($this->data['TypeUser']['name'])){
			$this->data['TypeUser']['name'] =  ucfirst($this->data['TypeUser']['name']);
		}
	}

}
