<?php
App::uses('AppController', 'Controller');
/**
 * TypeUsers Controller
 *
 * @property TypeUser $TypeUser
*/
class TypeUsersController extends AppController {

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		if($this->Session->read('Auth.User.id') && $this->Session->read('Auth.User.admin') === true){
			$this->TypeUser->recursive = 0;
			$this->paginate = array('order' => 'TypeUser.order ASC');
			$this->set('typeUsers', $this->paginate());
		}else{
			$this->redirect('/');die;
		}
	}


	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		
		if ($this->request->is('post')) {
			$this->TypeUser->create();
			$this->TypeUser->set($this->request->data);
			if($this->request->is('ajax')) {
				if ($this->TypeUser->validates()) {
					$countordermax = $this->TypeUser->find('first',array('fields'=>'MAX(TypeUser.order) as maxorder'));

					$this->request->data['TypeUser']['order'] = $countordermax[0]['maxorder']+1;
					if ($this->TypeUser->save($this->request->data)){
						$array['message'] = 'Type User Added';
						$array['statut']  = 'success';
						echo json_encode($array);
						exit;	} else {
						$array['message'] = 'Error added.Please, try again.';
						$array['statut']  = 'error';
						echo json_encode($array);
						exit;
						}
				}else{
					$array['message'] = 'ERROR fields';
					$array['data'] = array('TypeUser'=>$this->TypeUser->error_messages());
					$array['statut']  = 'error';
					echo json_encode($array);
					exit;
				}
			}else{
				$this->Session->setFlash(__('The type user could not be saved. Please, try again.'));
			}
		}
	}
	function editOrder(){
		$this->autoRender = false;
		$id = $_POST['id'];
		foreach ($id as $key=>$val){
			$order = $key+1;
			$data = array('id' => $val, 'order' => $order);
			$success = $this->TypeUser->save($data, array('validate' => false));
		}
		if($success){
			$array['message'] = 'Order Type Members updated';
			$array['statut']  = 'success';
			echo json_encode($array);
			exit;
		}else{
			$array['message'] = 'Error update.Please, try again.';
			$array['statut']  = 'error';
			echo json_encode($array);
			exit;
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
		$this->TypeUser->id = $_POST['id'];
		if (!$this->TypeUser->exists($id)) {
			throw new NotFoundException(__('Invalid type user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if($this->RequestHandler->isAjax()){
				$this->request->data['TypeUser']['name'] = $_POST['val'];
				$count = $this->TypeUser->find("count", array(
						"conditions" => array("name" => $this->data['TypeUser']['name'], "TypeUser.id !=".$_POST['id'])
				));

				if ($count == 0) {
					if ($this->TypeUser->save($this->request->data, array('validate' => false))) {
						$array['message'] = 'Order Type Members updated';
						$array['statut']  = 'success';
						echo json_encode($array);
						exit;
					} else {
						$array['message'] = 'Error update.Please, try again.';
						$array['statut']  = 'error';
						echo json_encode($array);
						exit;
					}
				}else{
					$array['message'] = 'This name already exists.Please, try again.';
					$array['statut']  = 'error';
					echo json_encode($array);
					exit;
				}
			}
		} else {
			$options = array('conditions' => array('TypeUser.' . $this->TypeUser->primaryKey => $id));
			$this->request->data = $this->TypeUser->find('first', $options);
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
		$this->TypeUser->id = $id;
		if (!$this->TypeUser->exists()) {
			throw new NotFoundException(__('Invalid type user'));
		}
		$this->request->onlyAllow('post', 'delete');
		$this->loadModel('User');
		$testexistuser = $this->User->find('count',array('conditions'=>array('User.type_users_id'=>$id)));
		if($testexistuser > 0){
			$this->Session->setFlash(__('Type user can\'t be deleted because it\'s linked to members,<br /> Please change type directly  on members before'),'default', array('class' => 'alert alert-error'));
			$this->redirect(array('action' => 'index'));
		}else{
		if ($this->TypeUser->delete()) {
			$this->Session->setFlash(__('Type user deleted'),'default', array('class' => 'alert alert-success'));
			$this->redirect(array('action' => 'index'));
		}
		
		}
	}

	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index() {
		$this->TypeUser->recursive = 0;
		$this->set('typeUsers', $this->paginate());
	}

	/**
	 * admin_view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_view($id = null) {
		if (!$this->TypeUser->exists($id)) {
			throw new NotFoundException(__('Invalid type user'));
		}
		$options = array('conditions' => array('TypeUser.' . $this->TypeUser->primaryKey => $id));
		$this->set('typeUser', $this->TypeUser->find('first', $options));
	}

	/**
	 * admin_add method
	 *
	 * @return void
	 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->TypeUser->create();
			if ($this->TypeUser->save($this->request->data)) {
				$this->Session->setFlash(__('The type user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The type user could not be saved. Please, try again.'));
			}
		}
	}

	/**
	 * admin_edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_edit($id = null) {
		if (!$this->TypeUser->exists($id)) {
			throw new NotFoundException(__('Invalid type user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->TypeUser->save($this->request->data)) {
				$this->Session->setFlash(__('The type user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The type user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('TypeUser.' . $this->TypeUser->primaryKey => $id));
			$this->request->data = $this->TypeUser->find('first', $options);
		}
	}

	/**
	 * admin_delete method
	 *
	 * @throws NotFoundException
	 * @throws MethodNotAllowedException
	 * @param string $id
	 * @return void
	 */
	public function admin_delete($id = null) {
		$this->TypeUser->id = $id;
		if (!$this->TypeUser->exists()) {
			throw new NotFoundException(__('Invalid type user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->TypeUser->delete()) {
			$this->Session->setFlash(__('Type user deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Type user was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
