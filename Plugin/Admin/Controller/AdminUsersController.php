<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 * AdminUsersController
 *
 * @property User $User
 */
class AdminUsersController extends AdminAppController {

	public $uses = array('User');

    public function login() {
        if ($this->Auth->login()) {
            $this->redirect($this->Auth->redirect());
        } else {
            if($this->request->isPost()) {
                $this->Auth->flash(__('Invalid username or password, try again'));
            }
        }
    }

    public function logout() {
        $this->redirect($this->Auth->logout());
    }
	public function index() {
	    $conditions = array();
        $usersTableURL = array('controller' => 'admin_users', 'action' => 'index');

        //join get query & named params
        $params = array_merge($this->request->params['named']);
        foreach($this->request->query as $key => $value) $params[$key] = $value;

        foreach($params as $key => $value) {
            $split = explode('-', $key);
            $modelName = (sizeof($split) > 1) ? $split[0] : 'User';
            $property = (sizeof($split) > 1) ? $split[1] : $key;
            if($modelName == 'User' || !empty($this->User->belongsTo[$modelName])) {
                $this->loadModel($modelName);
                $modelObj = new $modelName();
                if(!empty($modelObj)) {
                    $columnType = $modelObj->getColumnType($property);
                    if(!empty($columnType)){
                        //add it to url
                        $usersTableURL[$key] = $value;
                        //add it to conditions
                        switch($columnType)
                        {
                            case 'string':
                                $conditions[$modelName . '.' . $property . ' LIKE'] = '%'.$value.'%';
                                break;
                            default:
                                $conditions[$modelName . '.' . $property] = $value;
                                break;
                        }
                    }
                }
            }

        }

		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate('User', $conditions, array()));
		$this->set('usersTableURL', $usersTableURL);
		//render as local table if it is an ajax request
        if($this->request->is('ajax'))
        {
            $this->render('table');
        }
	}

	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
        $user = $this->User->read(null, $id);
		$this->set('user', $user);
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'), 'default', array(), 'good');
                $this->redirect(array('action' => 'view', $this->User->id));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'default', array(), 'bad');
			}
		} else {
            //add the named params as data
            foreach($this->request->params['named'] as $param => $value) {
                $columnType = $this->User->getColumnType($param);
                if(!empty($columnType)) {
                    if(empty($this->request->data['User'])) $this->request->data['User'] = array();
                    $this->request->data['User'][$param] = $value;
                }
            }
        }
	}


	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'), 'default', array(), 'good');
                $this->redirect(array('action' => 'view', $this->User->id));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'default', array(), 'bad');
			}
		} else {
            $user = $this->User->read(null, $id);
			$this->request->data = $user;
		}

	}

	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'), 'default', array(), 'good');
            $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'), 'default', array(), 'bad');
		$this->redirect(array('action' => 'index'));
	}
}
