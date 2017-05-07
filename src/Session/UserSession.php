<?php

require_once __DIR__ . '/../Database/IDatabase.php';
require_once __DIR__ . '/DatabaseSession.php';

class UserSession extends DatabaseSession {

	public function __construct(IDatabase $dbConn) {
		parent::__construct($dbConn);
		session_start();
	}

    public function logIn($name, $password)
    {
        if($this->isLoggedIn()) {
            $this->logOut();
            session_start();
        }
        $parameters['fields'] = array('username', 'auth');
        $parameters['table'] = 'users';
        $parameters['conditions'] = array('username'=>$name, 'password'=>$password);
        $result = $this->dbConn->select($parameters);
        if($result) {
            $_SESSION['authorisation'] = $result[0]['auth'];
            $_SESSION['username'] = $name;
            return true;
        } else {
            return false;
        }
    }

    public function logOut()
    {
        $_SESSION = array();
        if(ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['username']);
    }

    public function username()
    {
        return $_SESSION['username'];
    }

    public function authorisation()
    {
        return $_SESSION['authorisation'];
    }
} 