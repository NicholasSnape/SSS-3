<?php

require_once __DIR__ . '/../../src/Database/IDatabase.php';

class FakeDatabase implements IDatabase {
	public $returnValue;

	public function __construct($returnValue = null) {
		$this->returnValue = $returnValue;
	}

	public function select($parameters) {
		return $this->getReturn();
	}

	public function insert($parameters) {
		return $this->getReturn();
	}

	public function update($parameters) {
		return $this->getReturn();
	}

	public function delete($parameters) {
		return $this->getReturn();
	}

	private function getReturn() {
		if(isset($this->returnValue)) {
			return $this->returnValue;
		} else {
			return false;
		}
	}
}