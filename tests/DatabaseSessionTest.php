<?php

require_once __DIR__ . '/Fake/FakeDatabase.php';
require_once __DIR__ . '/../src/Session/DatabaseSession.php';


class DatabaseSessionTest extends PHPUnit_Framework_TestCase {
	public function testInstantiate() {
		$this->assertInstanceOf("DatabaseSession", new DatabaseSession(new FakeDatabase()));
	}

	public function testReadSuccessful() {
		$returnValue = array(array(
			'sess_data'=>'username|s:4:"user"'
		));
		$sess = new DatabaseSession(new FakeDatabase($returnValue));
		$this->assertEquals('username|s:4:"user"', $sess->read(1));
	}

	public function testReadNotSuccessful() {

		$sess = new DatabaseSession(new FakeDatabase());
		$this->assertEquals('', $sess->read(1));
	}

	public function testWriteSuccessful() {

		$sess = new DatabaseSession(new FakeDatabase(1));

		$this->assertTrue($sess->write(1, 'serialised_data'));
	}

	public function testWriteNotSuccessful() {
		$sess = new DatabaseSession(new FakeDatabase(2));
		$this->assertFalse($sess->write(1, 'serialised_data'));

		$sess = new DatabaseSession(new FakeDatabase(null));
		$this->assertFalse($sess->write(1, 'serialised_data'));
	}

	public function testDestroySuccessful() {

		$sess = new DatabaseSession(new FakeDatabase(1));

		$this->assertTrue($sess->write(1, 'serialised_data'));
	}


	public function testDestroyNotSuccessful() {
		$sess = new DatabaseSession(new FakeDatabase(null));
		$this->assertFalse($sess->write(1, 'serialised_data'));
	}
}
