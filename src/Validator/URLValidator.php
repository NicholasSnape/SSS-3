<?php

require_once __DIR__ . '/../Validator/Validator.php';

class URLValidator extends Validator {

	public function validate()
	{
		if($this->isValidatable()) {
            if (filter_var($this->value, FILTER_VALIDATE_URL) === FALSE) {
                $this->errorMessage = "Not a valid URL";
            }
			$this->validated = true;
		}
	}
}