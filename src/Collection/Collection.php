<?php

class Collection implements Iterator, ArrayAccess  {
	private $members = array();
	private $position = 0;

	public function addItem($item, $key = null) {
		if($key) {
			if($this->exists($key)) {
				throw new Exception('Key already exists.');
			}
			$this->members[$key] = $item;
		} else {
			$this->members[] = $item;
		}
	}

	public function removeItem($key) {
		if($this->exists($key)) {
			unset($this->members[$key]);
		} else {
			throw new Exception('Key does not exist.');
		}
	}

	public function getItem($key) {
		if($this->exists($key)) {
			return $this->members[$key];
		} else {
			throw new Exception('Key does not exist.');
		}
	}

	public function keys() {
		return array_keys($this->members);
	}

	public function exists($key) {
		return isset($this->members[$key]);
	}

	public function length() {
		return sizeof($this->members);
	}

	public function current() {
		return $this->members[$this->key()];
	}

	public function key() {
		$keys = $this->keys();
		return $keys[$this->position];
	}

	public function next() {
		$this->position++;
	}

	public function rewind() {
		$this->position = 0;
	}

	public function valid() {
		return $this->position < $this->length();
	}

	public function offsetExists($offset) {
		return $this->exists($offset);
	}

	public function offsetGet($offset) {
		return $this->getItem($offset);
	}

	public function offsetSet($offset, $value) {
		$this->addItem($value, $offset);
	}

	public function offsetUnset($offset) {
		$this->removeItem($offset);
	}
}