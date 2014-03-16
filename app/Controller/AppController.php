<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');
App::uses('Helper', 'AppHelper');


/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
*/
class AppController extends Controller {
	public $components = array('RequestHandler','Session','Cookie','Auth');
	public $uses = array('Property');

	function beforeFilter(){
		
	 if(isset($this->Auth))
	 {
	 	$this->Auth->authenticate = array('Form' => array('fields' => array('username' => 'mail'),
	 			'scope'=>array('User.activate' => 1)
	 	));
// 	 	$this->Auth->authError =  'No authorized';
// 	 	$this->Auth->authorize = array('Controller');
	 	// 	 	$this->Auth->userModel = 'User';
	 	// 	 	$this->Auth->loginAction = array('controller'=>'users', 'action'=>'login');
	 	// 	 	$this->Auth->fields = array('username' => 'mail', 'password' => 'password');
	 	// 	 	$this->Auth->scope = array('User.activated' => 1);

	 	// 	 	       $this->Auth->loginRedirect = '/admin/articles';
	 	// 	 	       $this->Auth->loginError = "Identifiant ou mot de passe incorrects.";
	 	// 	 	       $this->Auth->logoutRedirect = '/';
	 	// 	        $this->Auth->authError = "Vous n'avez pas accès à cette page.";
	 	$this->Auth->autoRedirect = false;
	 	// 	 	//       $this->Auth->authorize = 'controller';

// 	 	$this->Auth->allow('view', 'index');
	 		 	$this->Auth->allow();

	 }
	}
	public function beforeRender() {
		$this->set('properties', $this->Property->find('all'));
		$params = explode('/',$this->params->url);
		$this->set('params',$params[0]);
		if(isset($params[1])){
			$this->set('paramsfull',$params[1]);
		}
	}

	
}

