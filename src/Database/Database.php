<?php

require_once __DIR__ . '/IDatabase.php';

class Database implements IDatabase {

    private $dbConn;

    public function __construct($cfg)
    {
        $this->dbConn = new mysqli(
            $cfg['db']['host'],
            $cfg['db']['user'],
			$cfg['db']['pass'],
			$cfg['db']['db'])
        ;
        if (mysqli_connect_errno()) {
            throw new Exception('Failed to connect to MySQL: ' .
                mysqli_connect_error());
        }
    }

    public function select($parameters)
    {
        $sql = 'SELECT ' .
            implode(', ', $this->getFields($parameters['fields'])) .
            ' FROM ' . $this->prepareLabel($parameters['table'])
        ;
        if(isset($parameters['conditions'])) {
            $sql .= $this->getWhere(
                $parameters['conditions'],
                @$this->getOperator($parameters['operator'])
            );
        }
        if($result = $this->dbConn->query($sql)) {
            $returnArray = array();

            while ($row = $result->fetch_assoc()) {
                $returnArray[] = $row;
            }
            if ($returnArray) {
                return $returnArray;
            }
        }
        return false;
    }

    public function insert($parameters)
    {
        $sql = 'INSERT INTO ' . $this->prepareLabel($parameters['table']) .
            ' (' . implode(', ', $this->getFields($parameters['fields'])) .')' .
            ' VALUES ' .
            implode(', ', $this->getRecords($parameters['records']))
        ;
        $this->dbConn->query($sql);

        return $this->dbConn->affected_rows;
    }

    public function update($parameters)
    {
        $sql = 'UPDATE ' . $this->prepareLabel($parameters['table']) .
            ' SET ' .
            implode(', ', $this->getFieldValues($parameters['updateValues'], '='))
        ;
        if(isset($parameters['conditions'])) {
            $sql .= $this->getWhere(
                $parameters['conditions'],
                @$this->getOperator($parameters['operator'])
            );
        }

        $this->dbConn->query($sql);

        return $this->dbConn->affected_rows;
    }

    public function delete($parameters)
    {
        $sql = 'DELETE FROM ' . $this->prepareLabel($parameters['table']);
        if(isset($parameters['conditions'])) {
            $sql .= $this->getWhere(
                $parameters['conditions'],
                @$this->getOperator($parameters['operator'])
            );
        }

        $this->dbConn->query($sql);

        return $this->dbConn->affected_rows;
    }

    public function executeRaw($sql) {
    	return $this->dbConn->query($sql);
	}

    private function getFields($fields)
    {
        foreach($fields as $key=>$field) {
            $fields[$key] = $this->prepareLabel($field);
        }
        return $fields;
    }

    private function getRecords($records)
    {
        $preparedRecords = array();
        foreach($records as $recordValues) {
            foreach($recordValues as $key=>$value) {
                $recordValues[$key] = $this->prepareValue($value);
            }
            $preparedRecords[] = '(' . implode(', ', $recordValues) . ')';
        }
        return $preparedRecords;
    }

    private function getWhere($conditions, $operator)
    {
        $conditions = $this->getFieldValues($conditions, $operator);
        return ' WHERE ' . implode(' AND ', $conditions);
    }

    private function getOperator($operator)
    {
        $returnOperator = '=';
        if(isset($operator)) {
            $returnOperator = $operator;
        }
        return $returnOperator;
    }

    private function getFieldValues($fieldValues, $operator)
    {
        $resultArray = array();
        foreach($fieldValues as $field=>$value) {
            $resultArray[] = $this->prepareLabel($field) .
                ' ' . $operator .
                ' ' . $this->prepareValue($value)
            ;
        }
        return $resultArray;
    }

    private function prepareLabel($label)
    {
        if($label != '*') {
            $label = '`' . $label . '`';
        }
        return $label;
    }

    private function prepareValue($value)
    {
        if(!is_numeric($value)) {
            $value = '\'' . $value . '\'';
        }
        return $value;
    }
}
