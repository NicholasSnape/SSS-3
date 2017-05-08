<?php

require_once __DIR__ . '/../Validator/Validator.php';

class DOBValidator extends Validator {

	public function validate()
	{
		if($this->isValidatable()) {
		    $mindob = strtotime("-18 years");
		    $thisdob = strtotime($this->value);
		    if ($thisdob > $mindob) {
		        $this->errorMessage = "Not old enough";
            }
			$this->validated = true;
		}
	}
}