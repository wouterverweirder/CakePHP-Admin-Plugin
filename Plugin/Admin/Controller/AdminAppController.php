<?php
App::uses('AppController', 'Controller');
class AdminAppController extends AppController {

    public $backendPluginName = null;
    public $backendPluginNameUnderscored = null;
    public $redirectUrl = null;

	public $helpers = array('Form', 'Html', 'Js', 'Session', 'Time');
	public $components = array(
        'Session',
        'Auth',
        'RequestHandler',
        'Paginator'
    );

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->backendPluginName = $this->plugin;
        $this->backendPluginNameUnderscored = Inflector::underscore($this->backendPluginName);
        $this->helpers[] = $this->backendPluginName . '.ExtendedForm';
        $this->set('backendPluginName', $this->backendPluginName);
        $this->set('backendPluginNameUnderscored', $this->backendPluginNameUnderscored);
    }

	public function beforeFilter() {
        if(!empty($this->request->query['redirect'])) {
            if(!empty($this->request->base) && strpos($this->request->query['redirect'], $this->request->base) === 0) {
                $this->request->query['redirect'] = substr($this->request->query['redirect'], strlen($this->request->base));
            }
            $this->redirectUrl = $this->request->query['redirect'];
        } else {
            if($this->request->action == 'index' && !$this->request->is('ajax')) {
                $this->redirectUrl = '/' . $this->request->url;
            } else {
                $this->redirectUrl = $this->referer(array('action' => 'index'), true);
            }
        }
        //remove this block after you created the first user
        $this->Auth->allow();
        return;
        //end remove this block after you created the first user
		$this->Auth->authorize = array('Controller');
		$this->Auth->authenticate = array('Form' => array(
                'fields' => array('username' => 'email')
            )
        );
        $this->Auth->flash = array('element' => 'default', 'key' => 'auth', 'params' => array('class' => 'alert'));
        $this->Auth->loginAction = array('controller' => $this->backendPluginNameUnderscored . '_users', 'action' => 'login', 'plugin' => $this->backendPluginNameUnderscored);
        $this->Auth->logoutRedirect = array('controller' => 'pages', 'action' => 'display', 'home', 'plugin' => false);
        $this->Auth->loginRedirect = array('controller' => $this->backendPluginNameUnderscored . '_pages', 'action' => 'display', 'home', 'plugin' => Inflector::underscore($this->backendPluginName));
	}

	public function beforeRender() {
		$this->set('redirectUrl', $this->redirectUrl);
	}

	public function isAuthorized($user = null) {
        if(!empty($user)) {
            return true;
        }
    }
}

