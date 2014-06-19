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
    
    protected function _isAuthorized($user) {
        return ($this->action == 'login' || $this->action == 'logout');
    }