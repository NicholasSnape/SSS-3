<?php

require_once __DIR__ . '/../Validator/Validator.php';

class NumberValidator extends Validator {
	private $minRange = 0;
	private $maxRange = 5000;

	public function validate()
	{
		if($this->isValidatable()) {
			$options = array(
				'options'=>array(
					'min_range'=>$this->minRange,
					'max_range'=>$this->maxRange,
				),
			);

			if(filter_var($this->value, FILTER_VALIDATE_INT, $options) === FALSE) {
				$this->errorMessage = 'Number must be an integer between' . $this->minRange . ' and ' . $this->maxRange;
			}
			$this->validated = true;
		}
	}

	public function setMinimumRange($minRange) {
		if($this->isInteger($minRange)) {
			$this->minRange = $minRange;
		} else {
			throw new \InvalidArgumentException('Integer required');
		}
	}

	public function setMaximumRange($maxRange) {
		if($this->isInteger($maxRange)) {
			$this->maxRange = $maxRange;
		} else {
			throw new \InvalidArgumentException('Integer required');
		}
	}

	private function isInteger($val) {
		return ctype_digit(strval($val));
	}
}