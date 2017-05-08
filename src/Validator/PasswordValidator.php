<?php

require_once __DIR__ . '/../Validator/Validator.php';

class PasswordValidator extends Validator {

    public function validate()
    {
        if($this->isValidatable()) {
            if(preg_match("/^[a-zA-Z0-9]+$/", $this->value) != 1) {
                $this->errorMessage = 'Password pattern does not match.';
            }
            $this->validated=true;
        }
    }
}