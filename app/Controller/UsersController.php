<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'captcha/captcha');
/**
 * Users Controller
 *
 * @property User $User
*/
class UsersController extends AppController {

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->User->recursive = 0;
		$typeUsers  = $this->User->find('all', array('fields'=>array('TypeUser.name'),'group'=>array('TypeUser.name'),
				'order'=>'TypeUser.order ASC', 'conditions'=>array('User.activate'=>1)));
		//if in url we have 'member' we view just user member otherwise we see just collaborator
		$this->set('typeUsers',$typeUsers);
		$this->set('users', $this->paginate(array('User.activate'=>1)));

	}

	/**
	 * signup method
	 */
	function login(){
		if ($this->request->is('post')){
			if($this->Auth->login()){
				$this->Session->setFlash('You are connected', 'default', array('class'=>'alert alert-success'));
				if(isset($_POST['pagelog']) && $_POST['pagelog'] == 'true'){
					$this->redirect('/');
				}else{
					$this->redirect($this->referer());
				}
			}
			else{
				$mail 		   = $this->request->data['User']['mail'];
				$pwd		   =  AuthComponent::password($this->request->data['User']['password']);
				$activeaccount = $this->User->find('count', array('conditions' => array('User.mail' => $mail,'User.password'=>$pwd)));

				// 				settype($activeaccount, "int");
				// 				$activeaccount = "1";
				// 				debug(gettype($activeaccount));
				if($activeaccount){
					$this->Session->setFlash('Your mail exist but your account is not activated =>
							<span id="activateaccount" style="cursor:pointer"><u> Activated your account.</u><span>', 'default', array('class'=>'alert alert-info'));
				}
				else{
					$this->Session->setFlash('Erroned mail/password', 'default', array('class'=>'alert alert-error'));

				}
			}
		}
	}

	function logout()
	{
		$this->Auth->logout();
		$this->redirect($this->referer());
	}

	//lost password
	function lostpswd($token = null){

		$sessionId = $this->Session->read('Auth.User.id');
		if($sessionId){
			$this->redirect('/');
			die;
		}

		if(isset($token) && !empty($token)) {
			$token = explode('-',$token);
			$user =  $this->User->find('first',array(
					'conditions'=>array('User.id'=>$token[0],'SHA1(User.password)' => $token[1],'activate'=>1, 'DATEDIFF(now(),User.token)<=5')));
			if(!empty($user)){
				$this->User->id = $user['User']['id'];
				$newpwd = substr(sha1(uniqid(rand(),true)),0,8);
				$this->User->saveField('password', $newpwd);
				$this->Session->setFlash('Your new password has been reset, this is your new paswword:<br /><h4> '.$newpwd.'</h4>', 'default', array('class'=>'alert alert-success'));
			}else{
				$this->Session->setFlash('Sorry, This link is not valid', 'default', array('class'=>'alert alert-error'));
			}
		}
		if ($this->request->is('post') && !isset($this->request->params['named']['token'])) {
			$mail = $_POST['UserMailPswdLost'];
			$userLostMail = $this->User->find('first',array('conditions'=> array('mail'=>$mail,'activate'=>1)));
			if(empty($userLostMail)){
				// 				$this->Session->setFlash('No members matchs with this mail.', 'default', array('class'=>'alert alert-error'));
				$array['message'] = 'No members matchs with this mail or your account is not activated.';
				$array['statut']  = 'error';
				echo json_encode($array);
				exit;
			}else{
				// 				debug($userLostMail);
				$link = array('controller'=>'users','action'=>'lostpswd',$userLostMail['User']['id'].'-'.sha1($userLostMail['User']['password']));
				App::uses('CakeEmail','Network/Email');
				$mailPwd = new CakeEmail('laposte');
				$mailPwd->from('laura.amary@laposte.net')
				->to($userLostMail['User']['mail'])
				->subject('Lost Password')
				->emailFormat('html')
				->template('pwd')
				->viewVars(array('username'=>$userLostMail['User']['name'].' '.$userLostMail['User']['surname'],'link'=>$link))
				->send();

				$this->User->id = $userLostMail['User']['id'];
				$this->User->saveField('token',date('Y-m-d H:i:s'));
				$array['message'] = 'Please, check your mail for generate a new password';
				$array['statut']  = 'success';
				echo json_encode($array);
				exit;
				// 				$this->Session->setFlash('Please, check your mail for generate a new password', 'default', array('class'=>'alert alert-success'));
			}
		}
	}
	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		// 		$this->User->id = $id;
		// 		if (!$this->User->exists()) {
		// 			throw new NotFoundException(__('Invalid user'));
		// 		}
		// 		$this->set('user', $this->User->read(null, $id));

		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$pageReturn = $this->referer(array('action'=>'index'), true);
		$this->set('pageReturn', $pageReturn);
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}


	function mailactivate(){

		if(isset($_POST['mail']) && !empty($_POST['mail'])){
			$this->autoRender = false;
			$email = $_POST['mail'];
			$info = $this->User->find('first',array('fields'=>array('User.id, User.name,User.surname'),'conditions'=>array('User.mail' => $email)));
			$this->User->id = $info['User']['id'];
			$name = $info['User']['name'];
			$surname = $info['User']['surname'];
		}

		$link = array('controller'=>'users','action'=>'activate',$this->User->id.'-'.sha1($name));
		App::uses('CakeEmail','Network/Email');
		$mail = new CakeEmail('laposte');
		$mail->from('laura.amary@laposte.net')
		->to($email)
		->subject('Inscription')
		->emailFormat('html')
		->template('signup')
		->viewVars(array('username'=>$name.' '.$surname,'link'=>$link))
		->send();

		$array['message'] = 'Please, check your mail for activate your account, You will be redirected automatically';
		$array['statut']  = 'success';
		echo json_encode($array);
		exit;
	}

	function activate($token){
		$token = explode('-',$token);
		$user =  $this->User->find('first',array(
				'conditions'=>array('User.id'=>$token[0],'SHA1(User.name)' => $token[1],'activate'=>0)
		));
		$userAlready =  $this->User->find('first',array(
				'conditions'=>array('User.id'=>$token[0],'SHA1(User.name)' => $token[1],'activate'=>1)
		));

		if(!empty($user)){
			$this->User->id = $user['User']['id'];
			$this->User->saveField('activate',1);
			$this->Session->setFlash('The user has been activated', 'default', array('class'=>'alert alert-success'));
			$this->Auth->login($user['User']);
		}else{
			if(!empty($userAlready)){
				$this->Session->setFlash('This user is always activated.', 'default', array('class'=>'alert alert-error'));
			}else{
				$this->Session->setFlash('This activation link is not valid.', 'default', array('class'=>'alert alert-error'));
			}
		}
		$this->redirect('/');
		die();
	}

	/**
	 * activationpage method
	 */

	function activationpage(){
		if($this->Session->read('Auth.User.id') && $this->Session->read('Auth.User.admin') === true){
			if(isset($_POST['id']) && !empty($_POST['id'])){
				$this->User->id = $_POST['id'];
				$d = $this->request->data;
				$activate=($_POST['value'] == 'true')?1:0;
				$d['User']['activate'] = $activate;
				if($this->RequestHandler->isAjax()){
					if ($this->User->save($d)) {
						$array['message'] = 'The member\'s acitvation has been updated.';
						$array['statut']  = 'success';
						echo json_encode($array);
						exit;
					} else {
						$array['message'] = 'The member\'s acitvationcould not be updated. Please, try again.';
						$array['data'] = array('User'=>$this->User->error_messages());
						$array['statut']  = 'error';
						echo json_encode($array);
						exit;
					}
				}
			}
			$this->User->recursive = 0;
			$typeUsers  = $this->User->find('all', array('fields'=>array('TypeUser.name'),'group'=>array('TypeUser.name'),
					'order'=>'TypeUser.order ASC', 'conditions'=>array('User.id!='.$this->Session->read('Auth.User.id') )));
			//if in url we have 'member' we view just user member otherwise we see just collaborator
			$this->set('typeUsers',$typeUsers);
			$this->set('users', $this->paginate(array('User.id !='.$this->Session->read('Auth.User.id'))));
		}else{
			$this->redirect('/');
			die;
		}
	}
	/**
	 *add method
	 *
	 * @return void
	 */
	public function add() {
		if($this->Session->read('Auth.User.id') && $this->Session->read('Auth.User.admin') === true){
			if ($this->request->is('post')) {
				$this->User->create();
				$d = $this->request->data;
				$this->User->set($d);
				$passError = false;

				if($this->RequestHandler->isAjax()) {
					if(!empty($d['User']['pass1'])){
						if(strlen($d['User']['pass1'])<8){
							$passError = true;
							$errorMsg = array("pass1"=>"Minimum 8 characters ");
						}
						if($d['User']['pass1'] == $d['User']['pass2']){
							$d['User']['password'] = $d['User']['pass1'];
						}else{
							$passError = true;
							$errorMsg = array("pass2"=>"Passwords you entered do not match");
						}
					}else{
						$passError = true;
						$errorMsg = array("pass1"=>"Please, Must not be blank","pass2"=>"Please, Must not be blank");
					}

					if($passError){
						$array['data'] = array("User"=>$errorMsg);
						$array['message'] = 'The member could not be saved. Please, try again.';
						$array['statut']  = 'error';
						echo json_encode($array);
						exit;
					}else{
						if ($this->User->validates()) {
							$d['User']['id'] = null;
							//Send an Email for activate
							$email = $d['User']['mail'];
							$name = $d['User']['name'];
							$surname = $d['User']['surname'];
							$id = $this->User->getInsertID();

							if($this->User->save($d)){
								$link = array('controller'=>'users','action'=>'activate',$this->User->id.'-'.sha1(ucwords(strtolower(trim($name)))), 'admin' => false);
								App::uses('CakeEmail','Network/Email');
								$mail = new CakeEmail('laposte');
								$mail->from('laura.amary@laposte.net')
								->to($email)
								->subject('Inscription')
								->emailFormat('html')
								->template('signup')
								->viewVars(array('username'=>$name.' '.$surname,'link'=>$link))
								->send();

								$array['id']	  = $id;
								$array['message'] = 'Please, check your mail to activate your account, You will be redirected automatically';
								$array['statut']  = 'success';
								echo json_encode($array);
								exit;
							}else {
								$array['message'] = 'ERROR - Email was send but the user was not add';
								$array['statut']  = 'error';
								echo json_encode($array);
								exit;
							}
							//End Send an Email for activate
						} else {
							$array['message'] = 'ERROR fields';
							$array['data'] = array("User"=>$this->User->error_messages());
							$array['statut']  = 'error';
							echo json_encode($array);
							exit;
						}
					}
				}
			}
			$typeUsers = $this->User->TypeUser->find('list');
			$projects = $this->User->Project->find('list');
			$this->set(compact('typeUsers', 'projects'));
			$this->layout = 'whitoutmenu';
		}else{
			$this->redirect('/');
			die;
		}
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {

		$this->User->id = $id;
		$sessionId = $this->Session->read('Auth.User.id');
		if(!$sessionId && $sessionId != $id){
			$this->redirect('/');die;
		}
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid member'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$d = $this->request->data;
			$this->User->set($d);
			if($this->RequestHandler->isAjax()){
				if ($this->User->validates()) {
					if($this->Session->read('Auth.User.admin') === false){
						$mail = $this->User->find('first',array('field'=>array('User.mail'),'conditions'=>array('User.id'=>$id)));
						$d['User']['mail'] = $mail['User']['mail'];
					}
					if ($this->User->save($d)) {
						if($sessionId == $id){
							$this->Session->write('Auth.User.name', $d['User']['name']);
							$this->Session->write('Auth.User.surname', $d['User']['surname']);
						}
						$array['message'] = 'The member has been updated.';
						$array['statut']  = 'success';
						echo json_encode($array);
						exit;
					} else {
						$array['message'] = 'The member could not be updated. Please, try again.';
						$array['data'] = array('User'=>$this->User->error_messages());
						$array['statut']  = 'error';
						echo json_encode($array);
						exit;
					}
				}else {
					$array['message'] = 'The member could not be updated. Please, try again.';
					$array['data'] = array('User'=>$this->User->error_messages());
					$array['statut']  = 'error';
					echo json_encode($array);
					exit;
				}
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
		if(isset($sessionId) && !empty($sessionId)){
			if($this->Session->read('Auth.User.admin') === true){
				$this->set('iduser',$id);
			}
			$typeUsers = $this->User->TypeUser->find('list');
			$typeUsersnoadmin = $this->User->TypeUser->find('all');
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id),'fields'=>'type_users_id' );
			$userinfo = $this->User->find('first', $options);
			$this->set(compact('typeUsers','userinfo','typeUsersnoadmin'));

			$this->layout = 'whitoutmenu';
		}
	}

	/**
	 * edit password method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function editPwd($id = null) {
		$this->User->id = $id;
		$sessionId = $this->Session->read('Auth.User.id');
		if(!$sessionId || $sessionId != $id){
			$this->redirect('/');
			die;
		}
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid member'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			// 			debug($this->User->data);
			$d = $this->request->data;
			// 			$this->User->set($this->request->data);
			if($this->RequestHandler->isAjax()){
				if(!empty($d['User']['pass1'])){
					if(strlen($d['User']['pass1'])<8){
						$passError = true;
						$errorMsg = array("pass1"=>"Minimum 8 characters ");
					}
					if($d['User']['pass1'] == $d['User']['pass2']){
						$d['User']['password'] = $d['User']['pass1'];
					}else{
						$passError = true;
						$errorMsg = array("pass2"=>"Passwords you entered do not match");
					}
				}else{
					$passError = true;
					$errorMsg = array("pass1"=>"Please, Must not be blank","pass2"=>"Please, Must not be blank");
				}
				// 				debug($d['User']['password']);
				$success = $this->User->save($d,true,array('password'));
				if ($success) {
					$array['message'] = 'Your password has been updated.';
					$array['statut']  = 'success';
					echo json_encode($array);
					exit;
				} else {
					$array['message'] = 'The member could not be updated. Please, try again.';
					if($passError){
						$array['data'] = array("User"=>$errorMsg);
					}
					$array['statut']  = 'error';
					echo json_encode($array);
					exit;exit;
				}
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
		if(isset($sessionId) && !empty($sessionId)){
			// 			$typeUsers = $this->User->TypeUser->find('list');
			// 			$projects = $this->User->Project->find('list');
			// 			$this->set(compact('typeUsers', 'projects'));
			$this->layout = 'whitoutmenu';
		}
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @throws MethodNotAllowedException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if($this->Session->read('Auth.User.id')!= $id){
			if (!$this->User->exists()) {
				throw new NotFoundException(__('Invalid member'));
			}
			if ($this->User->delete()) {
				$this->Session->setFlash('member deleted', 'default', array('class'=>'alert alert-success'));
				$this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('member was not deleted', 'default', array('class'=>'alert alert-error'));
			$this->redirect(array('action' => 'index'));
		}else{
			$this->Session->setFlash('You can delete yourself', 'default', array('class'=>'alert alert-error'));
			$this->redirect(array('action' => 'index'));
		}

	}


}
