<?php

require_once __DIR__ . '/../Collection/Collection.php';
require_once __DIR__ . '/../Validator/Validator.php';

class ValidatorSet extends Collection
{
    public function addItem($validator, $key=null)
    {
    	if($validator instanceof Validator) {
			parent::addItem($validator, $key);
		} else {
    		throw new Exception("Validator type object expected.");
		}
    }

    public function getErrors()
    {
        $errors = array();
        foreach($this as $key=>$validator) {
            $validator->validate();
            if($validator->hasError())
                $errors[$key] = $validator->getError();
        }
        return $errors;
    }
}