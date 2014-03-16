<?php
App::uses('AppController', 'Controller');
/**
 * Properties Controller
 *
 * @property Property $Property
 */
class PropertiesController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Property->recursive = 0;
		$this->set('properties', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Property->exists($id)) {
			throw new NotFoundException(__('Invalid property'));
		}
		$options = array('conditions' => array('Property.' . $this->Property->primaryKey => $id));
		$this->set('property', $this->Property->find('first', $options));
	}


/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit() {
		if ($this->request->is('post') || $this->request->is('put')){
			$datas = $this->request->data;
			foreach($datas as $key=>$val){
				$info = $this->Property->find('first', array('conditions'=>array('type'=>$key)));
				$this->Property->set(array('id'=>$info['Property']['id'],'type'=>$key,'attribut'=>$val));
				$success = $this->Property->save();
			}

			if ($success) {
					$array['message'] = 'The property has been saved.';
					$array['statut']  = 'success';
					echo json_encode($array);
					exit;
			} else {
				$array['message'] = 'The property could not be saved. Please, try again.';
				$array['statut']  = 'error';
				echo json_encode($array);
				exit;
			}
		} else {
			
			$property = $this->Property->find('all',array('order'=>array('Property.order' => 'ASC')));
			$this->set('datapropreties',$property);
			$this->layout = 'whitoutmenu';
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Property->id = $id;
		if (!$this->Property->exists()) {
			throw new NotFoundException(__('Invalid property'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Property->delete()) {
			$this->Session->setFlash(__('Property deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Property was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
