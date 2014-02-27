	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this-><?php echo $currentModelName; ?>->id = $id;
		if (!$this-><?php echo $currentModelName; ?>->exists()) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		if ($this-><?php echo $currentModelName; ?>->delete()) {
			$this->Session->setFlash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> deleted'), 'default', array(), 'good');
            $this->redirect($this->redirectUrl);
		}
		$this->Session->setFlash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> was not deleted'), 'default', array(), 'bad');
		$this->redirect(array('action' => 'index'));
	}