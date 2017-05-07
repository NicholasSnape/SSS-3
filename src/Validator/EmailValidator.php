<?php

require_once __DIR__ . '/../Validator/Validator.php';

class EmailValidator extends Validator {

	public function validate()
	{
		if($this->isValidatable()) {
			if(filter_var($this->value, FILTER_VALIDATE_EMAIL) === FALSE) {
				$this->errorMessage = 'Not a valid email address';
			}
			$this->validated = true;
		}
	}
}