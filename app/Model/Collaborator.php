<?php
App::uses('AppModel', 'Model');
/**
 * Collaborator Model
 *
*/
class Collaborator extends AppModel {

	/**
	 * Use table
	 *
	 * @var mixed False or table name
	 */
	public $useTable = 'collaborator';
	public $virtualFields = array("nameSurname"=>"CONCAT(name, ' ' ,surname)");
	/**
	 * Display field
	 *
	 * @var string
	*/
	public $displayField = 'name';

	public $validate = array(
			'link'=>array(
					'allowEmpty'=>true,
					'rule'      => array('url', true),
					'message'   =>'URL incorrect, put first http://'
			)
	);

	public function beforeSave($options = array()){
		if(!empty($this->data['Collaborator']['name']) && !empty($this->data['Collaborator']['surname'])){
			$this->data['Collaborator']['name'] = ucwords(strtolower(trim($this->data['Collaborator']['name'])));
			$this->data['Collaborator']['surname'] = ucwords(strtolower(trim($this->data['Collaborator']['surname'])));
		}
	}
}
