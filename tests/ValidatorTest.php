<?php

require_once __DIR__ . '/../src/Validator/EmailValidator.php';
require_once __DIR__ . '/../src/Validator/NameValidator.php';
require_once __DIR__ . '/../src/Validator/NumberValidator.php';
require_once __DIR__ . '/../src/Validator/ValidatorSet.php';


class TestValidation extends PHPUnit_Framework_TestCase {

	public function testEmailFail() {
		$email = new EmailValidator('notanemail');
		$this->assertEquals(true, $email->hasError());
	}

	public function testEmailSuccess() {
		$email = new EmailValidator('me@here.com');
		$this->assertEquals(false, $email->hasError());
	}

	public function testEmailRequired() {
		$email = new EmailValidator('', true);
		$this->assertEquals('This is a required field', $email->getError());
	}

	public function testNameFailTooManyForDefaultLength() {
		$name = new NameValidator('-----26characters---------');
		$this->assertEquals(true, $name->hasError());
	}

	public function testNameSuccessLimitForDefaultLength() {
		$name = new NameValidator('-----25characters--------');
		$this->assertEquals(false, $name->hasError());
	}

	public function testNameRequired() {
		$age = new NameValidator('', true);
		$this->assertEquals('This is a required field', $age->getError());
	}

	public function testAgeFailTooLow() {
		$age = new NumberValidator('20');
		$age->setMinimumRange(21);
		$this->assertEquals(true, $age->hasError());
	}

	public function testAgeFailTooHigh() {
		$age = new NumberValidator('45');
		$age->setMaximumRange(44);
		$this->assertEquals(true, $age->hasError());
	}

	public function testAgeSuccessLowBoundary() {
		$age = new NumberValidator('21');
		$age->setMinimumRange(21);
		$this->assertEquals(false, $age->hasError());
	}

	public function testAgeSuccessHighBoundary() {
		$age = new NumberValidator('44');
		$age->setMaximumRange(44);
		$this->assertEquals(false, $age->hasError());
	}

	public function testFailNonIntegerString() {
		$age = new NumberValidator('Twenty-one');
		$this->assertEquals(true, $age->hasError());
	}

	public function testFailNonIntegerDecimalPlace() {
		$age = new NumberValidator('21.1');
		$this->assertEquals(true, $age->hasError());
	}

	public function testFailMinRangeString() {
		$this->setExpectedException('InvalidArgumentException', 'Integer required');
		$age = new NumberValidator('21');
		$age->setMinimumRange('Twenty-one');
	}

	public function testFailMinRangeDecimalPlace() {
		$this->setExpectedException('InvalidArgumentException', 'Integer required');
		$age = new NumberValidator('21');
		$age->setMinimumRange('21.1');
	}

	public function testAgeRequired() {
		$age = new NumberValidator('', true);
		$this->assertEquals('This is a required field', $age->getError());
	}

	public function testSet() {
		$valSet = new ValidatorSet();
		$valSet->addItem(new EmailValidator('notanemail'), 'email');
		$email = $valSet->getItem('email');
		$this->assertEquals(true, $email->hasError());
	}

	public function testSetReturnErrors() {
		$valSet = new ValidatorSet();
		$valSet->addItem(new EmailValidator('notanemail'), 'email1');
		$valSet->addItem(new EmailValidator('me@here.com'), 'email2');
		$expected = array('email1' => 'Not a valid email address');
		$this->assertEquals($expected, $valSet->getErrors());
	}
}
