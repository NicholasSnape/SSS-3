<?php

abstract class Validator
{
    protected $errorMessage = '';
    protected $value;
    protected $validated = false;
    protected $required;

    public function __construct($value='', $required=false)
    {
        $this->value = trim($value);
        $this->required = $required;
    }

    public function hasError()
    {
        if(!$this->validated) {
            $this->validate();
        }
        return $this->errorMessage != '';
    }

    public function getError()
    {
        if(!$this->validated) {
            $this->validate();
        }
        return $this->errorMessage;
    }

    public function getSanitisedValue()
    {
        return htmlentities($this->value, ENT_QUOTES, 'UTF-8');
    }

    public function isValidatable()
    {
        if($this->value === '') {
            if($this->required)
                $this->errorMessage = 'This is a required field';
        } else {
            return true;
        }
        return false;
    }

    abstract public function validate();
}