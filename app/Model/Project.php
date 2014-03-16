<?php
App::uses('AppModel', 'Model');
/**
 * Project Model
 *
 * @property Groups $Groups
*/
class Project extends AppModel {

	var $validate = array(
			'name' => array(
					array(
							'rule'=>'notEmpty',
							'message' => 'Name not null please'
					),
					array(
							'rule'=>'isUnique',
							'message' => "This name already exists"
					)
			),
			'stardate' => array(
					'typedate' => array(
							'rule' => array('date','dmy'),
							'message' => 'Enter a valid date (jj-mm-yyyy)',
							'allowEmpty' => true
					),
					'compare'    => array(
							'rule'      => array('comparedate', 'stardate'),
							'message'   => 'Dates doesn\'t match',
							'required'  => true,
					),
					'empty' => array(
							'rule'      => 'notEmpty',
							'message'   => 'Start Date must not be blank',
					)
			),
			'enddate' => array(
					'rule' => array('date','dmy'),
					'message' => 'Enter a valid date (jj-mm-yyyy)',
					'allowEmpty' => true
			),
			'link'=>array(
					'allowEmpty'=>true,
					'required' => false,
					'rule'      => array('url', true),
					'message'   =>'URL incorrect, put first http://'
			),
			'User'=>array(
					'testmultiple' => array(
							'rule'	  => array('testmultiple','User'),
							'message' => 'Please choose member'
					)
			)
	);
	function testmultiple($data,$user){
		if($this->data[$this->alias]['TypeUser'] == "M"){
			return true;
		}else{
			if($this->data[$this->alias]['TypeUser'] == "O") {
				if($this->data[$this->alias][$user] == null) {
					return false;
				}else  return true;
			}
		}
	}

	function comparedate($data, $startdate)
	{
		$startdate         = $this->data[$this->alias][$startdate];
		if(!empty($this->data[$this->alias]['enddate'])){
				
			if($startdate >  $this->data[$this->alias]['enddate']){
				return false;
			}else return true;
		}return true;
	}
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public function beforeSave($options = array()){

		if(!empty($this->data['Project']['name'])){
			$this->data['Project']['slug'] =  Inflector::slug($this->data['Project']['name'],'-');
		}
		if (!empty($this->data['Project']['stardate'])) {
			$this->data['Project']['stardate'] = $this->dateFormatBeforeSave($this->data['Project']['stardate']);
		}
		if(!empty($this->data['Project']['enddate'])){
			$this->data['Project']['enddate'] = $this->dateFormatBeforeSave($this->data['Project']['enddate']);
		}
		//if external link null
		if($this->data['Project']['external'] == "1" && $this->data['Project']['link'] == ''){
			$this->data['Project']['external'] = "0";
		}
		// 		if(!empty($this->data['Project']['user_id'])){
		// // 			$this->data['Project']['user_id'] = var_dump(implode(',',$this->data['Project']['user_id']));
		// 			$this->data['Project']['user_id'] = implode(',',$this->data['Project']['user_id']);

		// 		}

		foreach (array_keys($this->hasAndBelongsToMany) as $model){
			if(isset($this->data[$this->name][$model])){
				$this->data[$model][$model] = $this->data[$this->name][$model];
				unset($this->data[$this->name][$model]);
			}
		}
		return true;

	}

	function afterFind($results, $primary = false){
			
		foreach ($results as $key => $val) {
			if (isset($val['Project']['stardate'])) {
				$results[$key]['Project']['stardate'] = $this->dateFormatAfterFind($val['Project']['stardate']);
			}
			if (isset($val['Project']['enddate'])) {
				$results[$key]['Project']['enddate'] = $this->dateFormatAfterFind($val['Project']['enddate']);
			}

		}
		return $results;
	}

	public function dateFormatBeforeSave($dateString) {
		return date('Y-m-d', strtotime($dateString));
	}
	public function dateFormatAfterFind($dateString) {
		return date('d-m-Y', strtotime($dateString));
	}

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'name';

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $hasMany = array(
			'Media' => array(
					'className' => 'Media',
					'foreignKey' => 'project_id',
					'conditions' => '',
					'fields' => '',
					'order' => 'Media.date DESC'
			)
	);

	var $hasAndBelongsToMany = array(
			'User' => array(
					'className'              => 'User',
					'joinTable'              => 'users_projects',
					'foreignKey'             => 'project_id',
					'associationForeignKey'  => 'user_id',
					'unique' 				 => 'keepExisting',
			)
	);
}
