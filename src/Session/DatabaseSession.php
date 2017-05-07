<?php

require_once __DIR__ . '/../Database/IDatabase.php';

class DatabaseSession implements SessionHandlerInterface
{
    protected $dbConn;
    private $table = 'sessions';

    public function __construct(IDatabase $dbConn)
    {
        $this->dbConn = $dbConn;
        if(session_status() == PHP_SESSION_NONE){
            session_set_save_handler($this);
        } else {
            throw new Exception('Cannot set session save handler, session is already active.');
        }
    }

    public function open($save_path, $name)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($session_id)
    {
        $parameters['fields'] = array('sess_data');
        $parameters['table'] = $this->table;
        $parameters['conditions'] = array('sess_id'=>$session_id);

        $result = $this->dbConn->select($parameters);

        return (string)@$result[0]['sess_data'];
    }

    public function write($session_id, $session_data)
    {
		$parameters['fields'] = array('sess_id', 'sess_data', 'modified');
        $parameters['table'] = $this->table;
        $parameters['records'] = array(array($session_id, $session_data,
            time()));

        if(!$status = $this->insertHasSucceeded($parameters)) {
            $parameters['conditions'] = array('sess_id'=>$session_id);
            $parameters['updateValues'] = array(
                'sess_data'=>$session_data,
                'modified'=>time()
            );
			$status = $this->dbConn->update($parameters) == 1;
        }
        return $status;
    }

    private function insertHasSucceeded($parameters)
    {
        return $this->dbConn->insert($parameters) == 1;
    }

    public function destroy($session_id)
    {
        $parameters['table'] = $this->table;
        $parameters['conditions'] = array('sess_id'=>$session_id);
        return $this->dbConn->delete($parameters) > 0;
    }

    public function gc($maxlifetime)
    {
        $expectedTime = time() - $maxlifetime;
        $parameters['table'] = $this->table;
        $parameters['conditions'] = array('modified'=>$expectedTime);
        $parameters['operator'] = '<';
        $this->dbConn->delete($parameters);
        return true;
    }
}