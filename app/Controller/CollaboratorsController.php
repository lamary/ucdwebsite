<?php
App::uses('AppController', 'Controller');
/**
 * Collaborators Controller
 *
 * @property Collaborator $Collaborator
*/
class CollaboratorsController extends AppController {

	public $paginate = array('limit'=>20);
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->Collaborator->recursive = 0;
		$this->set('collaborators', $this->paginate());
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		if (!$this->Collaborator->exists($id)) {
			throw new NotFoundException(__('Invalid collaborator'));
		}
		$options = array('conditions' => array('Collaborator.' . $this->Collaborator->primaryKey => $id));
		$this->set('collaborator', $this->Collaborator->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Collaborator->create();
			$this->Collaborator->set($this->request->data);
			if($this->RequestHandler->isAjax()) {
				if ($this->Collaborator->validates()) {
					$d = $this->request->data;
					if ($this->Collaborator->save($this->request->data)) {
						$array['message'] = 'The collaborator has been saved';
						$array['statut']  = 'success';
						echo json_encode($array);
						exit;
					} else {
						$array['message'] = 'The collaborator could not be saved. Please, try again.';
						$array['statut']  = 'error';
						echo json_encode($array);
						exit;
					}
				}else {
					$array['message'] = 'ERROR fields';
					$array['data'] = array('Collaborator'=>$this->Collaborator->error_messages());
					$array['statut']  = 'error';
					echo json_encode($array);
					exit;
				}
			}
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
		if (!$this->Collaborator->exists($id)) {
			throw new NotFoundException(__('Invalid collaborator'));
		}
		// 		debug($this->request->data);
		if ($this->request->is('post') || $this->request->is('put')) {
			if($this->RequestHandler->isAjax()) {
				if ($this->Collaborator->validates()) {
					if ($this->Collaborator->save($this->request->data)) {
						$array['message'] = 'The collaborator has been updated';
						$array['statut']  = 'success';
						echo json_encode($array);
						exit;
					} else {
						$array['message'] = 'The collaborator could not be updated. Please, try again.';
						$array['statut']  = 'error';
						echo json_encode($array);
						exit;
					}
				}else {
					$array['message'] = 'ERROR fields';
					$array['data'] = array('Collaborator'=>$this->Collaborator->error_messages());
					$array['statut']  = 'error';
					echo json_encode($array);
					exit;
				}
			}
		}
		else {
			$options = array('conditions' => array('Collaborator.' . $this->Collaborator->primaryKey => $id));
			$this->request->data = $this->Collaborator->find('first', $options);
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
		$this->Collaborator->id = $id;
		if (!$this->Collaborator->exists()) {
			throw new NotFoundException(__('Invalid collaborator'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Collaborator->delete()) {
			$this->Session->setFlash(__('Collaborator deleted'),'default', array('class' => 'alert alert-success'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Collaborator was not deleted'),'default', array('class' => 'alert alert-error'));
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index() {
		$this->Collaborator->recursive = 0;
		$this->set('collaborators', $this->paginate());
	}

	/**
	 * admin_view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_view($id = null) {
		if (!$this->Collaborator->exists($id)) {
			throw new NotFoundException(__('Invalid collaborator'));
		}
		$options = array('conditions' => array('Collaborator.' . $this->Collaborator->primaryKey => $id));
		$this->set('collaborator', $this->Collaborator->find('first', $options));
	}

	/**
	 * admin_add method
	 *
	 * @return void
	 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Collaborator->create();
			if ($this->Collaborator->save($this->request->data)) {
				$this->Session->setFlash(__('The collaborator has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The collaborator could not be saved. Please, try again.'));
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
		if (!$this->Collaborator->exists($id)) {
			throw new NotFoundException(__('Invalid collaborator'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Collaborator->save($this->request->data)) {
				$this->Session->setFlash(__('The collaborator has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The collaborator could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Collaborator.' . $this->Collaborator->primaryKey => $id));
			$this->request->data = $this->Collaborator->find('first', $options);
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
		$this->Collaborator->id = $id;
		if (!$this->Collaborator->exists()) {
			throw new NotFoundException(__('Invalid collaborator'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Collaborator->delete()) {
			$this->Session->setFlash(__('Collaborator deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Collaborator was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
