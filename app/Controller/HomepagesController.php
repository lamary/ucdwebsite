<?php
App::uses('AppController', 'Controller');
/**
 * Homepages Controller
 *
 * @property Homepage $Homepage
*/
class HomepagesController extends AppController {
	public $paginate = array('order' => array('Homepage.column' => 'asc'));
	/**
	 * index method
	 *
	 * @return void
	*/
	public function index() {
		$this->Homepage->recursive = 0;
		$this->set('homepages', $this->paginate());
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		if (!$this->Homepage->exists($id)) {
			throw new NotFoundException(__('Invalid homepage'));
		}
		$options = array('conditions' => array('Homepage.' . $this->Homepage->primaryKey => $id));
		$this->set('homepage', $this->Homepage->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this->request->is('post')) {
			$d = $this->request->data;
			$this->Homepage->create();

			if($this->RequestHandler->isAjax()) {
				$existrow = $this->Homepage->find('first',array('fields'=>array('MAX(Homepage.row) AS rowwidget')));
				if(empty($existrow)){
					$d['Homepage']['row'] = 1;
				}else{
					$d['Homepage']['row']=$existrow[0]['rowwidget']+1;
				}
				$d['Homepage']['column'] = 1;
				// 				$d['Homepage']['column'] = $column;
				$this->Homepage->set($d);
				if ($this->Homepage->save($d)) {
					$array['message'] = 'The Widget has been saved';
					$array['statut']  = 'success';
					echo json_encode($array);
					exit;
					// 						$this->Session->setFlash(__('The homepage has been saved'));
					// 						$this->redirect(array('action' => 'index'));
				} else {
					$array['message'] = 'The Widget could not be saved. Please, try again.';
					$array['statut']  = 'error';
					echo json_encode($array);
					exit;
					// 						$this->Session->setFlash(__('The homepage could not be saved. Please, try again.'));
				}
			}
		}
		$this->layout = 'whitoutmenu';
	}

	function editOrder(){
		$this->autoRender = false;
		$success = true;
		$idrow 	  = $_POST['idrow'];
		$idcolumn = $_POST['idcolumn'];
		$idwidget = $_POST['idwidget'];
		foreach ($idwidget as $valw){
			$id[] = $valw;
		}
		foreach ($idcolumn as $valc){
			$column[] = $valc;
		}
		for($i=0;$i<sizeof($column);$i++){
			$data = array('row' => $idrow, 'column'=>$i+1,'id' => $id[$i]);
			$success = $this->Homepage->save($data);
		}
		if($success){
			$array['message'] = 'Order Widgets updated';
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
		$this->autoRender = false;
		$this->Homepage->id = $id;
		if (!$this->Homepage->exists($id)) {
			throw new NotFoundException(__('Invalid homepage'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if($this->RequestHandler->isAjax()){
				$this->request->data['Homepage']['content'] = stripslashes($_POST['content']);
				// 			debug(htmlentities($_POST['content']));
				if ($this->Homepage->save($this->request->data)) {
					// 					$this->Session->setFlash(__('The homepage has been saved'));
					// 					$this->redirect(array('action' => 'index'));
					$array['message'] = 'Widget updated';
					$array['statut']  = 'success';
					echo json_encode($array);
					exit;
				} else {
					// 					$this->Session->setFlash(__('The homepage could not be saved. Please, try again.'));
					$array['message'] = 'Error update.Please, try again.';
					$array['statut']  = 'error';
					echo json_encode($array);
					exit;
				}
			}
		} else {
			$options = array('conditions' => array('Homepage.' . $this->Homepage->primaryKey => $id));
			$this->request->data = $this->Homepage->find('first', $options);
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
		$this->autoRender = false;
		$this->Homepage->id = $id;
		if (!$this->Homepage->exists()) {
			throw new NotFoundException(__('Invalid homepage'));
		}
		$this->request->onlyAllow('post', 'delete');
		if($this->RequestHandler->isAjax()){
			if ($this->Homepage->delete()) {
				$this->Session->setFlash(__('Widget deleted'),'default', array('class' => 'alert alert-success'));
				// 			$this->redirect(array('action' => 'index'));
				$array['statut']  = 'success';
				echo json_encode($array);
				exit;
			}else{
				$this->Session->setFlash(__('Widget was not deleted'),'default', array('class' => 'alert alert-error'));
				// 		$this->redirect(array('action' => 'index'));
				$array['statut']  = 'error';
				echo json_encode($array);
				exit;
			}
		}
	}


}
