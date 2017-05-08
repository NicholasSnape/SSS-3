<?php

require_once __DIR__ . '/../Validator/Validator.php';

class NameValidator extends Validator
{
    private $length = 25;

    public function validate()
    {
        if($this->isValidatable()) {
            if(strlen($this->value) > $this->length) {
                $this->errorMessage = $this->length . ' characters maximum';
            }
            if(preg_match("/^[a-zA-Z]+$/", $this->value) != 1) {
                $this->errorMessage = 'Name pattern does not match.';
            }
            $this->validated=true;
        }
    }

    public function setLength($length)
    {
        if(is_integer($length)) {
            $this->length = $length;
        } else {
            throw new \Exception('Integer expected');
        }
    }
}