<?php

require_once __DIR__ . '/../src/Database/Database.php';

class DatabaseTest extends PHPUnit_Framework_TestCase {
	private $testConn;
	private $db;

    protected function setUp() {
    	$cfg = array(
    		'db'=>array(
    			'host'=>'localhost',
				'user'=>'test',
				'pass'=>'test',
				'db'=>'test'
			)
		);
        $this->db = new Database($cfg);

        $this->testConn = new \mysqli(
            $cfg['db']['host'],
            $cfg['db']['user'],
            $cfg['db']['pass'],
            $cfg['db']['db']
        );
        $sqlTable = <<<CREATETABLE
            CREATE TABLE IF NOT EXISTS `testtable` (
              `Id` int(4) NOT NULL AUTO_INCREMENT,
              `FirstName` varchar(50) NOT NULL,
              `Surname` varchar(50) NOT NULL,
              PRIMARY KEY (`Id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
CREATETABLE;

        $sqlInsert = <<<CREATEQUERY
            INSERT INTO `testtable` (`Id`, `FirstName`, `Surname`) VALUES
            (1, 'Philip', 'Windridge'),
            (2, 'Alastair', 'Dawes');
CREATEQUERY;

	    $this->testConn->query($sqlTable);
	    $this->testConn->query($sqlInsert);
    }

    public function tearDown() {
        $sql = "DROP TABLE `testtable`";
	    $this->testConn->query($sql);
    }

    public function testSimpleSelect() {
        $parameters = array(
            'fields'=>array('*'),
            'table'=>'testtable'
        );
        $this->assertEquals($this->allRecords(), $this->db->select($parameters));
    }

    public function testSimpleTwoFields() {
        $parameters = array(
            'fields'=>array('FirstName', 'Surname'),
            'table'=>'testtable'
        );
        $this->assertEquals($this->allFirstNameSurname(), $this->db->select($parameters));
    }

    public function testSelectNoReturn() {
        $parameters = array(
            'fields'=>array('FirstName', 'Surname'),
            'table'=>'testtable',
            'conditions'=>array('FirstName'=>'Fred')
        );
        $this->assertFalse($this->db->select($parameters));
    }

    public function testSelectWhereClause() {
        $parameters = array(
            'fields'=>array('FirstName', 'Surname'),
            'table'=>'testtable',
            'conditions'=>array('FirstName'=>'Philip')
        );
	    $expected = $this->oneFirstNameSurname(1);
	    $actual = $this->db->select($parameters);
	    $this->assertEquals($expected, $actual);
    }

    public function testSelectWhereClauseOnNumberId() {
	    $parameters = array(
		    'fields'=>array('FirstName', 'Surname'),
		    'table'=>'testtable',
		    'conditions'=>array('Id'=>1)
	    );
	    $expected = $this->oneFirstNameSurname(1);
	    $actual = $this->db->select($parameters);
	    $this->assertEquals($expected, $actual);
    }

	public function testSelectWhereLessThanNumberId() {
		$parameters = array(
			'fields'=>array('FirstName', 'Surname'),
			'table'=>'testtable',
			'conditions'=>array('Id'=>2),
			'operator'=>'<'
		);
		$expected = $this->oneFirstNameSurname(1);
		$actual = $this->db->select($parameters);
		$this->assertEquals($expected, $actual);
	}

	public function testSelectWhereGreaterThanNumberId() {
		$parameters = array(
			'fields'=>array('FirstName', 'Surname'),
			'table'=>'testtable',
			'conditions'=>array('Id'=>1),
			'operator'=>'>'
		);
		$expected = $this->oneFirstNameSurname(2);
		$actual = $this->db->select($parameters);
		$this->assertEquals($expected, $actual);
	}

	public function testSelectWhereLIKE() {
		$parameters = array(
			'fields'=>array('FirstName', 'Surname'),
			'table'=>'testtable',
			'conditions'=>array('FirstName'=>'%hi%'),
			'operator'=>'LIKE'
		);
		$expected = $this->oneFirstNameSurname(1);
		$actual = $this->db->select($parameters);
		$this->assertEquals($expected, $actual);
	}

	public function testInsertOneRecord() {
		$parameters = array(
			'fields'=>array('FirstName', 'Surname'),
			'table'=>'testtable',
			'records'=>array(
				array('Fiona', 'Knight')
			)
		);
		$this->assertEquals(1, $this->db->insert($parameters));
	}

	public function testInsertAndRetrieveOneRecord() {
		$parameters = array(
			'fields'=>array('FirstName', 'Surname'),
			'table'=>'testtable',
			'records'=>array(
				array('Fiona', 'Knight')
			)
		);
		$this->db->insert($parameters);

		$parameters += ['conditions'=>array('Id'=>3)];

		$expected = array(array('FirstName'=>'Fiona', 'Surname'=>'Knight'));
		$actual = $this->db->select($parameters);
		$this->assertEquals($expected, $actual);
	}

	public function testInsertMultipleRecords() {
		$parameters = array(
			'fields'=>array('FirstName', 'Surname'),
			'table'=>'testtable',
			'records'=>array(
				array('Fiona', 'Knight'),
				array('Jan', 'Lawton'),
				array('Rachael', 'Trubshaw'),
				array('Robin', 'Oldham')
			)
		);
		$this->assertEquals(4, $this->db->insert($parameters));
	}

	public function testUpdateSingleFieldAllRecords() {
		$parameters = array(
			'table'=>'testtable',
			'updateValues'=>array('FirstName'=>'Lecturer')
		);
		$this->assertEquals(2, $this->db->update($parameters));
	}

	public function testUpdateAndSelectSingleFieldAllRecords() {
		$parameters = array(
			'table'=>'testtable',
			'updateValues'=>array('FirstName'=>'Lecturer')
		);
		$this->db->update($parameters);
		$parameters += ['fields'=>array('FirstName', 'Surname')];
		$expected = array(
			array('FirstName'=>'Lecturer', 'Surname'=>'Windridge'),
			array('FirstName'=>'Lecturer', 'Surname'=>'Dawes')
		);

		$this->assertEquals($expected, $this->db->select($parameters));
	}

	public function testUpdateSingleFieldSingleRecord() {
		$parameters = array(
			'table'=>'testtable',
			'updateValues'=>array('FirstName'=>'Lecturer'),
			'conditions'=>array('FirstName'=>'Alastair')
		);
		$this->assertEquals(1, $this->db->update($parameters));
	}

	public function testUpdateMultipleFieldAllRecords(){
		$parameters = array(
			'table'=>'testtable',
			'updateValues'=>array(
				'FirstName'=>'Lecturer',
				'Surname'=>'Tutor'
			)
		);
		$this->assertEquals(2, $this->db->update($parameters));
	}

	public function testDeleteOneRecord() {
		$parameters = array(
			'table'=>'testtable',
			'conditions'=>array('FirstName'=>'Alastair')
		);
		$this->assertEquals(1, $this->db->delete($parameters));
	}

	public function testDeleteOneRecordSelectAll() {
		$parameters = array(
			'table'=>'testtable',
			'conditions'=>array('Id'=>2)
		);
		$this->db->delete($parameters);
		unset($parameters['conditions']);
		$parameters += ['fields'=>array('FirstName', 'Surname')];

		$expected = array(array('FirstName'=>'Philip', 'Surname'=>'Windridge'));
		$this->assertEquals($expected, $this->db->select($parameters));
	}

	public function testDeleteAllRecords() {
		$parameters = array(
			'table'=>'testtable'
		);
		$this->assertEquals(2, $this->db->delete($parameters));
	}

	private function allRecords() {
    	return array(
		    $this->getFirstRecord(),
		    $this->getSecondRecord()
	    );
	}

	private function allFirstNameSurname() {
		return array(
			$this->getFirstNameSurnameAsArray($this->getFirstRecord()),
			$this->getFirstNameSurnameAsArray($this->getSecondRecord())
		);
	}

	private function oneFirstNameSurname($recordNumber) {
    	if($recordNumber == 1) {
    		$record = $this->getFirstRecord();
	    } else {
    		$record = $this->getSecondRecord();
	    }
		return array($this->getFirstNameSurnameAsArray($record));
	}

	private function getFirstNameSurnameAsArray($record) {
		return array(
			'FirstName'=>$record['FirstName'],
			'Surname'=>$record['Surname']
		);
	}

	private function getFirstRecord() {
    	return array('Id'=>'1', 'FirstName'=>'Philip', 'Surname'=>'Windridge');
	}

	private function getSecondRecord() {
    	return array('Id'=>2, 'FirstName'=>'Alastair', 'Surname'=>'Dawes');
	}
}
