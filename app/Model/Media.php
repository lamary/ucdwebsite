<?php
App::uses('AppModel', 'Model');
/**
 * Media Model
 *
 * @property Project $Project
*/
class Media extends AppModel {

	/**
	 * Use table
	 *
	 * @var mixed False or table name
	 */
	public $useTable = 'medias';

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'name';

	public $validate = array(
			'linkref'=>array(
					'allowEmpty'=>true,
					'rule' => array('url', true),
					'message'=>'URL incorrect, put first http://'
			));

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public function beforeSave($options = array()){
		if(!empty($this->data['Media']['name'])){
			$this->data['Media']['name'] =  Inflector::slug($this->data['Media']['name'],' ');
			$this->data['Media']['slug'] =  Inflector::slug($this->data['Media']['name'],'-');
		}
	}

	// 	function afterFind($results, $primary = false){
	// 		foreach ($results as $key => $val) {
	// 			if (isset($val['Media']['date'])) {
	// // 				$results[$key]['Media']['date'] = $this->dateFormatAfterFind($val['Media']['date']);
	// 				$results[$key]['Media']['date'] = $val['Media']['date'];
	// 			}

	// 		}
	// 		return $results;
	// 	}

	// 	public function dateFormatAfterFind($dateString) {
	// 		return date('Y', strtotime($dateString));
	// 	}
	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
			'Project' => array(
					'className' => 'Project',
					'foreignKey' => 'project_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);
}
