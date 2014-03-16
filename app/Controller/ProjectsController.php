<?php
App::uses('AppController', 'Controller');
/**
 * Projects Controller
 *
 * @property Project $Project
*/

class ProjectsController extends AppController {

	var $uses = array('Project','Media');
	public $paginate = array('limit'=>10);
	
	/**
	 * index method
	 *
	 * @return void
	*/
	public function index() {

		$this->Project->find('all', array('contain' => 'User'));
		$this->set('projects', $this->paginate());

		$this->add();
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null,$slug=null) {
		$this->Project->id = $id;
		if (!$this->Project->exists()) {
			throw new NotFoundException(__('Invalid project'));
		}
		$this->set('project', $this->Project->read(null, $id));
		$pageReturn = $this->referer(array('action'=>'index'), true);
		$this->set('pageReturn', $pageReturn);
		$typeMedia = $this->Project->Media->find('all', array('fields'=>'Media.date','conditions'=>array('Media.project_id'=>$id),
				'group'=>array('Media.date'),'order'=>array('Media.date DESC')));
		$this->set('typeMedia', $typeMedia);
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		$sessionId = $this->Session->read('Auth.User.id');
		if ($this->request->is('post')) {
			$this->Project->create();
			$this->Project->set($this->request->data);
			if($this->RequestHandler->isAjax()) {
				if ($this->Project->validates()) {
					//Project manager
					//If creator is admin = can choose project manager
					if($this->Session->read('Auth.User.admin') === true){
						if($this->request->data['Project']['manager'] == ""){
							$this->request->data['Project']['manager'] = $sessionId;
						}
					}else{
						//if creator not admin, he is project manager per default
						$this->request->data['Project']['manager'] = $sessionId;
					}
					//group members
					if($this->request->data['Project']['TypeUser'] == "M"){
						$this->request->data['Project']['User'][] = $sessionId;
					}
					else {
						$this->request->data['Project']['User'][]= $sessionId;
					}
					
					$finadmin = $this->Project->User->find('all',array('fields'=>array('User.id'),'conditions'=>array('admin=1','activate=1')));
					foreach($finadmin as $user){
						$this->request->data['Project']['User'][] = $user['User']['id'];
					}
					//project creator
					$this->request->data['Project']['creator'] =  $sessionId;

					if($this->Project->save($this->request->data)){
						$array['message'] = 'The Project has been saved';
						$array['statut']  = 'success';
						echo json_encode($array);
						exit;
					}else {
						$array['message'] = 'The Project could not be saved. Please, try again.';
						$array['statut']  = 'error';
						echo json_encode($array);
						exit;
					}

				}else{
					$array['message'] = 'ERROR fields';
					$array['data'] = array('Project'=>$this->Project->error_messages());
					$array['statut']  = 'error';
					echo json_encode($array);
					exit;

				}

			}
		}
		if(isset($sessionId) && !empty($sessionId)){
			$users = $this->Project->User->find('list',array('fields'=>array('nameSurname'),'conditions'=>array('User.id !='.$sessionId, 'User.activate = 1')));
			$this->set(compact('users'));
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
		$sessionId = $this->Session->read('Auth.User.id');
		$this->Project->id = $id;
		if (!$this->Project->exists()) {
			throw new NotFoundException(__('Invalid project'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Project->set($this->request->data);
			if($this->RequestHandler->isAjax()) {
				if ($this->Project->validates()) {
					//Project manager
					//If creator is admin = can choose project manager
					if($this->Session->read('Auth.User.admin') === true){
						if($this->request->data['Project']['manager'] == ""){
							$this->request->data['Project']['manager'] = $sessionId;
						}
					}
					//group members
					if($this->request->data['Project']['TypeUser'] == "M"){
						$this->request->data['Project']['User'][] = $this->Session->read('Auth.User.id');
					}
					else { $this->request->data['Project']['User'][]= $this->Session->read('Auth.User.id');
					}
					
					$finadmin = $this->Project->User->find('all',array('fields'=>array('User.id'),'conditions'=>array('admin=1','activate=1')));
					foreach($finadmin as $user){
						$this->request->data['Project']['User'][] = $user['User']['id'];
					}
					if($this->Project->save($this->request->data)){
						$array['message'] = 'The Project has been saved';
						$array['statut']  = 'success';
						echo json_encode($array);
						exit;
					}else {
						$array['message'] = 'The Project could not be saved. Please, try again.';
						$array['statut']  = 'error';
						echo json_encode($array);
						exit;
					}

				}else{
					$array['message'] = 'ERROR fields';
					$array['data']    = array('Project'=>$this->Project->error_messages());
					$array['statut']  = 'error';
					echo json_encode($array);
					exit;
				}
			}
		} else {
			$this->request->data = $this->Project->read(null, $id);
		}

		if(isset($sessionId) && !empty($sessionId)){
			$users = $this->Project->User->find('list',array('fields'=>array('nameSurname'),'conditions'=>array('User.id !='.$sessionId)));
			$this->set(compact('users'));
			$this->set('project', $this->Project->read(null, $id));
			$this->layout = 'whitoutmenu';
		}else{
			$this->redirect('/');
		}

	}

	/**
	 * delete method
	 *
	 * @throws MethodNotAllowedException
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Project->id = $id;
		if (!$this->Project->exists()) {
			throw new NotFoundException(__('Invalid project'),'default', array('class' => 'alert alert-error'));
		}
		$medias = $this->Project->Media->find('all',array('conditions'=>'Project.id = '.$id,'fields'=>'Media.*'));
		if($medias){
			foreach ($medias as $media){
				$this->Media->set(array(
						'id' => $media['Media']['id'],
						'project_id' => null
				));
				$this->Media->save();
			}
		}
		if ($this->Project->delete()) {
				
			$this->Session->setFlash(__('Project deleted'),'default', array('class' => 'alert alert-success'));
			$this->redirect(array('action' => 'index'));
		}else{
			$this->Session->setFlash(__('Project was not deleted'),'default', array('class' => 'alert alert-error'));
			$this->redirect(array('action' => 'index'));
		}
	}

	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index() {
		$this->Project->recursive = 0;
		$this->set('projects', $this->paginate());
	}

	/**
	 * admin_view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_view($id = null) {
		$this->Project->id = $id;
		if (!$this->Project->exists()) {
			throw new NotFoundException(__('Invalid project'));
		}
		$this->set('project', $this->Project->read(null, $id));
	}

	/**
	 * admin_add method
	 *
	 * @return void
	 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Project->create();
			if ($this->Project->save($this->request->data)) {
				$this->Session->setFlash(__('The project has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project could not be saved. Please, try again.'));
			}
		}
		$users = $this->Project->User->find('list');
		$this->set(compact('users'));
	}

	/**
	 * admin_edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_edit($id = null) {
		$this->Project->id = $id;
		if (!$this->Project->exists()) {
			throw new NotFoundException(__('Invalid project'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Project->save($this->request->data)) {
				$this->Session->setFlash(__('The project has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Project->read(null, $id);
		}
		$users = $this->Project->User->find('list');
		$this->set(compact('users'));
	}

	/**
	 * admin_delete method
	 *
	 * @throws MethodNotAllowedException
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Project->id = $id;
		if (!$this->Project->exists()) {
			throw new NotFoundException(__('Invalid project'));
		}
		if ($this->Project->delete()) {
			$this->Session->setFlash(__('Project deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
