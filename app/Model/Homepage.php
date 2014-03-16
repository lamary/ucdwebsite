<?php
App::uses('AppModel', 'Model');
/**
 * Homepage Model
 *
 */
class Homepage extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'homepage';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'content';

}
