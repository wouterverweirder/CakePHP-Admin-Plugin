<?php

class BacksiteShell extends Shell {

	public function initialize() {
		Configure::load($this->plugin . '.console');

		if(empty($this->tasks)) {
			$this->tasks = array();
		}
		$this->tasks[] = $this->plugin . '.BacksiteController';
		$this->tasks[] = $this->plugin . '.BacksiteView';
		parent::initialize();
	}

	public function main() {
		$this->BacksiteController->execute();
		$this->BacksiteView->execute();
	}
}