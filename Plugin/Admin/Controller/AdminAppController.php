<?php
App::uses('AppController', 'Controller');
class AdminAppController extends AppController {

    public $backendPluginName = null;
    public $backendPluginNameUnderscored = null;

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
        $this->helpers[] = $this->backendPluginName . '.Menu';
        $this->set('backendPluginName', $this->backendPluginName);
        $this->set('backendPluginNameUnderscored', $this->backendPluginNameUnderscored);
    }

	public function beforeFilter() {
		$this->Auth->authorize = array('Controller');
		$this->Auth->authenticate = array('Form' => array(
                'fields' => array('username' => 'email')
            )
        );
        $this->Auth->loginAction = array('controller' => $this->backendPluginNameUnderscored . '_users', 'action' => 'login', 'plugin' => $this->backendPluginNameUnderscored);
        $this->Auth->logoutRedirect = array('controller' => 'pages', 'action' => 'display', 'home', 'plugin' => false);
        $this->Auth->loginRedirect = array('controller' => $this->backendPluginNameUnderscored . '_pages', 'action' => 'display', 'home', 'plugin' => Inflector::underscore($this->backendPluginName));
	}

	public function beforeRender() {
		$menuItems = array('items' => array());

        $menuItems['items']['General'] = array('target' => "/{$this->backendPluginNameUnderscored}", 'items' => array());
        $menuItems['items']['General']['items']['Gebruikers'] = array('target' => "/{$this->backendPluginNameUnderscored}/users", 'items' => array());

        $menuItems['items']['Logout'] = array ('target' => "/{$this->backendPluginNameUnderscored}/users/logout", 'items' => array ());

        $this->set('menuItems', $menuItems);
	}

	public function isAuthorized($user = null) {
        if(!empty($user)) {
            return true;
        }
    }
}

