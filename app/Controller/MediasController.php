<?php
App::uses('AppController', 'Controller');
/**
 * Media Controller
 *
 * @property Media $Media
*/
class MediasController extends AppController {
	public $paginate = array('limit'=>9999);
	/**
	 * index method
	 *
	 * @return void
	*/
	public function index() {
		$nameProjects = $this->Media->Project->find('all',array('group'=>array('Project.name'),
				'order'=>array('YEAR(Project.stardate) ASC')));

		$this->set('medias', $this->paginate());
		$this->set('nameProjects',$nameProjects);
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		$this->Media->id = $id;
		if (!$this->Media->exists()) {
			throw new NotFoundException(__('Invalid media'));
		}
		$conditions = array("Media.id" => $id);
		$this->set('media', $this->Media->find('all',  array('conditions' => $conditions, 'group'=>'Media.date')));

	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this->request->is('post')) {
			//If file
			if(isset($_GET['project_id'])){
				$success_transfert = true;
				$project_id 	= $_GET['project_id'];
				$reference 		= $_GET['reference'];
				$date 			= $_GET['date'];
				$linkRef		= $_GET['linkref'];
				$file = $_FILES['file'];
				//extention media
				$name = $file['name'];
				$f=explode('.',$name);
				$ext = '.'.strtolower(substr($name, strrpos($name,'.',-1) +1));
				
				$filename = Inflector::slug(implode('.',array_slice($f,0,-1)),' ');
				$slug = Inflector::slug(implode('.',array_slice($f,0,-1)),'-');
				if($project_id != "" || $project_id != null){
					$v = 'uploads/'.$date.'/Project'.$project_id.'/'.$slug.$ext;
					$filenamedir = 'uploads/'.$date.'/Project'.$project_id;
				}else{
					$v = 'uploads/'.$date.'/'.$slug.$ext;
					$filenamedir = 'uploads/'.$date;
				}

				if(!file_exists($filenamedir)){
					mkdir($filenamedir,0777,true);
				}

				$options = array('conditions'=>array('Media.name'=> $filename,'Media.type' => $ext));
				$testExistBDD = $this->Media->find('first',$options);
				// 			debug($testExistBDD);

				if(file_exists($v) || $testExistBDD){
					die('{"statut":false, "message": "ERROR : Publication '.$filename.$ext.' already exists"}');
					$success_transfert = false;
				}
				//Backup BDD
				$success = $this->Media->save(array(
						'project_id'=> $project_id,
						'reference'=>$reference,
						'name' =>$filename,
						'linkref' =>$linkRef,
						'type' => $ext,
						'link'=> $v,
						'slug'=> $slug,
						'date'=>$date));
				if($this->Media->validates()){
					if($success_transfert === true && $success){
						move_uploaded_file($file['tmp_name'], $v);
						die('{"statut":true, "message": "Publication '.$slug.$ext.' Uploaded"}');
					}else{
						die('{"statut":false, "message": "ERROR : Upload"}');
					}
				}else{
					$array['message'] = 'ERROR fields';
					$array['data'] = array('Media'=>$this->Media->error_messages());
					$array['statut']  = 'false';
					echo json_encode($array);
					exit;
				}
			}else{
				$this->Media->create();
				$this->Media->set($this->request->data);
				if($this->RequestHandler->isAjax()) {
					$this->request->data['Media']['date'] = $this->request->data['Media']['date']['year'];
					if($this->Media->validates()){
						if($this->Media->save($this->request->data)){
							$array['message'] = 'The Publication has been saved';
							$array['statut']  = 'success';
							echo json_encode($array);
							exit;
						}else {
							$array['message'] = 'The Publication could not be saved. Please, try again.';
							$array['statut']  = 'error';
							echo json_encode($array);
							exit;
						}
					}else{
						$array['message'] = 'ERROR fields';
						$array['data'] = array('Media'=>$this->Media->error_messages());
						$array['statut']  = 'error';
						echo json_encode($array);
						exit;
					}
				}
			}
		}
		$sessionId = $this->Session->read('Auth.User.id');
		if(isset($sessionId) && !empty($sessionId)){
			$projects = $this->Media->Project->find('list');
			$this->set(compact('projects'));
		}
	}
	function _etatDuRepertoire($link){
		if($link != 'uploads'){
			$expLink = explode('/',$link);
			$linkEnd = "";
			$sizeLink = sizeof($expLink);
			for($i=0;$i<$sizeLink-1;$i++){
				$slash=($i == $sizeLink-2) ? '' : '/';
				$linkEnd .= $expLink[$i].$slash;
			}
			$h = opendir($linkEnd);
			$c = 0;
			while (($o = readdir($h)) !== FALSE)
			{
				if (($o != '.') and ($o != '..'))
				{
					$c++;
				}
			}
			closedir($h);
			if($c==0){
				rmdir($linkEnd);
				$this->_etatDuRepertoire($linkEnd);
			}

		}else return false;
	}
	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		$this->Media->id = $id;
		if (!$this->Media->exists()) {
			throw new NotFoundException(__('Invalid media'));
		}
		$sessionId = $this->Session->read('Auth.User.id');
		if(!$sessionId && $sessionId != $id){
			$this->redirect('/');die;
		}
		if ($this->request->is('post') || $this->request->is('put')) {

			//If file
			if(isset($_GET['project_id'])){
				$success_transfert = true;
				$project_id 	= $_GET['project_id'];
				$reference 		= $_GET['reference'];
				$date 			= $_GET['date'];
				$linkRef		= $_GET['linkref'];
				$file = $_FILES['file'];
				//extention media
				$name = $file['name'];
				$f=explode('.',$name);
				$ext = '.'.strtolower(substr($name, strrpos($name,'.',-1) +1));
					
				$filename = Inflector::slug(implode('.',array_slice($f,0,-1)),' ');
				$slug = Inflector::slug(implode('.',array_slice($f,0,-1)),'-');

				//test if this publication have already a media
				$optionsmedia = array('conditions'=>array('Media.id'=> $id));
				$testExistmedia = $this->Media->find('first',$optionsmedia);
				if(!empty($testExistmedia)){
					//if same date but different name
					if($testExistmedia['Media']['date'] == $date && $testExistmedia['Media']['name'] !="" && $testExistmedia['Media']['name'] != $filename){
						unlink(WWW_ROOT.$testExistmedia['Media']['link']);
					}
					//test if this publication change date but new media
					if($testExistmedia['Media']['date'] != $date && $testExistmedia['Media']['name'] != $filename){
						unlink(WWW_ROOT.$testExistmedia['Media']['link']);
						$this->_etatDuRepertoire($testExistmedia['Media']['link']);
					}
				}
				$options = array('conditions'=>array('Media.id !='.$id,'Media.name'=> $filename));
				$testExistBDD = $this->Media->find('first',$options);
				if($testExistBDD){
					die('{"statut":false, "message": "ERROR : Publication '.$filename.$ext.' already exists"}');
					$success_transfert = false;
				}

				if($project_id != "" || $project_id != null){
					$v = 'uploads/'.$date.'/Project'.$project_id.'/'.$slug.$ext;
					$filenamedir = 'uploads/'.$date.'/Project'.$project_id;
				}else{
					$v = 'uploads/'.$date.'/'.$slug.$ext;
					$filenamedir = 'uploads/'.$date;
				}
				if(!file_exists($filenamedir)){
					mkdir($filenamedir,0777,true);
				}

				//Backup BDD
				$success = $this->Media->save(array(
						'id'=>$id,
						'project_id'=> $project_id,
						'reference'=>$reference,
						'name' =>$filename,
						'linkref' =>$linkRef,
						'type' => $ext,
						'link'=> $v,
						'slug'=> $slug,
						'date'=>$date));
				if($this->Media->validates()){
					if($success_transfert === true && $success){
						move_uploaded_file($file['tmp_name'], $v);
						die('{"statut":true, "message": "Publication '.$slug.$ext.' Uploaded"}');
					}else{
						die('{"statut":false, "message": "ERROR : Upload"}');
					}
				}else{
					$array['message'] = 'ERROR fields';
					$array['data'] = array('Media'=>$this->Media->error_messages());
					$array['statut']  = 'false';
					echo json_encode($array);
					exit;
				}
			}else{
				//if ajax
				
				$this->request->data['Media']['date'] = $this->request->data['Media']['date']['year'];
				if($this->RequestHandler->isAjax()){
					if($this->Media->validates()){
						//test if this publication have already a media
						if(isset($this->request->data['listfield']) && $this->request->data['listfield'] == "empty"){
							$optionsmedia = array('conditions'=>array('Media.id'=> $id));
							$testExistmedia = $this->Media->find('first',$optionsmedia);
							if(!empty($testExistmedia) && $testExistmedia['Media']['link'] !=""){
								unlink(WWW_ROOT.$testExistmedia['Media']['link']);
								$this->_etatDuRepertoire($testExistmedia['Media']['link']);
								$this->request->data['Media']['name'] = "";
								$this->request->data['Media']['type'] = "";
								$this->request->data['Media']['link'] = "";
								$this->request->data['Media']['slug'] = "";
							}
						}
						//test if this publication change date but have media
						$optionsdate = array('conditions'=>array('Media.id'=> $id,'Media.name != ""','Media.date !='.$this->request->data['Media']['date']));
						$testExistmediadate = $this->Media->find('first',$optionsdate);
						if(!empty($testExistmediadate)){
								
							$project_id = $testExistmediadate['Media']['project_id'];
							$date = $this->request->data['Media']['date'];
							$slug = $testExistmediadate['Media']['slug'];
							$ext = $testExistmediadate['Media']['type'];
							if($project_id != "" || $project_id != null){
								$v = 'uploads/'.$date.'/Project'.$project_id.'/'.$slug.$ext;
								$filenamedir = 'uploads/'.$date.'/Project'.$project_id;
							}else{
								$v = 'uploads/'.$date.'/'.$slug.$ext;
								$filenamedir = 'uploads/'.$date;
							}
							if(!file_exists($filenamedir)){
								mkdir($filenamedir,0777,true);
							}
							rename($testExistmediadate['Media']['link'], $v);
							$this->request->data['Media']['link'] = $v;
							$this->_etatDuRepertoire($testExistmediadate['Media']['link']);
						}
						$this->Media->set($this->request->data);
						if ($this->Media->save($this->request->data)) {
								
							$array['message'] = 'Publication updated';
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
						$array['message'] = 'ERROR fields';
						$array['data'] = array('Media'=>$this->Media->error_messages());
						$array['statut']  = 'error';
						echo json_encode($array);
						exit;
					}
				}else{
					//if not ajax
					if ($this->Media->save($this->request->data)) {
						$this->Session->setFlash('Publication updated', 'default', array('class'=>'alert alert-success'));
						$this->redirect(array('action' => '/'));
					}
					else{
						$this->Session->setFlash('Error update Please, try again.','default', array('class'=>'alert alert-error'));
					}
				}
			}
		}else {
			$this->request->data = $this->Media->read(null, $id);
		}
		$sessionId = $this->Session->read('Auth.User.id');
		if(isset($sessionId) && !empty($sessionId)){
			$publication = $this->Media->find('first',array('conditions'=>array('Media.id'=>$id)));
			$projects = $this->Media->Project->find('list');
			$this->set(compact('projects','publication'));
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
		if(isset($_GET['action']) && $_GET['action'] == "delete"){
			$this->Media->id = $_GET['id'];
			if (!$this->Media->exists()) {
				throw new NotFoundException(__('Invalid media'));
			}
			$link = $this->Media->field('link');

			if ($this->Media->delete()) {
				if($link){
					unlink(WWW_ROOT.$link);
					$this->_etatDuRepertoire($link);
				}

				$array['message'] 	= 'Publication deleted.';
				$array['statut']  	= 'success';
				$array['id']  		= $_GET['id'];
				$array['idtable']  	= $_GET['idtable'];
				echo json_encode($array);
				exit;
			}
			$array['message'] = 'Publication was not deleted.';
			$array['statut']  = 'error';
			echo json_encode($array);
			exit;
		}else {throw new MethodNotAllowedException();
		}
	}


	/**
	 * for extract a list of publications in text file
	 */
	function extract(){

		$recupInfo = $this->Media->find('all',array('fields' => array('Media.*')));

		$file = 'extracts/'.date("dmY").'.txt';
		if(file_exists($file))unlink($file);
		if($fp = fopen($file,"a")){
			fputs($fp, "\n");
			fputs($fp, "Extracts values of ". date('d-m-Y')."\r\n\r\n");

			$yearMedia = array();
			foreach($recupInfo as $media){
				//foreach by project
				foreach ($media as $dateMedia){
					$yearMedia[]= $dateMedia['date'];
				}
				$yearMediaUnique = array_unique($yearMedia);
				//sort table
				arsort($yearMediaUnique);
			}
			foreach ($yearMediaUnique as $date){
				fputs($fp,$date."\r\n\r\n");
				foreach($recupInfo as $media){
					foreach($media as $infomedia){
						if($infomedia['date'] == $date){
							if($infomedia['reference'] != null || $infomedia['reference'] != ""){
								fputs($fp, '  - '.$infomedia['reference']."\r\n");
							}else{
								fputs($fp, " - NO REFERENCE"."\r\n");
							}
						}
					}
				}
			}
			fclose($fp);
			$this->response->file('extracts/'.date("dmY").'.txt', array('download' => true, 'name' => date("dmY").'.txt'));
			$this->autoRender = false;

			
		}else{
			$this->redirect('/');
			$this->Session->setFlash('ERROR Extract', 'default', array('class'=>'alert alert-error'));
		}
	}
	function downloadtxt($filename){

		$link = $this->Media->find('first',array('fields'=>array('Media.link'),'conditions'=>array('Media.name'=>$filename)));
		$this->response->file($link['Media']['link'], array('download' => true, 'name' => $filename.'txt'));
		$this->autoRender = false;
	}

	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index() {
		$this->Media->recursive = 0;
		$this->set('media', $this->paginate());
	}

	/**
	 * admin_view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_view($id = null) {
		$this->Media->id = $id;
		if (!$this->Media->exists()) {
			throw new NotFoundException(__('Invalid media'));
		}
		$this->set('media', $this->Media->read(null, $id));
	}

	/**
	 * admin_add method
	 *
	 * @return void
	 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Media->create();
			if ($this->Media->save($this->request->data)) {
				$this->Session->setFlash(__('The media has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The media could not be saved. Please, try again.'));
			}
		}
		$projects = $this->Media->Project->find('list');
		$this->set(compact('projects'));
	}

	/**
	 * admin_edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_edit($id = null) {
		$this->Media->id = $id;
		if (!$this->Media->exists()) {
			throw new NotFoundException(__('Invalid media'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Media->save($this->request->data)) {
				$this->Session->setFlash(__('The media has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The media could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Media->read(null, $id);
		}
		$projects = $this->Media->Project->find('list');
		$this->set(compact('projects'));
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
		$this->Media->id = $id;
		if (!$this->Media->exists()) {
			throw new NotFoundException(__('Invalid media'));
		}
		if ($this->Media->delete()) {
			$this->Session->setFlash(__('Media deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Media was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
